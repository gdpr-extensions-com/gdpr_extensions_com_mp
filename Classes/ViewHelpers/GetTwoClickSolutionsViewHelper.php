<?php

namespace GdprExtensionsCom\GdprExtensionsComMp\ViewHelpers;

use GdprExtensionsCom\GdprExtensionsComMp\Domain\Repository\GdprManagerRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Database\Connection;


class GetTwoClickSolutionsViewHelper extends AbstractViewHelper
{

    /**
     * @var GdprManagerRepository
     */
    protected $gdprManagerRepository = null;


    /**
     * @return void
     */
    public function injectGdprManagerRepository(GdprManagerRepository $gdprManagerRepository)
    {
        $this->gdprManagerRepository = $gdprManagerRepository;
    }
    public function initializeArguments()
    {


    }

    public function render()
    {
        $packageManager = GeneralUtility::makeInstance(PackageManager::class);
        $extensions = ExtensionManagementUtility::getLoadedExtensionListArray();
        $extensionNames = [];

        foreach ($extensions as $extensionKey) {
            if ($packageManager->isPackageAvailable($extensionKey)) {
                $extensionName = $packageManager->getPackage($extensionKey)->getPackageMetaData()->getTitle();
                // Directly assign the name to the key in the associative array.
                $extensionNames[$extensionKey] = $extensionName;
            }
        }

        // Filter based on keys, looking for 'gdpr_two_x' in the extensionKey.
        $twoClickSolutions = array_filter($extensionNames, function ($key) {
            return str_contains($key, 'gdpr_two_x') || str_contains($key, 'gdpr_extensions_com');
        }, ARRAY_FILTER_USE_KEY); // Use ARRAY_FILTER_USE_KEY to filter by key.

        $gdprDellQb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');

        $gdprDellQb
            ->delete('tx_gdprextensionscomyoutube_domain_model_gdprmanager')
            ->where(
                $gdprDellQb->expr()->notIn(
                    'extension_title',
                    $gdprDellQb->createNamedParameter($twoClickSolutions,Connection::PARAM_STR_ARRAY)
                )
            )
            ->executeStatement();

            $twoClickSolutionsWithoutReviews = array_filter($twoClickSolutions, function ($ext) {
                // Use 'stripos' for case-insensitive search; returns false if 'review' is not found.
                return stripos($ext, 'review') === false;
            });

        $gdprManagers = $this->gdprManagerRepository->findAll();

        $installedTwoClickSol = [];
        foreach ($gdprManagers as $twoClickSol){
            array_push($installedTwoClickSol,$twoClickSol->getExtensionTitle());
        }

        $missingExtensions = array_diff($twoClickSolutionsWithoutReviews, $installedTwoClickSol);


        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');
        // dd($missingExtensions);

        foreach ($missingExtensions as $key => $value) {
            $queryBuilder
                ->insert('tx_gdprextensionscomyoutube_domain_model_gdprmanager')
                ->values([
                    'extension_title' => $value,
                    'extension_key' => $key,
                    'heading' => '', // Default empty string
                    'content' => '', // Default empty string
                    'button_text' => '', // Default empty string
                    'enable_background_image' => 0, // Default 0
                    'background_image' => '', // Default empty string
                    'background_image_color' => '', // Default empty string
                    'button_color' => '', // Default empty string
                    'text_color' => '', // Default empty string
                    'button_shape' => '' // Default empty string
                ])
                ->execute();
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');

        $gdprManagers = $queryBuilder
            ->select('*')
            ->from('tx_gdprextensionscomyoutube_domain_model_gdprmanager')
            ->where(
                $queryBuilder->expr()->notLike('extension_title',
                    $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards('review') . '%')
                )
            )
            ->execute()
            ->fetchAll();

        $normalizedGdprManagers = [];
        foreach ($gdprManagers as $gdprManager) {
            if(array_key_exists($gdprManager['extension_key'], $extensionNames)) {
                $normalizedGdprManagers[$gdprManager['extension_key']] = $gdprManager;
            }
        }
        $jsonString = json_encode($normalizedGdprManagers);

        return $jsonString;
    }

}

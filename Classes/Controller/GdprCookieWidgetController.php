<?php

namespace GdprExtensionsCom\GdprExtensionsComMp\Controller;

use GdprExtensionsCom\GdprExtensionsComMp\Domain\Repository\GdprManagerRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GdprCookieWidgetController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * ContentObject
     *
     * @var ContentObject
     */
    protected $contentObject = null;

    /**
     * Action initialize
     */
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
    protected function initializeAction()
    {
        $this->contentObject = $this->configurationManager->getContentObject();

        // intialize the content object
    }

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');

        $queryBuilder
            ->select('*')
            ->from('tx_gdprextensionscomyoutube_domain_model_cookiewidget');

        $result = $queryBuilder->execute()->fetchAssociative();
        if ($result["cookie_widget_image"] && strpos($result["cookie_widget_image"], '/') !== 0) {
            $result["cookie_widget_image"] = '/' . $result["cookie_widget_image"];
        }
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');
        $gdprSettingGoogleReviewlist = $queryBuilder
            ->select('*')
            ->from('tx_gdprextensionscomyoutube_domain_model_gdprmanager')->where(
                $queryBuilder->expr()->like('extension_title', $queryBuilder->createNamedParameter('%' . (string)'google_reviewlist' . '%'))
            );
        $settings =  $gdprSettingGoogleReviewlist->execute()->fetchAssociative();

        $gdprManagers = $this->gdprManagerRepository->fetchGdprManagerWithoutReviews()->toArray();

        foreach ($gdprManagers as $gdprManager) {
            // Clean properties should be an associative array which can be JSON encoded.
            if (is_array($gdprManager->_getCleanProperties())) {
                $properties = $gdprManager->_getCleanProperties();
                $normalizedGdprManagers[$properties['extensionKey']] = $properties;
                if($properties['extensionKey'] == 'gdpr_extensions_com_mp'){
                    $this->view->assign('metaCode', $properties['metaCode']);
                }
                if($properties['extensionKey'] == 'gdpr_two_x_gtm'){
                    $this->view->assign('gtmCode', $properties['gtmCode']);
                }
            }
        }

        $this->view->assign('cookieWidgetData', $result);
        $this->view->assign('GoogleReviewlistData', $this->contentObject->data);
        $this->view->assign('GoogleReviewlistSettings', $settings);
        $this->view->assign('rootPid', $GLOBALS['TSFE']->site->getRootPageId());
        return $this->htmlResponse();

    }

    public function ajaxAction() {
        $json_str = file_get_contents('php://input');
        // Get as an object
        $json_obj = json_decode($json_str);
        $rootId = (int)$json_obj->rootPid;

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_tracking');
        $result = $queryBuilder
            ->select('*')
            ->from('gdpr_tracking')
            ->where(
                $queryBuilder->expr()->eq(
                    'root_pid',
                    $queryBuilder->createNamedParameter($rootId)
                )
            )
            ->executeQuery()
            ->fetchAssociative();

        if($result){
            die(json_encode($result));
        }else{
            die(json_encode(['status' => 0]));
        }

    }


}

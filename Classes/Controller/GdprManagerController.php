<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComMp\Controller;


use GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\MapLocation;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\Connection;


/**
 * This file is part of the "gdpr-extensions-com-google_reviewlist" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023
 */

/**
 * GdprManagerController
 */
class GdprManagerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * gdprManagerRepository
     *
     * @var \GdprExtensionsCom\GdprExtensionsComMp\Domain\Repository\GdprManagerRepository
     */

    /**
     * @var ModuleTemplateFactory
     */
    protected $moduleTemplateFactory;
    protected $gdprManagerRepository = null;

    public function __construct(ModuleTemplateFactory $moduleTemplateFactory)
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        return $this->htmlResponse();

    }

    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(): \Psr\Http\Message\ResponseInterface
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

        $gdprManagers = $this->gdprManagerRepository->findAll();

        $installedTwoClickSol = [];
        foreach ($gdprManagers as $twoClickSol){
            array_push($installedTwoClickSol,$twoClickSol->getExtensionTitle());
        }

        $missingExtensions = array_diff($twoClickSolutions, $installedTwoClickSol);

        // dd($missingExtensions);


        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');

        foreach ($missingExtensions as $key => $value) {
        // dd($key);

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

        $uploadImageUrl = $this->uriBuilder->reset()
            ->uriFor('uploadImage');
        $saveCookieWidget = $this->uriBuilder->reset()
            ->uriFor('cookieWidget');

//        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');
//
//        $queryBuilder
//            ->select('*')
//            ->from('tx_gdprextensionscomyoutube_domain_model_cookiewidget');
//
//        $result = $queryBuilder->execute()->fetchAssociative();

        $mtData = $this->gdprManagerRepository->findByExtension_key('gdpr_extensions_com_mp')->toArray();
        return $this->redirect('edit',null,null,['gdprManager' => $mtData[0]]);

        $this->view->assign('uploadImageUrl', $uploadImageUrl);
        $this->view->assign('cookieWidgetData', $result);
        $gdprManagers = $this->gdprManagerRepository->findAll();
        $this->view->assign('gdprManagers', $gdprManagers);
        return $this->htmlResponse();
    }

    /**
     * action show
     *
     * @param \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction(\GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager): \Psr\Http\Message\ResponseInterface
    {
        $this->view->assign('gdprManager', $gdprManager);
        return $this->htmlResponse();
    }

    /**
     * action new
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function newAction(): \Psr\Http\Message\ResponseInterface
    {
        $uploadImageUrl = $this->uriBuilder->reset()
            ->uriFor('uploadImage');
        $this->view->assign('uploadImageUrl', $uploadImageUrl);

        return $this->htmlResponse();
    }

    /**
     * action create
     *
     * @param \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $newGdprManager
     */
    public function createAction(\GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $newGdprManager)
    {
        $this->gdprManagerRepository->add($newGdprManager);
        $this->redirect('list');
    }

     /**
     * action edit
     *
     * @param \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("gdprManager")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function editAction(\GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager): \Psr\Http\Message\ResponseInterface
    {

        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();
        $configurations = [];

        foreach ($sites as $siteKey => $site) {
            $configurations[$siteKey] = $site->getConfiguration();
        }
        $uploadImageUrl = $this->uriBuilder->reset()
            ->uriFor('uploadImage');
        $this->view->assign('uploadImageUrl', $uploadImageUrl);

        $this->view->assignMultiple([
            'google_review' => 0,
            'googlemaps' => 0,
            'matomo' => 0,
            'meta' => 0,
            'gtm' => 0,
        ]);

        if(strpos($gdprManager->getExtensionTitle(), 'Google-Review') !== false  ) {
            $this->view->assign('google_review', 1);
        }
        if($gdprManager->getExtensionTitle() == 'gdpr-extensions-com-googlemaps-2clicksolution'){
            $this->view->assign('googlemaps', 1);
        }
        if($gdprManager->getExtensionKey() == 'gdpr_extensions_com_mt'){
            $gdprManager->setMatomoCode(base64_decode($gdprManager->getMatomoCode()));
            $this->view->assign('matomo', 1);
        }
        if($gdprManager->getExtensionKey() == 'gdpr_extensions_com_mp'){
            $gdprManager->setMetaCode(base64_decode($gdprManager->getMetaCode()));
            $this->view->assign('meta', 1);
        }
        if($gdprManager->getExtensionKey() == 'gdpr_extensions_com_gt'){
            $this->view->assign('gtm', 1);
        }


        $this->view->assign('gdprManager', $gdprManager);
        $this->view->assign('sites', $configurations);

        return $this->htmlResponse();
    }

    /**
     * action save
     * @param \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager
     */
    public function saveAction(\GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager): \Psr\Http\Message\ResponseInterface
    {

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_tracking');

        // Check if the record with the given root_pid (siteId) already exists
        $existingRecord = $queryBuilder
            ->select('uid')
            ->from('gdpr_tracking')
            ->where(
                $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($gdprManager->getSiteId(), \PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchOne();

        if ($existingRecord) {
            // Record exists, so update it
            $queryBuilder
                ->update('gdpr_tracking')
                ->where(
                    $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($gdprManager->getSiteId(), \PDO::PARAM_INT))
                )
                ->set('meta_code', base64_encode($gdprManager->getMetaCode()))
                ->set('base_url', $gdprManager->getBaseUrl())
                ->set('meta_heading', $gdprManager->getHeading())
                ->set('meta_desc', $gdprManager->getContent())
                ->set('meta_button_text', $gdprManager->getButtonText())
                ->set('meta_enable_background_image', $gdprManager->getEnableBackgroundImage())
                ->set('meta_background_image', $gdprManager->getBackgroundImage())
                ->set('meta_background_image_color', $gdprManager->getBackgroundImageColor())
                ->set('meta_button_color', $gdprManager->getButtonColor())
                ->set('meta_button_text_color', $gdprManager->getButtonTextColor())
                ->set('meta_text_color', $gdprManager->getTextColor())
                ->set('meta_heading_color', $gdprManager->getHeadingColor())
                ->set('meta_button_shape', $gdprManager->getButtonShape())
                ->set('extension_title', $gdprManager->getExtensionTitle())
                ->set('extension_key', $gdprManager->getExtensionKey())
                ->executeStatement();
        } else {
            // No existing record, so insert a new one
            $queryBuilder
                ->insert('gdpr_tracking')
                ->values([
                    'meta_code' => base64_encode($gdprManager->getMetaCode()),
                    'root_pid' => $gdprManager->getSiteId(),
                    'base_url' => $gdprManager->getBaseUrl(),
                    'meta_heading' => $gdprManager->getHeading(),
                    'meta_desc' => $gdprManager->getContent(),
                    'meta_button_text' => $gdprManager->getButtonText(),
                    'meta_enable_background_image' => $gdprManager->getEnableBackgroundImage(),
                    'meta_background_image' => $gdprManager->getBackgroundImage(),
                    'meta_background_image_color' => $gdprManager->getBackgroundImageColor(),
                    'meta_button_color' => $gdprManager->getButtonColor(),
                    'meta_button_text_color' => $gdprManager->getButtonTextColor(),
                    'meta_text_color' => $gdprManager->getTextColor(),
                    'meta_heading_color' => $gdprManager->getHeadingColor(),
                    'meta_button_shape' => $gdprManager->getButtonShape(),
                    'extension_title' => $gdprManager->getExtensionTitle(),
                    'extension_key' => $gdprManager->getExtensionKey(),
                ])
                ->executeStatement();
        }


        return $this->redirect('editAdd', null, null, [
            'id' => $gdprManager->getSiteId(),
            'url' => $gdprManager->getBaseUrl(),
            'gdprManager' => $gdprManager
        ]);
    }

    /**
     * action editAdd
     *
     * @param int $id
     * @param string $url
     * @param \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager
     */
    public function editAddAction(int $id, string $url ,\GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager): \Psr\Http\Message\ResponseInterface
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();
        $configurations = [];

        foreach ($sites as $siteKey => $site) {
            $configurations[$siteKey] = $site->getConfiguration();
        }
        $uploadImageUrl = $this->uriBuilder->reset()
            ->uriFor('uploadImage');
        $this->view->assign('uploadImageUrl', $uploadImageUrl);



        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_tracking');
            $result = $queryBuilder
                ->select('*')
                ->from('gdpr_tracking')
                ->where(
                    $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
                )
                ->executeQuery()->fetchAssociative();
        if ($result) {
            $gdprManager->setMetaCode(base64_decode($result['meta_code']));
            $gdprManager->setBaseUrl($result['base_url']);
            $gdprManager->setSiteId($result['root_pid']);
            $gdprManager->setHeading($result['meta_heading']);
            $gdprManager->setContent($result['meta_desc']);
            $gdprManager->setButtonText($result['meta_button_text']);
            $gdprManager->setEnableBackgroundImage($result['meta_enable_background_image']);
            $gdprManager->setBackgroundImage($result['meta_background_image']);
            $gdprManager->setBackgroundImageColor($result['meta_background_image_color']);
            $gdprManager->setButtonColor($result['meta_button_color']);
            $gdprManager->setButtonTextColor($result['meta_button_text_color']);
            $gdprManager->setTextColor($result['meta_text_color']);
            $gdprManager->setHeadingColor($result['meta_heading_color']);
            $gdprManager->setButtonShape($result['meta_button_shape']);
            $gdprManager->setExtensionTitle($result['extension_title']);
            $gdprManager->setExtensionKey($result['extension_key']);
        }
        $this->view->assign('id', $id);
        $this->view->assign('url', $url);
        $this->view->assign('gdprManager', $gdprManager);
        return $this->htmlResponse();
    }
    /**
     * action update
     *
     * @param \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateAction(\GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager) : \Psr\Http\Message\ResponseInterface
    {
        $locationsData = [];
        $encodeScript = base64_encode($gdprManager->getMetaCode());
        $gdprManager->setMetaCode($encodeScript);
        if($this->request->hasArgument('tx_GdprTwoXMatomo_web_GdprTwoXMatomogdprmanager')){
            $locationsData = $this->request->getArgument('tx_GdprTwoXMatomo_web_GdprTwoXMatomogdprmanager')['locations'];
        }
        elseif ($this->request->hasArgument('locations')){
            $locationsData = $this->request->getArgument('locations');
        }
        $gdprManager->clearLocations();
        foreach ($locationsData as $locationData) {
            if (!$locationData['lat'] || !$locationData['lon']) {
                continue;
            }
            $location = new MapLocation();
            $location->setTitle($locationData['title']);
            $location->setAddress($locationData['address']);
            $location->setLat((int)($locationData['lat']*1000000));
            $location->setLon((int)($locationData['lon']*1000000));

            $gdprManager->addLocation($location);
        }

        $this->gdprManagerRepository->update($gdprManager);
        return  $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager
     */
    public function deleteAction(\GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager $gdprManager)
    {
        $this->gdprManagerRepository->remove($gdprManager);
        $this->redirect('list');
    }

    /**
     * action uploadImage
     *
     */
    public function uploadImageAction()
    {
        // dd('uploadImage');
        $rootPageId = (int)($_GET['rootPageId'] ?? 0);

        $type = ($_GET['type'] ?? '');
        if(($rootPageId > 0) && $type != ''){
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_tracking');
            $result = $queryBuilder
                ->select('*')
                ->from('gdpr_tracking')
                ->where(
                    $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($rootPageId, \PDO::PARAM_INT))
                )
                ->executeQuery();

            $tracking = [];
            while ($row = $result->fetch()) {

                if($type == 'matomo' && !empty($row['matomo_code']) ){
                    $tracking[] = [
                        'matomo' => $row['matomo_code'],
                        'heading' => $row['matomo_heading'],
                        'desc' => $row['matomo_desc']
                    ];
                }
                elseif($type == 'gtm' && !empty($row['gtm_code'])){
                    $tracking[] = [
                        'gtm' => $row['gtm_code']
                    ];
                }elseif($type == 'meta' && !empty($row['meta_code'])){
                    $tracking[] = [
                        'meta' => $row['meta_code']
                    ];
                }

            }

            // Return the fetched locations
            return $this->jsonResponse(json_encode(['track' => $tracking]));
        }

        if ($rootPageId > 0 && $type === '') {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_multilocations');
            $result = $queryBuilder
                ->select('*')
                ->from('gdpr_multilocations')
                ->where(
                    $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($rootPageId, \PDO::PARAM_INT))
                )
                ->executeQuery();

            $locations = [];
            while ($row = $result->fetch()) {
                $locations[] = [
                    'title' => $row['name_of_location'],
                    'apiKey' => $row['dashboard_api_key']
                ];
            }

            // Return the fetched locations
            return $this->jsonResponse(json_encode(['locations' => $locations]));
        }


        $json = file_get_contents('php://input');
        $data = json_decode($json, true); // Decode JSON to associative array

        $actionType = $data['actionType'] ?? null;

        if ($actionType === 'addLocation') {
            // Extract the locations data
            $locations = $data['locations'] ?? [];

            // Get an instance of the QueryBuilder
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_multilocations');
            // Assuming all locations have the same rootPid
            $rootPid = isset($locations[0]) ? (int)($locations[0]['rootPageId'] ?? 0) : 0;

            // First, delete existing records for the same rootPageId
            if ($rootPid >= 0) {
                $queryBuilder
                    ->delete('gdpr_multilocations')
                    ->where(
                        $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($rootPid, \PDO::PARAM_INT))
                    )
                    ->executeStatement();
            }

                foreach ($locations as $location) {
                    $nameOfLocation = $location['title'] ?? '';
                    $dashboardApiKey = $location['apiKey'] ?? '';
                    $rootPid = (int)($location['rootPageId'] ?? 0);

                    // Insert the data into the gdpr_multilocations table
                    if($nameOfLocation !== ''){
                        $queryBuilder
                        ->insert('gdpr_multilocations')
                        ->values([
                            'name_of_location' => $nameOfLocation,
                            'dashboard_api_key' => $dashboardApiKey,
                            'root_pid' => $rootPid
                        ])
                        ->executeStatement();
                    }
                }

            return $this->jsonResponse(json_encode(['status' => true, 'message' => 'Changes applied successfully']));
        }

        ////// for tracking plugins like gtm, matomo /////

        if ($actionType === 'trackingAdd') {
            // Extract the locations data
            $locations = $data['locations'] ?? [];


            // Get an instance of the QueryBuilder
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_tracking');
            // Assuming all locations have the same rootPid
            $rootPid = isset($locations[0]) ? (int)($locations[0]['rootPageId'] ?? 0) : 0;
            $rootPageUrl = isset($locations[0]) ? ($locations[0]['baseUrl'] ?? 0) : 0;

            // First, delete existing records for the same rootPageId
            $status = 0;
            if ($rootPid >= 0) {
                $result = $queryBuilder
                    ->select('*')
                    ->from('gdpr_tracking')
                    ->where(
                        $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($rootPid, \PDO::PARAM_INT))
                    )
                    ->executeQuery()->fetchAssociative();

                $status = $result ? 1 : 0 ;
            }

            foreach ($locations as $location) {
                $nameOfLocation = $location['title'] ?? '';
                $type = $location['type'] ?? '';
                $heading = $location['heading'] ?? '';
                $desc = $location['desc'] ?? '';
                $rootPid = (int)($location['rootPageId'] ?? 0);

                // Update or Insert data into the gdpr_multilocations table based on the status
                if ($status === 0 && $type == 'matomo' ) {
                    $queryBuilder
                        ->insert('gdpr_tracking')
                        ->values([
                            'matomo_code' => base64_encode($nameOfLocation),
                            'root_pid' => $rootPid,
                            'base_url' => $rootPageUrl,
                            'matomo_heading' => $heading,
                            'matomo_desc' => $desc,
                        ])
                        ->executeStatement();
                } else if ($status === 1 && $type == 'matomo') {
                    $queryBuilder
                        ->update('gdpr_tracking')
                        ->set('matomo_code', base64_encode($nameOfLocation))
                        ->set('matomo_heading', $heading)
                        ->set('matomo_desc', $desc)
                        ->where(
                            $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($rootPid, \PDO::PARAM_INT))
                        )
                        ->executeStatement();
                }

                if($status === 0 && $type == 'gtm'){
                    $queryBuilder
                        ->insert('gdpr_tracking')
                        ->values([
                            'gtm_code' => $nameOfLocation,
                            'root_pid' => $rootPid,
                            'base_url' => $rootPageUrl,
                        ])
                        ->executeStatement();
                } else if ($status === 1 && $type == 'gtm' ) {
                    $queryBuilder
                        ->update('gdpr_tracking')
                        ->set('gtm_code', $nameOfLocation)
                        ->where(
                            $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($rootPid, \PDO::PARAM_INT))
                        )
                        ->executeStatement();
                }
                if($status === 0 && $type == 'meta'){
                    $queryBuilder
                        ->insert('gdpr_tracking')
                        ->values([
                            'meta_code' => $nameOfLocation,
                            'root_pid' => $rootPid,
                            'base_url' => $rootPageUrl,
                        ])
                        ->executeStatement();
                } else if ($status === 1 && $type == 'meta' ) {
                    $queryBuilder
                        ->update('gdpr_tracking')
                        ->set('meta_code', $nameOfLocation)
                        ->where(
                            $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($rootPid, \PDO::PARAM_INT))
                        )
                        ->executeStatement();
                }
            }


            return $this->jsonResponse(json_encode(['status' => true, 'message' => 'Changes applied successfully']));
        }
        //

        $forCookieWidget = $this->request->getParsedBody()['forCookie'] ?? $this->request->getQueryParams()['forCookie'] ?? null;
        if($forCookieWidget){
            $cookieWidgetImageValue = $this->request->getParsedBody()['cookieWidgetImageValue'] ?? $this->request->getQueryParams()['cookieWidgetImageValue'] ?? null;
            $cookieWidgetPositionValue = $this->request->getParsedBody()['cookieWidgetPositionValue'] ?? $this->request->getQueryParams()['cookieWidgetPositionValue'] ?? null;

            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_cookiewidget');
            $queryBuilder
                ->delete('tx_gdprextensionscomyoutube_domain_model_cookiewidget')
                ->execute();

            $queryBuilder
                ->insert('tx_gdprextensionscomyoutube_domain_model_cookiewidget')
                ->values([
                    'cookie_widget_image' => $cookieWidgetImageValue,
                    'cookie_widget_position' => $cookieWidgetPositionValue,
                ])
                ->execute();

            return $this->jsonResponse(json_encode(['status' => true]));
        }else{

        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

            $twoClickFolder = Environment::getPublicPath().'/fileadmin/user_upload/two_click_solution/';
            if (!is_dir($twoClickFolder)) {
                mkdir($twoClickFolder);
            }
            $basePath = 'fileadmin/user_upload/two_click_solution/';


            $originalFileName = basename($_FILES['image']['name']);
            $filePath = $_FILES['image']['tmp_name'];
            $fileHash = md5_file($filePath);
            $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
            $newFileName = $fileHash . '.' . $fileExtension;

            $targetPath = $twoClickFolder . $newFileName;

            if (move_uploaded_file($filePath, $targetPath)) {
                $siteUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');

                return $this->jsonResponse(json_encode([
                    'url' => $basePath.$newFileName
                ]));
            }
        }
        }

        return $this->jsonResponse(json_encode(['status' => false]));
    }



    /**
     * @param \GdprExtensionsCom\GdprExtensionsComMp\Domain\Repository\GdprManagerRepository $gdprManagerRepository
     */
    public function injectGdprManagerRepository(\GdprExtensionsCom\GdprExtensionsComMp\Domain\Repository\GdprManagerRepository $gdprManagerRepository)
    {
        $this->gdprManagerRepository = $gdprManagerRepository;
    }
}

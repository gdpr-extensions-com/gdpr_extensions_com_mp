<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComMp\Controller;


use GdprExtensionsCom\GdprExtensionsComMp\Utility\Helper;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ResponseInterface;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Laminas\Diactoros\Response\JsonResponse;

/**
 * This file is part of the "gdpr-extensions-com-google_reviewlist" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023
 */

/**
 * gdprgoogle_reviewlistController
 */
class GdprExtensionsComMpController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{


    /**
     * gdprManagerRepository
     *
     * @var \GdprExtensionsCom\GdprExtensionsComMp\Domain\Repository\GdprManagerRepository
     */

    protected $gdprManagerRepository = null;

    /**
     * ContentObject
     *
     * @var ContentObject
     */
    protected $contentObject = null;

    /**
     * array
     */
    protected $reviewArray = [];

    /**
     * Action initialize
     */
    protected function initializeAction()
    {
        $this->contentObject = $this->configurationManager->getContentObject();

        // intialize the content object
    }

    /**
     * @param \GdprExtensionsCom\GdprExtensionsComMp\Domain\Repository\GdprManagerRepository $gdprManagerRepository
     */
    public function injectGdprManagerRepository(\GdprExtensionsCom\GdprExtensionsComMp\Domain\Repository\GdprManagerRepository $gdprManagerRepository)
    {
        $this->gdprManagerRepository = $gdprManagerRepository;
    }

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {

        $showReviewsUrl = $this->uriBuilder->reset()
            ->uriFor('showReviews');
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_tracking');
        $result = $queryBuilder
            ->select('*')
            ->from('gdpr_tracking')
            ->where(
                $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($GLOBALS['TSFE']->site->getRootPageId()), \PDO::PARAM_INT)
            )
            ->execute()->fetchAssociative();
        $this->view->assign('showReviewsUrl', $showReviewsUrl);
        $this->view->assign('data', $this->contentObject->data);
        $this->view->assign('result', $result);
        $this->view->assign('rootPid', $GLOBALS['TSFE']->site->getRootPageId());
        return $this->htmlResponse();
    }

    public function showReviewsAction() {
        $reviewsToFetch = GeneralUtility::_GP('reveiwsToFetch') ?: 10;
        $sort = GeneralUtility::_GP('sort') ;
        $contentElementUid = $this->configurationManager->getContentObject()->data['uid']; // Example to get current content element UID

        $cacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class);
        $cache = $cacheManager->getCache('GdprExtensionsComMp');

        // Adjusted cache identifier to be more specific and include content element UID
        $cacheIdentifier = 'reviewArray_' . $contentElementUid;
        $cacheTag = 'content_element_' . $contentElementUid; // Cache tag based on content element UID

        $reviewArray = $cache->get($cacheIdentifier);

        if (!$reviewArray) {
            $reviewArray = $this->fetchReviews();
            $cache->set($cacheIdentifier, $reviewArray, [$cacheTag], 3600);
        }

        $reviewsSlice = array_slice($reviewArray, 0, (int)$reviewsToFetch);
        if($sort == '1'){
            usort($reviewsSlice, function ($a, $b) {
                return $a['date_sort'] - $b['date_sort'];
            });

        }
        elseif($sort == '2'){
            usort($reviewsSlice, function ($a, $b) {
                return $b['date_sort'] - $a['date_sort'];
            });
        }

        $result = ['fetchedReviews' => $reviewsSlice];

        return $this->jsonResponse(json_encode($result));
    }


    public function fetchReviews()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');
        $gdprSettingGoogleReviewlist = $queryBuilder
            ->select('*')
            ->from('tx_gdprextensionscomyoutube_domain_model_gdprmanager')->where(
                $queryBuilder->expr()->like('extension_title', $queryBuilder->createNamedParameter('%' . (string)'google_reviewlist' . '%'))
            );
        $settings =  $gdprSettingGoogleReviewlist->execute()->fetchAssociative();

        $reviews = [];
        $maxResults = $this->contentObject->data['tx_gdprreviewsclient_slider_max_reviews_list'];
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $showAllReviews = $this->contentObject->data['gdpr_show_all_reviews_list'];
        if ($showAllReviews == 1) {
            $maxResults = 2000;
        }

        // .................................................................

        $selectedLocations = explode(",", $this->contentObject->data['gdpr_business_locations_list']);

        if (!empty($this->contentObject->data['gdpr_business_locations_list'])) {
            $reviewsQB = $connectionPool->getQueryBuilderForTable('tx_gdprclientreviews_domain_model_reviews');
            $locationsreviewsQB = $connectionPool->getQueryBuilderForTable('gdpr_multilocations');
            $locationNamesList = [];
            foreach ($selectedLocations as $uid) {
                $locationResult = $locationsreviewsQB->select('dashboard_api_key')
                    ->from('gdpr_multilocations')
                    ->where(
                        $locationsreviewsQB->expr()
                            ->eq('uid', $uid)
                    )
                    ->executeQuery();
                $locationName = $locationResult->fetchOne();
                $locationNamesList[] = $locationName;
            }
            if ($locationNamesList) {
                $reviews = [];
                foreach ($locationNamesList as $location) {

                    $reviewsResult = $reviewsQB->select('*')
                        ->from('tx_gdprclientreviews_domain_model_reviews')
                        ->where(
                            $reviewsQB->expr()
                                ->eq('dashboard_api_key', $reviewsQB->createNamedParameter($location)),
                        )
                        ->executeQuery();

                    $reviewsData = $reviewsResult->fetchAllAssociative();

                    $reviews = array_merge($reviews, $reviewsData);

                }

            }

        }
        if($this->contentObject->data['gdpr_reviews_sorting_list']){
            usort($reviews, function ($a, $b) {
                return $a['date_sort'] - $b['date_sort'];
            });

        }
        else{
            usort($reviews, function ($a, $b) {
                return $b['date_sort'] - $a['date_sort'];
            });
        }

        $currentCount = sizeof($reviews);
        if ($currentCount < $maxResults) {
            $maxResults = $currentCount;
        }
        $holdReviews = $reviews;
        $filteredReveiws = [];
        for ($i = 0; $i < $maxResults; $i++) {
            dump($i);
            $filteredReveiws[$i] = $holdReviews[$i];
        }
        return $filteredReveiws ;
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

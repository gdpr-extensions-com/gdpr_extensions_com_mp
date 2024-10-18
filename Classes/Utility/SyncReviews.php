<?php

namespace GdprExtensionsCom\GdprExtensionsComMp\Utility;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use Exception;

class SyncReviews
{
    private function stripOriginalText($comment)
    {
        if (preg_match("#^\(Translated by Google\)(.*?)\(Original\)(.*)$#ms", $comment, $matches)) {
            return trim($matches[2]);
        } elseif (preg_match("#^(.*)\(Translated by Google\)(.*)$#ms", $comment, $matches)) {
            return trim($matches[1]);
        } else {
            return $comment;
        }
    }

    public function run(Helper $helper, ConnectionPool $connectionPool, Logger $logManager)
    {
        $multilocationQB = $connectionPool->getQueryBuilderForTable('gdpr_multilocations');

        $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
        $PathUtility = GeneralUtility::makeInstance(PathUtility::class);
        $Environment = GeneralUtility::makeInstance(Environment::class);

        $imageFolderPath = $Environment::getPublicPath().'/fileadmin/gdpr_reviews/';
        if (!is_dir($imageFolderPath)) {
            mkdir($imageFolderPath);
        }
        $imageFolderUrl = GeneralUtility::locationHeaderUrl($PathUtility::getAbsoluteWebPath(Environment::getPublicPath())).'/fileadmin/gdpr_reviews/';

        $starRatings = [
            'ZERO' => 0,
            'ONE' => 1,
            'TWO' => 2,
            'THREE' => 3,
            'FOUR' => 4,
            'FIVE' => 5,
        ];

        $reviewsToolBase = 'https://dashboard.gosign.de/';
        $sysTempQB = $connectionPool->getQueryBuilderForTable('sys_template');


        $hold=0;

        $multilocationQBResult = $multilocationQB
            ->select('*')
            ->from('gdpr_multilocations')
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($multilocationQBResult as $location) {
            try {

                $apiKey = $location['dashboard_api_key'] ?? null;
                $resposeStatus = 1;

                $SiteConfiguration = $sysTempQB->select('constants')
                    ->from('sys_template')
                    ->where(
                        $sysTempQB->expr()->eq('pid', $sysTempQB->createNamedParameter($location['root_pid'])),
                    )
                    ->setMaxResults(1)
                    ->executeQuery()
                    ->fetchAssociative();
                $sysTempQB->resetQueryParts();
                $constantsArray = $this->extractSecretKey($SiteConfiguration['constants']);
                $BaseURL = $constantsArray['plugin.tx_gdprextensionscomgooglemaps_gdprgooglemaps.settings.dashboardBaseUrl'];

                if ($apiKey) {

                    $reviewsToolUrl = (is_null($BaseURL) ? 'https://dashboard.gosign.de/': $BaseURL).'review/api/'.$location['dashboard_api_key'].'/reviews-list.json';

                    $params = [
                        'verify' => false,
                    ];

                    $response = $requestFactory->request($reviewsToolUrl, 'GET', $params);

                    if($response && $response->getStatusCode() == 200) {

                        $jsonResponse = json_decode($response
                            ->getBody()
                            ->getContents());

                        if(!isset($jsonResponse[0]->reviewId))
                        {
                            $resposeStatus = 0;
                        }
                        $reviewsQB = $connectionPool->getQueryBuilderForTable('tx_gdprclientreviews_domain_model_reviews');

                        $result = $reviewsQB->select('*')
                            ->from('tx_gdprclientreviews_domain_model_reviews')
                            ->where(
                                $reviewsQB->expr()
                                    ->eq('dashboard_api_key', $reviewsQB->createNamedParameter($location['dashboard_api_key'])),
                            )
                            ->executeQuery();
                        $reviews = [];
                        while ($ret = $result->fetchAssociative()) {
                            $reviewId = $ret['review_id'];
                            $reviews[$reviewId]['uid'] = $ret['uid'];
                            $reviews[$reviewId]['deleted'] = $ret['deleted'];
                            $reviews[$reviewId]['content_hash'] = $ret['content_hash'];
                        }
                        foreach ($jsonResponse as $item) {
                            try{


                            $item->comment = $this->stripOriginalText($item->comment);
                            $item->replyComment = $this->stripOriginalText($item->replyComment);
                            $item->comment = preg_replace('/[^\p{L}\p{N}\s]/u', '', $item->comment);
                            $item->replyComment = preg_replace('/[^\p{L}\p{N}\s]/u', '', $item->replyComment);
                            $reviewerPhotoUrl = '';

                            if (isset($item->reviewerProfilePhotoUrl) && $item->reviewerProfilePhotoUrl != '') {
                                $reviewerPhoto = file_get_contents($item->reviewerProfilePhotoUrl);
                                file_put_contents($imageFolderPath.md5($item->reviewerProfilePhotoUrl).'.png', $reviewerPhoto);
                                $reviewerPhotoUrl = $imageFolderUrl.md5($item->reviewerProfilePhotoUrl).'.png';
                            }


                            $content_hash = md5(
                                $reviewerPhotoUrl . ' ' .
                                $item->reviewerDisplayName . ' ' .
                                $item->starRating . ' ' .
                                $item->comment . ' ' .
                                $location['root_pid'] . ' ' .
                                $location['dashboard_api_key'] . ' ' .
                                $item->reviewId

                            );
                            if (isset($reviews[$item->reviewId])) {
                                // Review already in database

                                if ($reviews[$item->reviewId]['content_hash'] == $content_hash) {
                                    // Review have not changed
                                } else {
                                    // Update Review
                                    $reviewsQB = $connectionPool->getQueryBuilderForTable('tx_gdprclientreviews_domain_model_reviews');
                                    if($item->replyTime == '')
                                    {
                                        $reviewsQB
                                            ->update('tx_gdprclientreviews_domain_model_reviews')
                                            ->where(
                                                $reviewsQB->expr()->eq('review_id', $reviewsQB->createNamedParameter($item->reviewId)),
                                            )
                                            ->set('reviewer_profile_photo_url', $reviewerPhotoUrl)
                                            ->set('reviewer_display_name', $item->reviewerDisplayName)
                                            ->set('star_rating', $starRatings[$item->starRating])
                                            ->set('comment', $item->comment)
                                            ->set('reply_comment', $item->replyComment)
                                            ->set('source', $item->source)
                                            ->set('locationtitle', $item->locationtitle)
                                            ->set('create_time', date('d. M Y', strtotime($item->updateTime->date)))
                                            ->set('date_sort', strtotime($item->updateTime->date))
                                            ->set('deleted', '0')
                                            ->set('dashboard_api_key', $location['dashboard_api_key'])
                                            ->executeStatement();
                                    }
                                    else{

                                        $reviewsQB
                                            ->update('tx_gdprclientreviews_domain_model_reviews')
                                            ->where(
                                                $reviewsQB->expr()->eq('review_id', $reviewsQB->createNamedParameter($item->reviewId)),
                                            )
                                            ->set('reviewer_profile_photo_url', $reviewerPhotoUrl)
                                            ->set('reviewer_display_name', $item->reviewerDisplayName)
                                            ->set('star_rating', $starRatings[$item->starRating])
                                            ->set('comment', $item->comment)
                                            ->set('reply_comment', $item->replyComment)
                                            ->set('source', $item->source)
                                            ->set('locationtitle', $item->locationtitle)
                                            ->set('create_time', date('d. M Y', strtotime($item->updateTime->date)))
                                            ->set('reply_time', date('d. M Y', strtotime($item->replyTime->date)))
                                            ->set('date_sort', strtotime($item->updateTime->date))
                                            ->set('deleted', '0')
                                            ->set('dashboard_api_key', $location['dashboard_api_key'])
                                            ->executeStatement();

                                    }
                                }

                                unset($reviews[$item->reviewId]);
                                // Remove from stack the items which dont need to be marked as 'deleted'
                            } else {
                                $reviewsQB = $connectionPool->getQueryBuilderForTable('tx_gdprclientreviews_domain_model_reviews');
                                if($item->replyTime == '')
                                {
                                    $reviewsQB
                                        ->insert('tx_gdprclientreviews_domain_model_reviews')
                                        ->values([
                                            'review_id' => $item->reviewId,
                                            'reviewer_profile_photo_url' => $reviewerPhotoUrl,
                                            'reviewer_display_name' => $item->reviewerDisplayName,
                                            'star_rating' => $starRatings[$item->starRating],
                                            'comment' => $item->comment,
                                            'reply_comment' => $item->replyComment,
                                            'source' => $item->source,
                                            'locationtitle' => $item->locationtitle,
                                            'create_time' => date('d. M Y', strtotime($item->createTime->date)),
                                            'date_sort' => strtotime($item->createTime->date),
                                            'content_hash' => $content_hash,
                                            'root_pid' => $location['root_pid'],
                                            'dashboard_api_key' => $location['dashboard_api_key'],
                                            'revidroot' => $location['dashboard_api_key'] . $item->reviewId
                                        ])
                                        ->executeStatement();
                                }
                                else{
                                    $reviewsQB
                                        ->insert('tx_gdprclientreviews_domain_model_reviews')
                                        ->values([
                                            'review_id' => $item->reviewId,
                                            'reviewer_profile_photo_url' => $reviewerPhotoUrl,
                                            'reviewer_display_name' => $item->reviewerDisplayName,
                                            'star_rating' => $starRatings[$item->starRating],
                                            'comment' => $item->comment,
                                            'reply_comment' => $item->replyComment,
                                            'source' => $item->source,
                                            'locationtitle' => $item->locationtitle,
                                            'create_time' => date('d. M Y', strtotime($item->createTime->date)),
                                            'reply_time' => date('d. M Y', strtotime($item->replyTime->date)),
                                            'date_sort' => strtotime($item->createTime->date),
                                            'content_hash' => $content_hash,
                                            'root_pid' => $location['root_pid'],
                                            'dashboard_api_key' => $location['dashboard_api_key'],
                                            'revidroot' => $location['dashboard_api_key'] . $item->reviewId
                                        ])
                                        ->executeStatement();
                                     }
                                }
                            }catch(Exception $e){

                            }
                        }

                        $setDeletedReviews = [];
                        foreach ($reviews as $singleReview) {
                            $setDeletedReviews[] = $singleReview['uid'];
                        }

                        // Set Reviews to deleted if not in sync
                        if($resposeStatus == 1)
                        {
                            $reviewsQB = $connectionPool->getQueryBuilderForTable('tx_gdprclientreviews_domain_model_reviews');
                            $reviewsQB
                                ->update('tx_gdprclientreviews_domain_model_reviews')
                                ->where(
                                    $reviewsQB->expr()
                                        ->in(
                                            'uid',
                                            $reviewsQB->createNamedParameter(
                                                $setDeletedReviews,
                                                Connection::PARAM_STR_ARRAY
                                            )
                                        ),
                                )
                                ->set('deleted', '1')
                                ->executeStatement();
                        }
                    }
                }
            } catch (\Exception $exception) {
                $logManager->error(
                    $exception->getMessage(),
                    [
                        'code' => $exception->getCode(),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'trace' => $exception->getTrace(),
                    ]
                );
            }
        }

    }


    protected function extractSecretKey($constantsString)
    {
        $configLines = explode("\n", $constantsString);
        $configArray = [];

        foreach ($configLines as $line) {
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $configArray[trim($key)] = trim($value);
            }
        }
        return $configArray;
    }
}

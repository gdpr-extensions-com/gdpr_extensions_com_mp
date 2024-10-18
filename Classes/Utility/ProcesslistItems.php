<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComMp\Utility;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use GdprExtensionsCom\GdprExtensionsComMp\Utility\Helper;

class ProcesslistItems
{
    public function __construct()
    {

    }

    public function getLocationsforRoodPid(array &$params)
    {
        $helper = GeneralUtility::makeInstance(Helper::class);

        $rootpid = $helper->getRootPage($params['row']['pid']);
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $reviewsLocationQB = $connectionPool->getQueryBuilderForTable(
            'gdpr_multilocations'
        );

        $locationResult = $reviewsLocationQB->select('*')
            ->from('gdpr_multilocations')
            ->where(
                $reviewsLocationQB->expr()
                    ->eq('root_pid', $reviewsLocationQB->createNamedParameter($rootpid)),
            )
            ->orderBy('name_of_location', 'DESC')

            ->executeQuery();

        while ($Location = $locationResult->fetchAssociative()) {

            if (strlen($Location['name_of_location']) < 1) {
                continue;
            }

            $params['items'][] = [$Location['name_of_location'], $Location['uid']];
       }
        return $params;
    }

    public function getReviewsForRootPid(array &$params)
    {
        $helper = GeneralUtility::makeInstance(Helper::class);
        $result = $this->fetchReviewsforRoot($helper->getRootPage($params['row']['pid']));

        while ($ret = $result->fetchAssociative()) {
            if (strlen($ret['comment']) < 1) {
                continue;
            }

            $params['items'][] = [
                str_repeat('★', $ret['star_rating']) . ' ' . $ret['comment'] . ' (' . $ret['reviewer_display_name'] . ')',
                $ret['uid'],
            ];
        }

        return $params;
    }

    private function fetchReviewsforRoot($rootPid)
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $reviewsQB = $connectionPool->getQueryBuilderForTable('tx_goclientreviews_domain_model_reviews');

        return $reviewsQB->select('*')
            ->from('tx_goclientreviews_domain_model_reviews')
            ->where($reviewsQB->expr() ->eq('root_pid', $reviewsQB->createNamedParameter($rootPid)))
            ->orderBy('star_rating', 'DESC')
            ->executeQuery();
    }
}

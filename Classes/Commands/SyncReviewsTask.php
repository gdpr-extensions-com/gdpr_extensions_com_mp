<?php

namespace GdprExtensionsCom\GdprExtensionsComMp\Commands;

use GdprExtensionsCom\GdprExtensionsComMp\Utility\Helper;
use GdprExtensionsCom\GdprExtensionsComMp\Utility\SyncReviews;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SyncReviewsTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute()
    {

        $businessLogic = GeneralUtility::makeInstance(SyncReviews::class);
        $rFactory = GeneralUtility::makeInstance(RequestFactory::class);
        $helper = new Helper($rFactory);
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        $businessLogic->run($helper, $connectionPool, $logger);
        return true;
    }
}

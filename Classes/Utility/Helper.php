<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComMp\Utility;

use TYPO3\CMS\Core\Utility\RootlineUtility;

class Helper
{

    /**
     * getRootPage.
     *
     * @return (int))
     */
    public function getRootPage($pageUid)
    {
        $page = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(RootlineUtility::class, $pageUid);
        $rootLines = $page->get();

        if (! empty($rootLines)) {
            foreach ($rootLines as $rootLine) {
                if (! empty($rootLine['is_siteroot']) && $rootLine['is_siteroot']) {
                    return $rootLine['uid'];
                }
            }
        }

        return 0;
    }
}

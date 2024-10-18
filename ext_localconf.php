<?php
defined('TYPO3') || die();

(static function() {


    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'GdprExtensionsComMp',
        'gdprmp',
        [
            \GdprExtensionsCom\GdprExtensionsComMp\Controller\GdprExtensionsComMpController::class => 'index , showReviews,ajax'
        ],
        // non-cacheable actions
        [
            \GdprExtensionsCom\GdprExtensionsComMp\Controller\GdprExtensionsComMpController::class => 'showReviews, ajax',
            \GdprExtensionsCom\GdprExtensionsComMp\Controller\GdprManagerController::class => 'create, update, delete'
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT


    );




//    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
//        'mod {
//            wizards.newContentElement.wizardItems.common {
//                elements {
//                    gdprtmp {
//                        iconIdentifier = gdpr_two_x_meta_plugin_gdprgoogle_reviewlist
//                        title = LLL:EXT:gdpr_extensions_com_mt/Resources/Private/Language/locallang_db.xlf:tx_gdpr_two_x_gtm_gdprgoogle_reviewlist.name
//                        description = LLL:EXT:gdpr_extensions_com_mt/Resources/Private/Language/locallang_db.xlf:tx_gdpr_two_x_gtm_gdprgoogle_reviewlist.description
//                        tt_content_defValues {
//                            CType = list
//                            list_type = gdprextensionscommp_gdprmp
//
//                        }
//                    }
//                }
//                show = *
//            }
//       }'
//    );


    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod.wizards.newContentElement.wizardItems {
               gdpr.header = LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdpr_two_x_meta_gdprgoogle_reviewlist.name.tab
        }'
    );


    $registeredTasks = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'];
    $alreadyRegistered = 0;
    foreach($registeredTasks as $registeredTask){

        if(isset($registeredTask['extension']) && strpos($registeredTask['extension'], 'Googlereview') !== false){
            $alreadyRegistered +=1;
        }

    }


    if(!$alreadyRegistered){
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\GdprExtensionsCom\GdprExtensionsComMp\Commands\SyncReviewsTask::class] = [
            'extension' => 'GdprExtensionsComMp',
            'title' => 'Sync gdpr reviews',
            'description' => 'Sync gdpr reviews',
            'additionalFields' => \GdprExtensionsCom\GdprExtensionsComMp\Commands\SyncReviewsTask::class,
        ];
    }


})();
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \GdprExtensionsCom\GdprExtensionsComMp\Hooks\DataHandlerHook::class;
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['GdprExtensionsComMp'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['GdprExtensionsComMp'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\FileBackend::class,
        'groups' => ['all', 'GdprExtensionsComMp'],
        'options' => [
            'defaultLifetime' => 3600, // Cache lifetime in seconds
        ],
    ];
}

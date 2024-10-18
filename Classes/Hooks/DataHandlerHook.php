<?php
namespace GdprExtensionsCom\GdprExtensionsComMp\Hooks;

class DataHandlerHook {
    public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, $dataHandler) {
        // Check if the table is 'tt_content' and if the changes affect your plugin
        if ($table === 'tt_content' && (array_key_exists('gdpr_reviews_sorting_list', $fieldArray) || array_key_exists('gdpr_business_locations_list', $fieldArray) || array_key_exists('gdpr_background_color_list', $fieldArray) || array_key_exists('gdpr_color_of_border_list', $fieldArray) || array_key_exists('gdpr_color_of_text_list', $fieldArray) || array_key_exists('gdpr_total_color_of_text_list', $fieldArray)| array_key_exists('gdpr_alt_text_list', $fieldArray)| array_key_exists('gdpr_show_all_reviews_list', $fieldArray) || array_key_exists('gdpr_same_as_url_list', $fieldArray)  || array_key_exists('tx_gdprreviewsclient_slider_max_reviews_list', $fieldArray))) {
            // Initialize cache manager |
            $cacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class);

            // Determine the UID of the content element (can be different for new records)
            $contentElementUid = $status === 'new' ? $dataHandler->substNEWwithIDs[$id] : $id;

            // Invalidate cache by tag
            $cacheManager->getCache('GdprExtensionsComMp')->flushByTags(['content_element_' . $contentElementUid]);
        }
    }
}

<?php

if ((int)\TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version() >= 12) {
    $allRegisteredModules = $GLOBALS['TBE_MODULES']['web'];
    if (stripos($allRegisteredModules, 'gdprmanager') == false){

        return[
            'gdpr' => [
                'labels' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_mod_web.xlf',
                'iconIdentifier' => 'gdpr_extensions_com_tab',
                'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
            ],
            'mp' => [
                'parent' => 'gdpr',
                'position' => [],
                'access' => 'user,group',
                'iconIdentifier' => 'gdpr_two_x_meta_plugin_gdprgoogle_reviewlist',
                'path' => '/module/mp',
                'labels' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_gdprmanager.xlf',
                'extensionName' => 'GdprExtensionsComMp',
                'controllerActions' => [
                    \GdprExtensionsCom\GdprExtensionsComMp\Controller\GdprManagerController::class => [
                        'list',
                        'index',
                        'show',
                        'new',
                        'create',
                        'edit',
                        'editAdd',
                        'save',
                        'update',
                        'delete',
                        'uploadImage'
                    ],
                ],
            ]
        ];

    }}



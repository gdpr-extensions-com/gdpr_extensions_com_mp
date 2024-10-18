<?php
return [

    'ctrl' => [
        'title' => 'gdpr_tracking',
        'label' => 'name_of_location',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'meta_code','gtm_code','meta_heading','meta_desc',
        'iconfile' => 'EXT:gdpr_extensions_com_mp/Resources/Public/Icons/tx_goapiconnect_domain_model_multi_location.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'meta_code, gtm_code, meta_heading, meta_desc, root_pid,  --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime, sys_language_uid, l10n_parent, l10n_diffsource, hidden'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'gdpr_tracking',
                'foreign_table_where' => 'AND {#gdpr_tracking}.{#pid}=###CURRENT_PID### AND {#gdpr_tracking}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'meta_code' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title_desc',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],

        'gtm_code' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title_desc',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],

        'meta_heading' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title_desc',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],

        'meta_desc' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title_desc',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],

        'base_url' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title_desc',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],
        'root_pid' => [
            'exclude' => true,
            'label' => 'Root Pid',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
    ],
];

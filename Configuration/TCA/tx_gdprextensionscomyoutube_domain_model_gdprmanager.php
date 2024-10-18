<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_GdprTwoXMatomo_domain_model_gdprmanager',
        'label' => 'heading',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'heading,content,button_text,background_image_color,button_color,text_color,button_shape',
        'iconfile' => 'EXT:gdpr_extensions_com_mp/Resources/Public/Icons/tx_GdprTwoXMatomo_domain_model_gdprmanager.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'heading, content, button_text, enable_background_image, background_image, background_image_color, button_color, text_color, button_text_color, button_shape, map_api, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
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
                'foreign_table' => 'tx_gdprextensionscomyoutube_domain_model_gdprmanager',
                'foreign_table_where' => 'AND {#tx_gdprextensionscomyoutube_domain_model_gdprmanager}.{#pid}=###CURRENT_PID### AND {#tx_gdprextensionscomyoutube_domain_model_gdprmanager}.{#sys_language_uid} IN (-1,0)',
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

        'heading' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_GdprTwoXMatomo_domain_model_gdprmanager.heading',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_GdprTwoXMatomo_domain_model_gdprmanager.heading.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],
        'content' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_GdprTwoXMatomo_domain_model_gdprmanager.content',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_GdprTwoXMatomo_domain_model_gdprmanager.content.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],
        'button_text' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_GdprTwoXMatomo_domain_model_gdprmanager.button_text',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_GdprTwoXMatomo_domain_model_gdprmanager.button_text.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],
        'enable_background_image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_GdprTwoXMatomo_domain_model_gdprmanager.enable_background_image',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_GdprTwoXMatomo_domain_model_gdprmanager.enable_background_image.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],

        'background_image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.background_image',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.background_image.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],

        'background_image_color' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.background_image_color',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.background_image_color.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'button_color' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.button_color',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.button_color.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'text_color' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.text_color',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.text_color.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'button_text_color' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.button_text_color',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.button_text_color.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'button_shape' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.button_shape',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.button_shape.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],

        'extension_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title_desc',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],

        'extension_key' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title_desc',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
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

        'locations' => [
            'exclude' => true,
            'label' => 'location',
            'description' => 'Map location',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_gdprextensionscomgooglemap_domain_model_maplocation',
                'foreign_field' => 'map',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],

        ],

        'map_api' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_youtube/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title',
            'description' => 'LLL:EXT:gdpr_extensions_com_youtube/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomyoutube_domain_model_gdprmanager.ext_title_desc',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],


    ],
];

<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgooglemap_domain_model_maplocation',
        'label' => 'title',
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
        'searchFields' => 'title,address',
        'iconfile' => 'EXT:gdpr_extensions_com_mp/Resources/Public/Icons/tx_gdprextensionscomgooglemap_domain_model_maplocation.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'title, address, lat, lon, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
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
                'foreign_table' => 'tx_gdprextensionscomgooglemap_domain_model_maplocation',
                'foreign_table_where' => 'AND {#tx_gdprextensionscomgooglemap_domain_model_maplocation}.{#pid}=###CURRENT_PID### AND {#tx_gdprextensionscomgooglemap_domain_model_maplocation}.{#sys_language_uid} IN (-1,0)',
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

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgooglemap_domain_model_maplocation.title',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgooglemap_domain_model_maplocation.title.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'address' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgooglemap_domain_model_maplocation.address',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgooglemap_domain_model_maplocation.address.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'lat' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgooglemap_domain_model_maplocation.lat',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgooglemap_domain_model_maplocation.lat.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'lon' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgooglemap_domain_model_maplocation.lon',
            'description' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgooglemap_domain_model_maplocation.lon.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],

        'map' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];

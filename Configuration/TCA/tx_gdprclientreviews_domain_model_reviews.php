<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprclientreviews_domain_model_reviews',
        'label' => 'review_id',
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
        'searchFields' => 'review_id,reviewer_profile_photo_url,reviewer_display_name,comment,content_hash',
        'iconfile' => 'EXT:gdpr_extensions_com_mp/Resources/Public/Icons/tx_gdprclientreviews_domain_model_reviews.gif',
    ],
    'types' => [
        '1' => [
            'showitem' => 'review_id, reviewer_profile_photo_url, reviewer_display_name, star_rating, comment, content_hash, root_pid, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime',
        ],
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
                'items' => [['', 0]],
                'foreign_table' => 'tx_gdprclientreviews_domain_model_reviews',
                'foreign_table_where' => 'AND {#tx_gdprclientreviews_domain_model_reviews}.{#pid}=###CURRENT_PID### AND {#tx_gdprclientreviews_domain_model_reviews}.{#sys_language_uid} IN (-1,0)',
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
                        'invertStateDisplay' => true,
                    ],
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
                    'allowLanguageSynchronization' => true,
                ],
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
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],

        'review_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprclientreviews_domain_model_reviews.review_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => '',
            ],
        ],
        'reviewer_profile_photo_url' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprclientreviews_domain_model_reviews.reviewer_profile_photo_url',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => '',
            ],
        ],
        'reviewer_display_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprclientreviews_domain_model_reviews.reviewer_display_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => '',
            ],
        ],
        'star_rating' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprclientreviews_domain_model_reviews.star_rating',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'comment' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprclientreviews_domain_model_reviews.comment',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => '',
            ],
        ],
        'content_hash' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprclientreviews_domain_model_reviews.content_hash',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => '',
            ],
        ],
        'root_pid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_mp/Resources/Private/Language/locallang_db.xlf:tx_gdprclientreviews_domain_model_reviews.root_pid',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'dashboard_api_key' => [
            'exclude' => true,
            'label' => 'Dashboard API Key',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],

    ],
];

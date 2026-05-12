<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title'          => 'Dentist',
        'label'          => 'name',
        'sortby'         => 'sorting',
        'tstamp'         => 'tstamp',
        'crdate'         => 'crdate',
        'delete'         => 'deleted',
        'enablecolumns'  => ['disabled' => 'hidden'],
        'searchFields'   => 'name,specialization,address,district,email,phone',
        'iconfile'       => 'EXT:dentist_directory/Resources/Public/Icons/dentist.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    name, slug, specialization, categories,
                    listing_tier, is_featured, is_claimed, status, moderator_note,
                --div--;Location,
                    address, district, city, latitude, longitude,
                --div--;Contact,
                    phone, email, website,
                --div--;Content,
                    description, working_hours, image,
            ',
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config'  => ['type' => 'check', 'renderType' => 'checkboxToggle', 'items' => [['label' => '', 'invertStateDisplay' => true]]],
        ],
        'name' => [
            'label'  => 'Name / Clinic',
            'config' => ['type' => 'input', 'size' => 50, 'max' => 255, 'eval' => 'trim', 'required' => true],
        ],
        'slug' => [
            'label'  => 'URL Slug',
            'config' => [
                'type'          => 'slug',
                'size'          => 50,
                'generatorOptions' => ['fields' => ['name'], 'fieldSeparator' => '-', 'prefixParentPageSlug' => false],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
            ],
        ],
        'specialization' => [
            'label'  => 'Main Specialization',
            'config' => ['type' => 'input', 'size' => 50, 'max' => 255, 'eval' => 'trim'],
        ],
        'address' => [
            'label'  => 'Address',
            'config' => ['type' => 'input', 'size' => 80, 'max' => 500, 'eval' => 'trim'],
        ],
        'district' => [
            'label'  => 'District',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '-- any --', 'value' => ''],
                    ['label' => 'Sector 1', 'value' => 'sector-1'],
                    ['label' => 'Sector 2', 'value' => 'sector-2'],
                    ['label' => 'Sector 3', 'value' => 'sector-3'],
                    ['label' => 'Sector 4', 'value' => 'sector-4'],
                    ['label' => 'Sector 5', 'value' => 'sector-5'],
                    ['label' => 'Sector 6', 'value' => 'sector-6'],
                ],
            ],
        ],
        'city' => [
            'label'  => 'City',
            'config' => ['type' => 'input', 'size' => 40, 'max' => 100, 'default' => 'Bucharest'],
        ],
        'phone' => [
            'label'  => 'Phone',
            'config' => ['type' => 'input', 'size' => 30, 'max' => 50, 'eval' => 'trim'],
        ],
        'email' => [
            'label'  => 'E-mail',
            'config' => ['type' => 'email', 'size' => 40],
        ],
        'website' => [
            'label'  => 'Website',
            'config' => ['type' => 'link', 'size' => 50],
        ],
        'description' => [
            'label'  => 'Description',
            'config' => ['type' => 'text', 'enableRichtext' => true, 'rows' => 8],
        ],
        'working_hours' => [
            'label'  => 'Working Hours',
            'config' => ['type' => 'text', 'rows' => 4],
        ],
        'image' => [
            'label'  => 'Photo',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                ['appearance' => ['createNewRelationLinkTitle' => 'Add image'], 'maxitems' => 1],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'latitude' => [
            'label'  => 'Latitude',
            'config' => ['type' => 'number', 'format' => 'decimal', 'size' => 20],
        ],
        'longitude' => [
            'label'  => 'Longitude',
            'config' => ['type' => 'number', 'format' => 'decimal', 'size' => 20],
        ],
        'categories' => [
            'label'  => 'Categories',
            'config' => [
                'type'          => 'select',
                'renderType'    => 'selectCheckBox',
                'foreign_table' => 'tx_dentistdirectory_domain_model_category',
                'MM'            => 'tx_dentistdirectory_dentist_category_mm',
                'size'          => 5,
                'minitems'      => 0,
                'maxitems'      => 99,
            ],
        ],
        'is_featured' => [
            'label'  => 'Featured (premium spotlight)',
            'config' => ['type' => 'check', 'renderType' => 'checkboxToggle'],
        ],
        'is_claimed' => [
            'label'  => 'Claimed by owner',
            'config' => ['type' => 'check', 'renderType' => 'checkboxToggle'],
        ],
        'listing_tier' => [
            'label'  => 'Listing Tier',
            'config' => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => [
                    ['label' => 'Free',    'value' => 'free'],
                    ['label' => 'Basic',   'value' => 'basic'],
                    ['label' => 'Premium', 'value' => 'premium'],
                ],
            ],
        ],
        'status' => [
            'label'  => 'Moderation Status',
            'config' => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => [
                    ['label' => 'Pending',  'value' => 'pending'],
                    ['label' => 'Approved', 'value' => 'approved'],
                    ['label' => 'Rejected', 'value' => 'rejected'],
                ],
            ],
        ],
        'moderator_note' => [
            'label'  => 'Moderator Note',
            'config' => ['type' => 'text', 'rows' => 3],
        ],
    ],
];

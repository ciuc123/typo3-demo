<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title'   => 'Dentist Category',
        'label'   => 'name',
        'delete'  => 'deleted',
        'enablecolumns' => ['disabled' => 'hidden'],
        'iconfile' => 'EXT:dentist_directory/Resources/Public/Icons/category.svg',
    ],
    'types' => [
        '1' => ['showitem' => 'name, slug, icon'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config'  => ['type' => 'check', 'renderType' => 'checkboxToggle', 'items' => [['label' => '', 'invertStateDisplay' => true]]],
        ],
        'name' => [
            'label'  => 'Name',
            'config' => ['type' => 'input', 'size' => 40, 'max' => 255, 'eval' => 'trim', 'required' => true],
        ],
        'slug' => [
            'label'  => 'URL Slug',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => ['fields' => ['name']],
                'fallbackCharacter' => '-',
                'eval' => 'unique',
            ],
        ],
        'icon' => [
            'label'  => 'Icon class (e.g. fa-tooth)',
            'config' => ['type' => 'input', 'size' => 30],
        ],
    ],
];

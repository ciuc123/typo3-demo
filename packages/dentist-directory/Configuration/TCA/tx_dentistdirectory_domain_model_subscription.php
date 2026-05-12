<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title'   => 'Subscription',
        'label'   => 'plan',
        'tstamp'  => 'tstamp',
        'crdate'  => 'crdate',
        'delete'  => 'deleted',
        'iconfile' => 'EXT:dentist_directory/Resources/Public/Icons/subscription.svg',
    ],
    'types' => [
        '1' => ['showitem' => 'dentist, plan, price_eur, starts_at, ends_at, payment_ref, status'],
    ],
    'columns' => [
        'dentist' => [
            'label'  => 'Dentist',
            'config' => [
                'type'          => 'select',
                'renderType'    => 'selectSingle',
                'foreign_table' => 'tx_dentistdirectory_domain_model_dentist',
                'minitems'      => 1,
                'maxitems'      => 1,
            ],
        ],
        'plan' => [
            'label'  => 'Plan',
            'config' => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => [
                    ['label' => 'Basic (€29/mo)',   'value' => 'basic'],
                    ['label' => 'Premium (€79/mo)', 'value' => 'premium'],
                ],
            ],
        ],
        'price_eur' => [
            'label'  => 'Price (EUR)',
            'config' => ['type' => 'number', 'format' => 'decimal', 'size' => 10],
        ],
        'starts_at' => [
            'label'  => 'Starts At',
            'config' => ['type' => 'datetime'],
        ],
        'ends_at' => [
            'label'  => 'Ends At',
            'config' => ['type' => 'datetime'],
        ],
        'payment_ref' => [
            'label'  => 'Payment Reference',
            'config' => ['type' => 'input', 'size' => 60],
        ],
        'status' => [
            'label'  => 'Status',
            'config' => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => [
                    ['label' => 'Active',    'value' => 'active'],
                    ['label' => 'Cancelled', 'value' => 'cancelled'],
                    ['label' => 'Expired',   'value' => 'expired'],
                ],
            ],
        ],
    ],
];

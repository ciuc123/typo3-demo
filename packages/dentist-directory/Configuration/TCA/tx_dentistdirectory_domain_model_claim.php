<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title'   => 'Business Claim',
        'label'   => 'claimant_name',
        'tstamp'  => 'tstamp',
        'crdate'  => 'crdate',
        'delete'  => 'deleted',
        'iconfile' => 'EXT:dentist_directory/Resources/Public/Icons/claim.svg',
    ],
    'types' => [
        '1' => ['showitem' => 'dentist, claimant_name, claimant_email, claimant_phone, message, status, reviewed_at, token'],
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
        'claimant_name' => [
            'label'  => 'Claimant Name',
            'config' => ['type' => 'input', 'size' => 40, 'max' => 255, 'eval' => 'trim'],
        ],
        'claimant_email' => [
            'label'  => 'Claimant E-mail',
            'config' => ['type' => 'email', 'size' => 40],
        ],
        'claimant_phone' => [
            'label'  => 'Claimant Phone',
            'config' => ['type' => 'input', 'size' => 20, 'max' => 50],
        ],
        'message' => [
            'label'  => 'Message',
            'config' => ['type' => 'text', 'rows' => 5],
        ],
        'token' => [
            'label'  => 'Verification Token',
            'config' => ['type' => 'input', 'size' => 64, 'readOnly' => true],
        ],
        'status' => [
            'label'  => 'Status',
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
        'reviewed_at' => [
            'label'  => 'Reviewed At',
            'config' => ['type' => 'datetime', 'readOnly' => true],
        ],
    ],
];

<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title'   => 'Lead (Contact Enquiry)',
        'label'   => 'sender_name',
        'tstamp'  => 'tstamp',
        'crdate'  => 'crdate',
        'delete'  => 'deleted',
        'iconfile' => 'EXT:dentist_directory/Resources/Public/Icons/lead.svg',
    ],
    'types' => [
        '1' => ['showitem' => 'dentist, sender_name, sender_email, sender_phone, message, is_read'],
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
        'sender_name' => [
            'label'  => 'Sender Name',
            'config' => ['type' => 'input', 'size' => 40, 'max' => 255, 'eval' => 'trim'],
        ],
        'sender_email' => [
            'label'  => 'Sender E-mail',
            'config' => ['type' => 'email', 'size' => 40],
        ],
        'sender_phone' => [
            'label'  => 'Sender Phone',
            'config' => ['type' => 'input', 'size' => 20, 'max' => 50],
        ],
        'message' => [
            'label'  => 'Message',
            'config' => ['type' => 'text', 'rows' => 5],
        ],
        'is_read' => [
            'label'  => 'Read',
            'config' => ['type' => 'check', 'renderType' => 'checkboxToggle'],
        ],
    ],
];

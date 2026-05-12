<?php

/**
 * TYPO3 system settings — committed placeholder for development.
 * Production values are injected via environment variables or a non-committed settings.php.
 */
return [
    'BE' => [
        'debug' => false,
        'sessionTimeout' => 3600,
    ],
    'DB' => [
        'Connections' => [
            'Default' => [
                'charset'  => 'utf8mb4',
                'dbname'   => getenv('TYPO3_DB_DBNAME')   ?: 'typo3_dentist',
                'driver'   => 'mysqli',
                'host'     => getenv('TYPO3_DB_HOST')     ?: '127.0.0.1',
                'password' => getenv('TYPO3_DB_PASSWORD') ?: '',
                'port'     => (int)(getenv('TYPO3_DB_PORT') ?: 3306),
                'user'     => getenv('TYPO3_DB_USER')     ?: 'typo3',
            ],
        ],
    ],
    'FE' => [
        'debug' => false,
    ],
    'GFX' => [
        'processor'       => 'GraphicsMagick',
        'processor_path'  => '/usr/bin/gm',
    ],
    'MAIL' => [
        'transport'      => getenv('TYPO3_MAIL_TRANSPORT') ?: 'smtp',
        'transport_smtp_server' => getenv('TYPO3_MAIL_HOST') ?: 'localhost',
        'transport_smtp_port'   => (int)(getenv('TYPO3_MAIL_PORT') ?: 25),
    ],
    'SYS' => [
        'encryptionKey' => getenv('TYPO3_ENCRYPTION_KEY') ?: '',
        'trustedHostsPattern' => '.*',
    ],
];

<?php

/**
 * TYPO3 system settings — Replit development configuration.
 * Production values are injected via environment variables.
 */
return [
    'BE' => [
        'debug' => true,
        'sessionTimeout' => 3600,
        'installToolPassword' => '$2y$12$jZjNOuqUfmXO2P/gN4ftrOAxyb0c27mClP0gFdUJSCW.3zGaZIUG2',
    ],
    'DB' => [
        'Connections' => [
            'Default' => [
                'charset'     => 'utf8mb4',
                'dbname'      => getenv('TYPO3_DB_DBNAME')   ?: 'typo3_dentist',
                'driver'      => 'mysqli',
                'host'        => getenv('TYPO3_DB_HOST')     ?: 'localhost',
                'password'    => getenv('TYPO3_DB_PASSWORD') ?: '',
                'port'        => (int)(getenv('TYPO3_DB_PORT') ?: 3306),
                'user'        => getenv('TYPO3_DB_USER')     ?: 'typo3',
                'unix_socket' => getenv('TYPO3_DB_SOCKET')  ?: '/tmp/mysql.sock',
            ],
        ],
    ],
    'FE' => [
        'debug' => true,
    ],
    'GFX' => [
        'processor'      => 'GraphicsMagick',
        'processor_path' => '/usr/bin/gm',
    ],
    'MAIL' => [
        'transport'             => getenv('TYPO3_MAIL_TRANSPORT') ?: 'sendmail',
        'transport_smtp_server' => getenv('TYPO3_MAIL_HOST')      ?: 'localhost',
        'transport_smtp_port'   => (int)(getenv('TYPO3_MAIL_PORT') ?: 25),
    ],
    'SYS' => [
        'encryptionKey'       => getenv('TYPO3_ENCRYPTION_KEY') ?: 'a1b2c3d4e5f6a1b2c3d4e5f6a1b2c3d4e5f6a1b2c3d4e5f6a1b2c3d4e5f6a1b2',
        'trustedHostsPattern' => '.*',
    ],
];

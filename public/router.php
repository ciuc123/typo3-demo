<?php

/**
 * PHP built-in server router for TYPO3 12.
 * Routes all requests through the TYPO3 entry points.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly if they exist
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Route backend requests
if (str_starts_with($uri, '/typo3/install')) {
    require __DIR__ . '/typo3/install.php';
    return;
}

if (str_starts_with($uri, '/typo3')) {
    require __DIR__ . '/typo3/index.php';
    return;
}

// All other requests go to the TYPO3 frontend
require __DIR__ . '/index.php';

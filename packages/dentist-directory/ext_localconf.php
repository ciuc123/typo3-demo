<?php

declare(strict_types=1);

defined('TYPO3') or die();

// ─── Plugin: Dentist Listing ──────────────────────────────────────────────────
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'DentistDirectory',
    'Listing',
    [
        \Ciuc123\DentistDirectory\Controller\ListingController::class => 'index, show',
    ],
    // non-cacheable actions
    [
        \Ciuc123\DentistDirectory\Controller\ListingController::class => 'index',
    ]
);

// ─── Plugin: Claim Your Business ─────────────────────────────────────────────
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'DentistDirectory',
    'Claim',
    [
        \Ciuc123\DentistDirectory\Controller\ClaimController::class => 'new, create, confirm, success',
    ],
    [
        \Ciuc123\DentistDirectory\Controller\ClaimController::class => 'new, create, confirm, success',
    ]
);

// ─── Plugin: Lead Form ────────────────────────────────────────────────────────
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'DentistDirectory',
    'LeadForm',
    [
        \Ciuc123\DentistDirectory\Controller\LeadFormController::class => 'show, submit, thanks',
    ],
    [
        \Ciuc123\DentistDirectory\Controller\LeadFormController::class => 'show, submit, thanks',
    ]
);

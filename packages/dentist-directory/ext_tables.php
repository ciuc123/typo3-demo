<?php

declare(strict_types=1);

defined('TYPO3') or die();

// ─── Register plugins in CMS page-content element ────────────────────────────
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'DentistDirectory',
    'Listing',
    'Dentist Directory — Listing'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'DentistDirectory',
    'Claim',
    'Dentist Directory — Claim Your Business'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'DentistDirectory',
    'LeadForm',
    'Dentist Directory — Lead Form'
);

// ─── TCA include ─────────────────────────────────────────────────────────────
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'dentist_directory',
    'Configuration/TypoScript',
    'Dentist Directory'
);

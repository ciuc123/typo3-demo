<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for Lead records.
 */
class LeadRepository extends Repository
{
    protected $defaultOrderings = ['crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING];
}

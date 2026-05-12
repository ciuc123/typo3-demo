<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for Category records.
 */
class CategoryRepository extends Repository
{
    protected $defaultOrderings = ['name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING];
}

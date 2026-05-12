<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Domain\Repository;

use Ciuc123\DentistDirectory\Domain\Model\Claim;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for Claim records.
 */
class ClaimRepository extends Repository
{
    /**
     * Find a claim by its verification token.
     */
    public function findByToken(string $token): ?Claim
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->matching($query->equals('token', $token));
        $query->setLimit(1);

        /** @var Claim|null $result */
        $result = $query->execute()->getFirst();

        return $result;
    }

    /**
     * Return all pending claims for back-end moderation.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface<Claim>
     */
    public function findPending()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->matching($query->equals('status', Claim::STATUS_PENDING));

        return $query->execute();
    }
}

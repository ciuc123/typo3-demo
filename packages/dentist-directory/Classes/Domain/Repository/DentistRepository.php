<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Domain\Repository;

use Ciuc123\DentistDirectory\Domain\Model\Dentist;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for Dentist records.
 *
 * Provides finder methods used by the listing and detail controllers.
 */
class DentistRepository extends Repository
{
    protected $defaultOrderings = [
        'is_featured' => QueryInterface::ORDER_DESCENDING,
        'name'        => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * Find all approved dentists, optionally filtered by district and/or category slug.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface<Dentist>
     */
    public function findApproved(string $district = '', int $categoryUid = 0, string $search = '')
    {
        $query = $this->createQuery();
        $constraints = [
            $query->equals('status', Dentist::STATUS_APPROVED),
        ];

        if ($district !== '') {
            $constraints[] = $query->equals('district', $district);
        }

        if ($categoryUid > 0) {
            $constraints[] = $query->contains('categories', $categoryUid);
        }

        if ($search !== '') {
            $constraints[] = $query->logicalOr(
                $query->like('name', '%' . $search . '%'),
                $query->like('specialization', '%' . $search . '%'),
                $query->like('address', '%' . $search . '%')
            );
        }

        $query->matching($query->logicalAnd(...$constraints));

        return $query->execute();
    }

    /**
     * Find featured (premium) approved dentists for the homepage spotlight.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface<Dentist>
     */
    public function findFeatured(int $limit = 6)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('status', Dentist::STATUS_APPROVED),
                $query->equals('isFeatured', true)
            )
        );
        $query->setLimit($limit);

        return $query->execute();
    }

    /**
     * Find a single approved dentist by slug.
     */
    public function findBySlug(string $slug): ?Dentist
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('slug', $slug),
                $query->equals('status', Dentist::STATUS_APPROVED)
            )
        );
        $query->setLimit(1);

        /** @var Dentist|null $result */
        $result = $query->execute()->getFirst();

        return $result;
    }

    /**
     * Find all records pending moderation (for admin back-end).
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface<Dentist>
     */
    public function findPendingModeration()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->matching($query->equals('status', Dentist::STATUS_PENDING));

        return $query->execute();
    }
}

<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Tests\Unit\Domain\Model;

use Ciuc123\DentistDirectory\Domain\Model\Dentist;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Dentist domain model.
 */
class DentistTest extends TestCase
{
    private Dentist $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new Dentist();
    }

    /** @test */
    public function nameIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getName());
    }

    /** @test */
    public function setNameStoresValue(): void
    {
        $this->subject->setName('Dr. Ion Popescu');
        self::assertSame('Dr. Ion Popescu', $this->subject->getName());
    }

    /** @test */
    public function cityDefaultsToBucharest(): void
    {
        self::assertSame('Bucharest', $this->subject->getCity());
    }

    /** @test */
    public function defaultListingTierIsFree(): void
    {
        self::assertSame(Dentist::TIER_FREE, $this->subject->getListingTier());
    }

    /** @test */
    public function isPremiumReturnsFalseForFreeTier(): void
    {
        self::assertFalse($this->subject->isPremium());
    }

    /** @test */
    public function isPremiumReturnsTrueForPremiumTier(): void
    {
        $this->subject->setListingTier(Dentist::TIER_PREMIUM);
        self::assertTrue($this->subject->isPremium());
    }

    /** @test */
    public function isBasicReturnsTrueForBasicTier(): void
    {
        $this->subject->setListingTier(Dentist::TIER_BASIC);
        self::assertTrue($this->subject->isBasic());
    }

    /** @test */
    public function defaultStatusIsPending(): void
    {
        self::assertSame(Dentist::STATUS_PENDING, $this->subject->getStatus());
    }

    /** @test */
    public function isApprovedReturnsFalseByDefault(): void
    {
        self::assertFalse($this->subject->isApproved());
    }

    /** @test */
    public function isApprovedReturnsTrueAfterApproval(): void
    {
        $this->subject->setStatus(Dentist::STATUS_APPROVED);
        self::assertTrue($this->subject->isApproved());
    }

    /** @test */
    public function isFeaturedDefaultsFalse(): void
    {
        self::assertFalse($this->subject->getIsFeatured());
    }

    /** @test */
    public function isClaimedDefaultsFalse(): void
    {
        self::assertFalse($this->subject->getIsClaimed());
    }

    /** @test */
    public function latitudeAndLongitudeDefaultToNull(): void
    {
        self::assertNull($this->subject->getLatitude());
        self::assertNull($this->subject->getLongitude());
    }

    /** @test */
    public function setLatitudeAndLongitudeStoreValues(): void
    {
        $this->subject->setLatitude(44.4268);
        $this->subject->setLongitude(26.1025);

        self::assertEqualsWithDelta(44.4268, $this->subject->getLatitude(), 0.0001);
        self::assertEqualsWithDelta(26.1025, $this->subject->getLongitude(), 0.0001);
    }

    /** @test */
    public function categoriesCollectionIsInitialisedEmpty(): void
    {
        self::assertCount(0, $this->subject->getCategories());
    }
}

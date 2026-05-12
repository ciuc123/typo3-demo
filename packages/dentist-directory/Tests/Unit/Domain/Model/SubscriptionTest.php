<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Tests\Unit\Domain\Model;

use Ciuc123\DentistDirectory\Domain\Model\Subscription;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Subscription domain model.
 */
class SubscriptionTest extends TestCase
{
    private Subscription $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new Subscription();
    }

    /** @test */
    public function defaultPlanIsBasic(): void
    {
        self::assertSame(Subscription::PLAN_BASIC, $this->subject->getPlan());
    }

    /** @test */
    public function defaultStatusIsActive(): void
    {
        self::assertSame(Subscription::STATUS_ACTIVE, $this->subject->getStatus());
    }

    /** @test */
    public function isActiveReturnsTrueByDefault(): void
    {
        self::assertTrue($this->subject->isActive());
    }

    /** @test */
    public function isExpiredReturnsFalseWhenEndsAtIsZero(): void
    {
        self::assertFalse($this->subject->isExpired());
    }

    /** @test */
    public function isExpiredReturnsTrueForPastTimestamp(): void
    {
        $this->subject->setEndsAt(time() - 86400); // yesterday
        self::assertTrue($this->subject->isExpired());
    }

    /** @test */
    public function isExpiredReturnsFalseForFutureTimestamp(): void
    {
        $this->subject->setEndsAt(time() + 86400); // tomorrow
        self::assertFalse($this->subject->isExpired());
    }

    /** @test */
    public function priceEurDefaultsToZero(): void
    {
        self::assertSame(0.0, $this->subject->getPriceEur());
    }

    /** @test */
    public function setPriceEurStoresValue(): void
    {
        $this->subject->setPriceEur(79.00);
        self::assertSame(79.00, $this->subject->getPriceEur());
    }
}

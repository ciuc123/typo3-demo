<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Tests\Unit\Domain\Model;

use Ciuc123\DentistDirectory\Domain\Model\Claim;
use Ciuc123\DentistDirectory\Domain\Model\Dentist;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Claim domain model.
 */
class ClaimTest extends TestCase
{
    private Claim $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new Claim();
    }

    /** @test */
    public function defaultStatusIsPending(): void
    {
        self::assertSame(Claim::STATUS_PENDING, $this->subject->getStatus());
    }

    /** @test */
    public function isPendingReturnsTrueByDefault(): void
    {
        self::assertTrue($this->subject->isPending());
    }

    /** @test */
    public function isApprovedReturnsFalseByDefault(): void
    {
        self::assertFalse($this->subject->isApproved());
    }

    /** @test */
    public function isApprovedReturnsTrueWhenApproved(): void
    {
        $this->subject->setStatus(Claim::STATUS_APPROVED);
        self::assertTrue($this->subject->isApproved());
    }

    /** @test */
    public function setTokenStoresValue(): void
    {
        $this->subject->setToken('abc123');
        self::assertSame('abc123', $this->subject->getToken());
    }

    /** @test */
    public function setDentistStoresDentist(): void
    {
        $dentist = new Dentist();
        $dentist->setName('Dr. Maria Ionescu');
        $this->subject->setDentist($dentist);

        self::assertSame($dentist, $this->subject->getDentist());
    }
}

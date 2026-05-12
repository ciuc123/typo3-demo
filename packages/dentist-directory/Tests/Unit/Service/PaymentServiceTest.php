<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Tests\Unit\Service;

use Ciuc123\DentistDirectory\Domain\Model\Dentist;
use Ciuc123\DentistDirectory\Domain\Model\Subscription;
use Ciuc123\DentistDirectory\Domain\Repository\DentistRepository;
use Ciuc123\DentistDirectory\Service\PaymentService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Unit tests for PaymentService.
 */
class PaymentServiceTest extends TestCase
{
    private DentistRepository&MockObject  $dentistRepository;
    private PersistenceManager&MockObject $persistenceManager;
    private PaymentService                $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dentistRepository  = $this->createMock(DentistRepository::class);
        $this->persistenceManager = $this->createMock(PersistenceManager::class);

        $this->subject = new PaymentService(
            $this->dentistRepository,
            $this->persistenceManager,
        );
    }

    /** @test */
    public function activateSubscriptionReturnsSubscription(): void
    {
        $dentist = new Dentist();
        $this->dentistRepository->expects(self::once())->method('update')->with($dentist);
        $this->persistenceManager->expects(self::once())->method('persistAll');

        $result = $this->subject->activateSubscription($dentist, Subscription::PLAN_BASIC, 'PAY-001');

        self::assertInstanceOf(Subscription::class, $result);
        self::assertSame(Subscription::PLAN_BASIC, $result->getPlan());
        self::assertSame(PaymentService::PRICES[Subscription::PLAN_BASIC], $result->getPriceEur());
        self::assertSame(Dentist::TIER_BASIC, $dentist->getListingTier());
    }

    /** @test */
    public function premiumActivationSetsFeaturedFlag(): void
    {
        $dentist = new Dentist();
        $this->dentistRepository->method('update');
        $this->persistenceManager->method('persistAll');

        $this->subject->activateSubscription($dentist, Subscription::PLAN_PREMIUM, 'PAY-002');

        self::assertTrue($dentist->getIsFeatured());
        self::assertSame(Dentist::TIER_PREMIUM, $dentist->getListingTier());
    }

    /** @test */
    public function activateSubscriptionThrowsOnUnknownPlan(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->subject->activateSubscription(new Dentist(), 'gold', 'PAY-003');
    }

    /** @test */
    public function cancelSubscriptionDowngradesToFree(): void
    {
        $dentist = new Dentist();
        $dentist->setListingTier(Dentist::TIER_PREMIUM);
        $dentist->setIsFeatured(true);

        $subscription = new Subscription();
        $subscription->setDentist($dentist);
        $subscription->setStatus(Subscription::STATUS_ACTIVE);

        $this->dentistRepository->expects(self::once())->method('update')->with($dentist);
        $this->persistenceManager->expects(self::once())->method('persistAll');

        $this->subject->cancelSubscription($subscription);

        self::assertSame(Subscription::STATUS_CANCELLED, $subscription->getStatus());
        self::assertSame(Dentist::TIER_FREE, $dentist->getListingTier());
        self::assertFalse($dentist->getIsFeatured());
    }
}

<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Service;

use Ciuc123\DentistDirectory\Domain\Model\Dentist;
use Ciuc123\DentistDirectory\Domain\Model\Subscription;
use Ciuc123\DentistDirectory\Domain\Repository\DentistRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Handles subscription plan changes and updates the dentist's listing tier.
 *
 * In a production deployment this service would integrate with a payment
 * gateway (e.g. Stripe).  For the MVP it only updates the local record.
 */
class PaymentService
{
    /** Monthly prices in EUR */
    public const PRICES = [
        Subscription::PLAN_BASIC   => 29.00,
        Subscription::PLAN_PREMIUM => 79.00,
    ];

    public function __construct(
        private readonly DentistRepository  $dentistRepository,
        private readonly PersistenceManager $persistenceManager,
    ) {}

    /**
     * Activate a subscription and update the dentist's listing tier.
     *
     * @param string $paymentRef External payment-gateway reference
     */
    public function activateSubscription(
        Dentist      $dentist,
        string       $plan,
        string       $paymentRef,
        int          $durationDays = 30
    ): Subscription {
        if (!array_key_exists($plan, self::PRICES)) {
            throw new \InvalidArgumentException(
                sprintf('Unknown subscription plan "%s".', $plan),
                1_715_000_000
            );
        }

        $subscription = new Subscription();
        $subscription->setDentist($dentist);
        $subscription->setPlan($plan);
        $subscription->setPriceEur(self::PRICES[$plan]);
        $subscription->setStartsAt(time());
        $subscription->setEndsAt(time() + ($durationDays * 86400));
        $subscription->setPaymentRef($paymentRef);
        $subscription->setStatus(Subscription::STATUS_ACTIVE);

        // Reflect paid tier on the dentist record
        $dentist->setListingTier($plan);
        if ($plan === Subscription::PLAN_PREMIUM) {
            $dentist->setIsFeatured(true);
        }

        $this->dentistRepository->update($dentist);
        $this->persistenceManager->persistAll();

        return $subscription;
    }

    /**
     * Cancel a subscription and downgrade the dentist to the free tier.
     */
    public function cancelSubscription(Subscription $subscription): void
    {
        $subscription->setStatus(Subscription::STATUS_CANCELLED);

        $dentist = $subscription->getDentist();
        if ($dentist !== null) {
            $dentist->setListingTier(Dentist::TIER_FREE);
            $dentist->setIsFeatured(false);
            $this->dentistRepository->update($dentist);
        }

        $this->persistenceManager->persistAll();
    }
}

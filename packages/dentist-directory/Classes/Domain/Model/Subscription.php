<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * A paid subscription record that controls listing tier and visibility.
 */
class Subscription extends AbstractEntity
{
    public const PLAN_BASIC   = 'basic';
    public const PLAN_PREMIUM = 'premium';

    public const STATUS_ACTIVE    = 'active';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED   = 'expired';

    protected ?Dentist $dentist  = null;
    protected string $plan       = self::PLAN_BASIC;
    protected float  $priceEur   = 0.0;
    protected int    $startsAt   = 0;
    protected int    $endsAt     = 0;
    protected string $paymentRef = '';
    protected string $status     = self::STATUS_ACTIVE;

    public function getDentist(): ?Dentist { return $this->dentist; }
    public function setDentist(?Dentist $dentist): void { $this->dentist = $dentist; }

    public function getPlan(): string { return $this->plan; }
    public function setPlan(string $plan): void { $this->plan = $plan; }

    public function getPriceEur(): float { return $this->priceEur; }
    public function setPriceEur(float $priceEur): void { $this->priceEur = $priceEur; }

    public function getStartsAt(): int { return $this->startsAt; }
    public function setStartsAt(int $startsAt): void { $this->startsAt = $startsAt; }

    public function getEndsAt(): int { return $this->endsAt; }
    public function setEndsAt(int $endsAt): void { $this->endsAt = $endsAt; }

    public function getPaymentRef(): string { return $this->paymentRef; }
    public function setPaymentRef(string $paymentRef): void { $this->paymentRef = $paymentRef; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }

    public function isActive(): bool { return $this->status === self::STATUS_ACTIVE; }

    public function isExpired(): bool
    {
        return $this->endsAt > 0 && $this->endsAt < time();
    }
}

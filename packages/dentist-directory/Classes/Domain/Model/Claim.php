<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * A "claim your business" request submitted by a dentist owner.
 */
class Claim extends AbstractEntity
{
    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected ?Dentist $dentist      = null;
    protected string $claimantName   = '';
    protected string $claimantEmail  = '';
    protected string $claimantPhone  = '';
    protected string $message        = '';
    protected string $token          = '';
    protected string $status         = self::STATUS_PENDING;
    protected int    $reviewedAt     = 0;

    public function getDentist(): ?Dentist { return $this->dentist; }
    public function setDentist(?Dentist $dentist): void { $this->dentist = $dentist; }

    public function getClaimantName(): string { return $this->claimantName; }
    public function setClaimantName(string $claimantName): void { $this->claimantName = $claimantName; }

    public function getClaimantEmail(): string { return $this->claimantEmail; }
    public function setClaimantEmail(string $claimantEmail): void { $this->claimantEmail = $claimantEmail; }

    public function getClaimantPhone(): string { return $this->claimantPhone; }
    public function setClaimantPhone(string $claimantPhone): void { $this->claimantPhone = $claimantPhone; }

    public function getMessage(): string { return $this->message; }
    public function setMessage(string $message): void { $this->message = $message; }

    public function getToken(): string { return $this->token; }
    public function setToken(string $token): void { $this->token = $token; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }

    public function getReviewedAt(): int { return $this->reviewedAt; }
    public function setReviewedAt(int $reviewedAt): void { $this->reviewedAt = $reviewedAt; }

    public function isPending(): bool  { return $this->status === self::STATUS_PENDING; }
    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
}

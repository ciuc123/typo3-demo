<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * A lead (contact enquiry) submitted via a dentist detail page.
 */
class Lead extends AbstractEntity
{
    protected ?Dentist $dentist    = null;
    protected string $senderName   = '';
    protected string $senderEmail  = '';
    protected string $senderPhone  = '';
    protected string $message      = '';
    protected bool   $isRead       = false;

    public function getDentist(): ?Dentist { return $this->dentist; }
    public function setDentist(?Dentist $dentist): void { $this->dentist = $dentist; }

    public function getSenderName(): string { return $this->senderName; }
    public function setSenderName(string $senderName): void { $this->senderName = $senderName; }

    public function getSenderEmail(): string { return $this->senderEmail; }
    public function setSenderEmail(string $senderEmail): void { $this->senderEmail = $senderEmail; }

    public function getSenderPhone(): string { return $this->senderPhone; }
    public function setSenderPhone(string $senderPhone): void { $this->senderPhone = $senderPhone; }

    public function getMessage(): string { return $this->message; }
    public function setMessage(string $message): void { $this->message = $message; }

    public function getIsRead(): bool { return $this->isRead; }
    public function setIsRead(bool $isRead): void { $this->isRead = $isRead; }
}

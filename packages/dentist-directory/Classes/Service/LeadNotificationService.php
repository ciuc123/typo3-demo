<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Service;

use Ciuc123\DentistDirectory\Domain\Model\Lead;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;

/**
 * Notifies a dentist when a new lead is received via their contact form.
 */
class LeadNotificationService
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {}

    public function notifyDentist(Lead $lead): void
    {
        $dentist = $lead->getDentist();

        if ($dentist === null || $dentist->getEmail() === '') {
            return;
        }

        $email = (new FluidEmail())
            ->to(new Address($dentist->getEmail(), $dentist->getName()))
            ->subject('New enquiry from your directory listing')
            ->format(FluidEmail::FORMAT_BOTH)
            ->setTemplate('LeadNotification')
            ->assign('lead', $lead)
            ->assign('dentist', $dentist);

        $this->mailer->send($email);
    }
}

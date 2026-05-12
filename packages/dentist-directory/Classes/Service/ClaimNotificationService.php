<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Service;

use Ciuc123\DentistDirectory\Domain\Model\Claim;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;

/**
 * Sends verification and admin-notification e-mails for claim requests.
 */
class ClaimNotificationService
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {}

    /**
     * Send a verification link to the claimant.
     */
    public function sendVerificationEmail(Claim $claim): void
    {
        $email = (new FluidEmail())
            ->to(new Address($claim->getClaimantEmail(), $claim->getClaimantName()))
            ->subject('Confirm your business claim — Dentist Directory Bucharest')
            ->format(FluidEmail::FORMAT_BOTH)
            ->setTemplate('ClaimVerification')
            ->assign('claim', $claim);

        $this->mailer->send($email);
    }

    /**
     * Notify admin that a new claim is awaiting moderation.
     */
    public function notifyAdmin(Claim $claim, string $adminEmail): void
    {
        $email = (new FluidEmail())
            ->to($adminEmail)
            ->subject('New business claim awaiting moderation')
            ->format(FluidEmail::FORMAT_BOTH)
            ->setTemplate('ClaimAdminNotification')
            ->assign('claim', $claim);

        $this->mailer->send($email);
    }
}

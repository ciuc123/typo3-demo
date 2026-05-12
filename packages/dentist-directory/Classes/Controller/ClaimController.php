<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Controller;

use Ciuc123\DentistDirectory\Domain\Model\Claim;
use Ciuc123\DentistDirectory\Domain\Repository\ClaimRepository;
use Ciuc123\DentistDirectory\Domain\Repository\DentistRepository;
use Ciuc123\DentistDirectory\Service\ClaimNotificationService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Handles the "Claim Your Business" flow:
 *   new  → create (persists, sends e-mail) → confirm (token link) → success
 */
class ClaimController extends ActionController
{
    public function __construct(
        private readonly DentistRepository       $dentistRepository,
        private readonly ClaimRepository         $claimRepository,
        private readonly PersistenceManager      $persistenceManager,
        private readonly ClaimNotificationService $notificationService,
        private readonly Random                  $random,
    ) {}

    /**
     * Step 1 — Show the claim form for a given dentist.
     */
    public function newAction(int $dentist = 0): ResponseInterface
    {
        $dentistRecord = $this->dentistRepository->findByIdentifier($dentist);

        if ($dentistRecord === null) {
            return $this->redirect('index', 'Listing');
        }

        $this->view->assign('dentist', $dentistRecord);
        $this->view->assign('claim', new Claim());

        return $this->htmlResponse();
    }

    /**
     * Step 2 — Persist the claim and trigger a verification e-mail.
     */
    public function createAction(Claim $claim, int $dentistUid = 0): ResponseInterface
    {
        $dentistRecord = $this->dentistRepository->findByIdentifier($dentistUid);

        if ($dentistRecord === null) {
            return $this->redirect('index', 'Listing');
        }

        $claim->setDentist($dentistRecord);
        $claim->setToken($this->random->generateRandomHexString(32));
        $claim->setStatus(Claim::STATUS_PENDING);

        $this->claimRepository->add($claim);
        $this->persistenceManager->persistAll();

        $this->notificationService->sendVerificationEmail($claim);

        return $this->redirect('confirm', null, null, ['token' => $claim->getToken()]);
    }

    /**
     * Step 3 — Confirm via token link (sent in e-mail).
     */
    public function confirmAction(string $token = ''): ResponseInterface
    {
        $claim = $this->claimRepository->findByToken($token);

        if ($claim === null) {
            $this->view->assign('error', 'invalid_token');
            return $this->htmlResponse();
        }

        $this->view->assign('claim', $claim);

        return $this->htmlResponse();
    }

    /**
     * Step 4 — Redirect after successful confirmation display.
     */
    public function successAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }
}

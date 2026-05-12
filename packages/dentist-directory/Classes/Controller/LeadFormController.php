<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Controller;

use Ciuc123\DentistDirectory\Domain\Model\Lead;
use Ciuc123\DentistDirectory\Domain\Repository\DentistRepository;
use Ciuc123\DentistDirectory\Domain\Repository\LeadRepository;
use Ciuc123\DentistDirectory\Service\LeadNotificationService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Handles the lead-generation contact form embedded on dentist detail pages.
 */
class LeadFormController extends ActionController
{
    public function __construct(
        private readonly DentistRepository      $dentistRepository,
        private readonly LeadRepository         $leadRepository,
        private readonly PersistenceManager     $persistenceManager,
        private readonly LeadNotificationService $notificationService,
    ) {}

    /**
     * Show the lead form for a given dentist.
     */
    public function showAction(int $dentist = 0): ResponseInterface
    {
        $dentistRecord = $this->dentistRepository->findByIdentifier($dentist);

        if ($dentistRecord === null) {
            return $this->redirect('index', 'Listing');
        }

        $this->view->assign('dentist', $dentistRecord);
        $this->view->assign('lead', new Lead());

        return $this->htmlResponse();
    }

    /**
     * Persist lead and notify the dentist (if claimed / premium).
     */
    public function submitAction(Lead $lead, int $dentistUid = 0): ResponseInterface
    {
        $dentistRecord = $this->dentistRepository->findByIdentifier($dentistUid);

        if ($dentistRecord === null) {
            return $this->redirect('index', 'Listing');
        }

        $lead->setDentist($dentistRecord);
        $this->leadRepository->add($lead);
        $this->persistenceManager->persistAll();

        $this->notificationService->notifyDentist($lead);

        return $this->redirect('thanks');
    }

    /**
     * Thank-you page after lead submission.
     */
    public function thanksAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }
}

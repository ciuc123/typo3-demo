<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Controller;

use Ciuc123\DentistDirectory\Domain\Repository\CategoryRepository;
use Ciuc123\DentistDirectory\Domain\Repository\DentistRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Handles the public listing page (index) and detail page (show).
 */
class ListingController extends ActionController
{
    public function __construct(
        private readonly DentistRepository  $dentistRepository,
        private readonly CategoryRepository $categoryRepository,
    ) {}

    /**
     * Listing page — shows a filterable grid of approved dentists.
     */
    public function indexAction(): ResponseInterface
    {
        $district    = trim((string)($this->request->getQueryParams()['district'] ?? ''));
        $categoryUid = (int)($this->request->getQueryParams()['category'] ?? 0);
        $search      = trim((string)($this->request->getQueryParams()['search'] ?? ''));

        $dentists   = $this->dentistRepository->findApproved($district, $categoryUid, $search);
        $featured   = $this->dentistRepository->findFeatured(3);
        $categories = $this->categoryRepository->findAll();

        $this->view->assignMultiple([
            'dentists'        => $dentists,
            'featuredDentists'=> $featured,
            'categories'      => $categories,
            'currentDistrict' => $district,
            'currentCategory' => $categoryUid,
            'currentSearch'   => $search,
        ]);

        return $this->htmlResponse();
    }

    /**
     * Detail page — single dentist profile.
     */
    public function showAction(int $dentist = 0): ResponseInterface
    {
        $dentistRecord = $this->dentistRepository->findByIdentifier($dentist);

        if ($dentistRecord === null || !$dentistRecord->isApproved()) {
            return $this->redirect('index');
        }

        $this->view->assign('dentist', $dentistRecord);

        return $this->htmlResponse();
    }
}

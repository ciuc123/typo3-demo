<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * A dentist (or dental clinic) listed in the directory.
 */
class Dentist extends AbstractEntity
{
    public const TIER_FREE    = 'free';
    public const TIER_BASIC   = 'basic';
    public const TIER_PREMIUM = 'premium';

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected string $name           = '';
    protected string $slug           = '';
    protected string $specialization = '';
    protected string $address        = '';
    protected string $district       = '';
    protected string $city           = 'Bucharest';
    protected string $phone          = '';
    protected string $email          = '';
    protected string $website        = '';
    protected string $description    = '';
    protected string $workingHours   = '';
    protected ?float $latitude       = null;
    protected ?float $longitude      = null;
    protected bool   $isFeatured     = false;
    protected bool   $isClaimed      = false;
    protected string $listingTier    = self::TIER_FREE;
    protected string $status         = self::STATUS_PENDING;
    protected string $moderatorNote  = '';

    /**
     * @var ObjectStorage<Category>
     */
    protected ObjectStorage $categories;

    public function __construct()
    {
        $this->categories = new ObjectStorage();
    }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getSlug(): string { return $this->slug; }
    public function setSlug(string $slug): void { $this->slug = $slug; }

    public function getSpecialization(): string { return $this->specialization; }
    public function setSpecialization(string $specialization): void { $this->specialization = $specialization; }

    public function getAddress(): string { return $this->address; }
    public function setAddress(string $address): void { $this->address = $address; }

    public function getDistrict(): string { return $this->district; }
    public function setDistrict(string $district): void { $this->district = $district; }

    public function getCity(): string { return $this->city; }
    public function setCity(string $city): void { $this->city = $city; }

    public function getPhone(): string { return $this->phone; }
    public function setPhone(string $phone): void { $this->phone = $phone; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getWebsite(): string { return $this->website; }
    public function setWebsite(string $website): void { $this->website = $website; }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): void { $this->description = $description; }

    public function getWorkingHours(): string { return $this->workingHours; }
    public function setWorkingHours(string $workingHours): void { $this->workingHours = $workingHours; }

    public function getLatitude(): ?float { return $this->latitude; }
    public function setLatitude(?float $latitude): void { $this->latitude = $latitude; }

    public function getLongitude(): ?float { return $this->longitude; }
    public function setLongitude(?float $longitude): void { $this->longitude = $longitude; }

    public function getIsFeatured(): bool { return $this->isFeatured; }
    public function setIsFeatured(bool $isFeatured): void { $this->isFeatured = $isFeatured; }

    public function getIsClaimed(): bool { return $this->isClaimed; }
    public function setIsClaimed(bool $isClaimed): void { $this->isClaimed = $isClaimed; }

    public function getListingTier(): string { return $this->listingTier; }
    public function setListingTier(string $listingTier): void { $this->listingTier = $listingTier; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }

    public function getModeratorNote(): string { return $this->moderatorNote; }
    public function setModeratorNote(string $moderatorNote): void { $this->moderatorNote = $moderatorNote; }

    public function getCategories(): ObjectStorage { return $this->categories; }
    public function setCategories(ObjectStorage $categories): void { $this->categories = $categories; }
    public function addCategory(Category $category): void { $this->categories->attach($category); }
    public function removeCategory(Category $category): void { $this->categories->detach($category); }

    public function isPremium(): bool { return $this->listingTier === self::TIER_PREMIUM; }
    public function isBasic(): bool   { return $this->listingTier === self::TIER_BASIC; }
    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
}

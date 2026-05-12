<?php

declare(strict_types=1);

namespace Ciuc123\DentistDirectory\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Dental specialization category (e.g. Orthodontics, Pediatric Dentistry …).
 */
class Category extends AbstractEntity
{
    protected string $name = '';
    protected string $slug = '';
    protected string $icon = '';

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getSlug(): string { return $this->slug; }
    public function setSlug(string $slug): void { $this->slug = $slug; }

    public function getIcon(): string { return $this->icon; }
    public function setIcon(string $icon): void { $this->icon = $icon; }
}

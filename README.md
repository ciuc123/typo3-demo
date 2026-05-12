# 🦷 Stomatologi București — Dentist Directory MVP

A niche TYPO3 directory for dentists in Bucharest with **paid listings**, **featured placements**, and **lead-generation forms**.

---

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Tech Stack](#tech-stack)
3. [Quick Start](#quick-start)
4. [Extension Structure](#extension-structure)
5. [Feature Catalogue](#feature-catalogue)
6. [Monetization Tiers](#monetization-tiers)
7. [Admin Moderation Flow](#admin-moderation-flow)
8. [Claim Your Business Flow](#claim-your-business-flow)
9. [Running Tests](#running-tests)
10. [Next Steps](#next-steps)

---

## Architecture Overview

```
typo3-demo/
├── composer.json              # Root project manifest (TYPO3 12 LTS)
├── phpunit.xml                # Unit test config
├── public/                    # Web root (TYPO3 entry point)
│   └── index.php
├── config/
│   └── system/
│       └── settings.php       # DB / mail config (env-driven)
└── packages/
    └── dentist-directory/     # Custom TYPO3 extension
        ├── Classes/
        │   ├── Controller/    # Extbase MVC controllers
        │   ├── Domain/
        │   │   ├── Model/     # Domain entities
        │   │   └── Repository/
        │   └── Service/       # Business logic services
        ├── Configuration/
        │   ├── TCA/           # TYPO3 table configuration
        │   ├── TypoScript/    # setup + constants
        │   └── Routes.yaml    # SEO-friendly URL routing
        ├── Resources/
        │   ├── Private/       # Fluid templates/partials/layouts
        │   └── Public/        # CSS + JS assets
        └── Tests/Unit/        # PHPUnit unit tests
```

---

## Tech Stack

| Layer          | Technology                        |
|----------------|-----------------------------------|
| CMS            | TYPO3 12 LTS                      |
| PHP            | 8.2+                              |
| MVC            | Extbase + Fluid                   |
| DB             | MySQL / MariaDB                   |
| Frontend       | Plain CSS (BEM-ish) + Vanilla JS  |
| Maps           | OpenStreetMap via Leaflet (lazy)  |
| Mail           | TYPO3 FluidEmail                  |
| Payments       | PaymentService (Stripe-ready hook)|

---

## Quick Start

### Prerequisites

- PHP 8.2+
- Composer 2
- MySQL / MariaDB
- Web server pointing to `public/`

### Install

```bash
git clone https://github.com/ciuc123/typo3-demo.git
cd typo3-demo

# Install TYPO3 + dependencies
composer install

# Run the TYPO3 install wizard
php vendor/bin/typo3 install:setup \
  --database-host=127.0.0.1 \
  --database-name=typo3_dentist \
  --database-user=typo3 \
  --database-password=secret \
  --admin-username=admin \
  --admin-password=Admin1234! \
  --site-name="Stomatologi București"
```

### Environment Variables (optional)

| Variable                | Default         | Description           |
|-------------------------|-----------------|-----------------------|
| `TYPO3_DB_HOST`         | `127.0.0.1`     | Database host         |
| `TYPO3_DB_DBNAME`       | `typo3_dentist` | Database name         |
| `TYPO3_DB_USER`         | `typo3`         | Database user         |
| `TYPO3_DB_PASSWORD`     | *(empty)*       | Database password     |
| `TYPO3_ENCRYPTION_KEY`  | *(empty)*       | TYPO3 encryption key  |
| `TYPO3_MAIL_HOST`       | `localhost`     | SMTP host             |

### Activate the Extension

In the TYPO3 backend:

1. **Admin Tools → Extensions** → Activate `dentist_directory`
2. **Admin Tools → Maintenance** → Run "Analyse Database Structure" to create tables
3. Add the **TypoScript static template** "Dentist Directory" to your root page
4. Create a page tree:
   - `/stomatologi/` → insert **Dentist Directory — Listing** plugin
   - `/stomatologi/revendica/` → insert **Dentist Directory — Claim Your Business** plugin
5. Set the TypoScript constants accordingly (page UIDs, storage PID)

---

## Extension Structure

### Domain Models

| Model          | Table                                            | Purpose                                   |
|----------------|--------------------------------------------------|-------------------------------------------|
| `Dentist`      | `tx_dentistdirectory_domain_model_dentist`       | Dentist / clinic listing                  |
| `Category`     | `tx_dentistdirectory_domain_model_category`      | Specialization tags (Orthodontics, etc.)  |
| `Claim`        | `tx_dentistdirectory_domain_model_claim`         | Business claim request                    |
| `Subscription` | `tx_dentistdirectory_domain_model_subscription`  | Paid plan record                          |
| `Lead`         | `tx_dentistdirectory_domain_model_lead`          | Contact enquiry from a visitor            |

### Controllers

| Controller           | Actions                               |
|----------------------|---------------------------------------|
| `ListingController`  | `index`, `show`                       |
| `ClaimController`    | `new`, `create`, `confirm`, `success` |
| `LeadFormController` | `show`, `submit`, `thanks`            |

---

## Feature Catalogue

### Listing Page (`/stomatologi/`)

- Responsive card grid (CSS Grid)
- **Featured spotlight** row — premium-tier dentists shown first
- Real-time filter by **district** (Sector 1–6) and **category**
- Free-text **search** (name, specialization, address)
- SEO schema.org `Dentist` markup on detail pages

### Detail Page (`/stomatologi/{slug}`)

- Full profile: photo, description, contact, working hours
- OpenStreetMap embed (Leaflet, lazy-loaded)
- Lead-generation contact form (stored as `Lead`, dentist notified by e-mail)
- "Claim Your Business" CTA for unclaimed listings

### Claim Your Business Flow

1. Visitor fills in the claim form (name, e-mail, phone, message)
2. System generates a one-time token and sends a verification e-mail
3. Claimant clicks the link → claim stored as `pending`
4. Admin moderates in the TYPO3 back-end → approves or rejects
5. On approval: dentist's `is_claimed` flag is set

---

## Monetization Tiers

| Tier        | Monthly Price | Features                                               |
|-------------|:-------------:|--------------------------------------------------------|
| **Free**    | €0            | Basic listing, appears in directory                    |
| **Basic**   | €29           | Enhanced profile, contact form enabled                 |
| **Premium** | €79           | Featured spotlight, top placement, badge, lead e-mails |

Upgrade flow is handled by `PaymentService::activateSubscription()` which accepts an external payment-gateway reference (Stripe / Netopia) and updates the subscription record and listing tier atomically.

---

## Admin Moderation Flow

TYPO3 back-end list views are available for:

- **Dentists** — approve / reject new submissions, set listing tier
- **Claims** — review and approve/reject business claim requests
- **Leads** — read contact enquiries sent via the directory
- **Subscriptions** — track active paid plans

Moderator notes can be added to each dentist record.

---

## Running Tests

Unit tests cover domain models and the PaymentService without requiring a database or a running TYPO3 instance.

```bash
# Install dev dependencies
composer install

# Run unit tests
./vendor/bin/phpunit --configuration phpunit.xml --testsuite unit
```

Expected output: **33 tests, 45 assertions, 0 failures**.

---

## Next Steps

- [ ] Integrate Stripe / Netopia payment gateway in `PaymentService`
- [ ] Build an admin dashboard with KPIs (listings, leads, MRR)
- [ ] Add TYPO3 Scheduler task to expire subscriptions automatically
- [ ] Implement sitemap.xml generation for all approved dentists
- [ ] Add review / rating system
- [ ] Import seed data (dentists from public sources)
- [ ] Set up CI/CD pipeline (GitHub Actions)
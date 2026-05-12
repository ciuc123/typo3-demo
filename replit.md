# Stomatologi București — Dentist Directory MVP

A niche TYPO3 12 CMS directory for dentists in Bucharest with paid listings, featured placements, and lead-generation forms.

## Architecture

- **CMS**: TYPO3 12 LTS (PHP 8.2)
- **MVC**: Extbase + Fluid templates
- **Database**: MySQL 8.0 (socket: `/tmp/mysql.sock`)
- **Frontend**: Plain CSS (BEM-ish) + Vanilla JS + Leaflet maps
- **Extension**: `dentist_directory` (custom, located in `packages/dentist-directory/`)

## Project Layout

```
typo3-demo/
├── composer.json              # Root project manifest (TYPO3 12 LTS)
├── start.sh                   # Startup script (MySQL + PHP built-in server)
├── public/                    # Web root
│   ├── index.php              # TYPO3 frontend entry point
│   ├── router.php             # PHP built-in server router
│   ├── typo3/                 # TYPO3 backend
│   └── _assets/              # Published extension assets
├── config/system/
│   └── settings.php           # DB/mail/sys config (env-driven)
├── packages/
│   └── dentist-directory/     # Custom TYPO3 extension
│       ├── Classes/           # Controllers, Domain Models, Services
│       ├── Configuration/     # TCA, TypoScript, Routes
│       └── Resources/         # Fluid templates, CSS, JS
├── vendor/                    # Composer dependencies
└── var/                       # TYPO3 runtime (cache, log, lock)
```

## Running the Application

The workflow `Start application` runs `bash start.sh` which:
1. Initializes & starts MySQL 8.0 (socket-only, `/tmp/mysql.sock`)
2. Creates the `typo3_dentist` database and `typo3` user
3. Creates required TYPO3 directories
4. Starts PHP built-in server on port 5000

## Environment Variables (set in Replit Secrets)

| Variable | Default | Description |
|---|---|---|
| `TYPO3_DB_HOST` | `localhost` | MySQL host |
| `TYPO3_DB_SOCKET` | `/tmp/mysql.sock` | MySQL socket path |
| `TYPO3_DB_DBNAME` | `typo3_dentist` | Database name |
| `TYPO3_DB_USER` | `typo3` | DB username |
| `TYPO3_DB_PASSWORD` | `typo3secret` | DB password |
| `TYPO3_ENCRYPTION_KEY` | (set) | TYPO3 encryption key |
| `TYPO3_CONTEXT` | `Development` | TYPO3 context |

## TYPO3 Access

- **Frontend**: `/` (after TYPO3 is set up via the installer)
- **Backend**: `/typo3/`
- **Installer**: `/typo3/install.php` (password: `jkl;1234`)

## First-Time Setup

On first run, visit `/typo3/install.php` to complete the TYPO3 installer wizard. The database is already set up — just create an admin user and configure the site name.

## User Preferences

- Keep code organized per TYPO3 12 Extbase conventions
- Use environment variables for all sensitive configuration

# TYPO3 MVP Setup - Complete Configuration Guide

## 📦 What Has Been Created

Your TYPO3 Dentist Directory MVP is now fully containerized with a professional Docker setup including:

### Core Files
- **Dockerfile** - Multi-stage build for optimized TYPO3 application
- **docker-compose.yml** - Orchestrates all services (database, cache, web, app)
- **Makefile** - Convenient commands for development
- **.env.example** - Environment configuration template

### Documentation
- **QUICKSTART.md** - 5-minute setup guide
- **DOCKER_SETUP.md** - Comprehensive documentation
- **README.md** - Can be expanded with project info

### Configuration Files
- **docker/php/php.ini** - PHP configuration
- **docker/php/php-fpm.conf** - PHP-FPM process manager config
- **docker/nginx/nginx.conf** - Nginx main configuration
- **docker/nginx/conf.d/typo3.conf** - TYPO3 site configuration
- **docker/postgres/init.sql** - Database initialization
- **docker/entrypoint.sh** - Container startup script
- **docker/healthcheck.sh** - Service health verification

### Git Configuration
- **.gitignore** - Proper exclusions for Docker/TYPO3 projects
- **.dockerignore** - Optimized Docker build context

## 🚀 Getting Started

### Step 1: Initialize Project Structure
```bash
# Navigate to your project
cd typo3-demo

# Setup directories and environment
make init
```

This will:
- Create required TYPO3 directories (public, fileadmin, typo3conf, var)
- Generate `.env` file from `.env.example`

### Step 2: Build and Start
```bash
# Build containers
make build

# Start all services
make up
```

Or combine them:
```bash
# Full setup (development environment)
make setup
```

### Step 3: Access TYPO3
1. Open your browser to **http://localhost**
2. Follow the TYPO3 installer wizard
3. Create your admin user
4. Set up your first site

### Step 4: Start Building
- Admin panel: http://localhost/typo3
- Create content
- Build your dentist directory

## 🐳 Services Included

### 1. **PostgreSQL Database**
- Modern, scalable relational database
- Default: `typo3` / `typo3secure123`
- Volume: `postgres_data` (persistent storage)

### 2. **Redis Cache**
- In-memory caching for performance
- Reduces database queries
- Session storage

### 3. **PHP-FPM Application**
- TYPO3 CMS runtime environment
- Runs as non-root user (typo3)
- Health checks enabled

### 4. **Nginx Web Server**
- Fast web server and reverse proxy
- Configured for TYPO3
- Gzip compression enabled
- Security headers included

### 5. **MailHog** (Development)
- Catch and inspect emails
- UI: http://localhost:8025
- No real email sending needed for testing

### 6. **phpMyAdmin** (Development)
- Database management UI
- Access: http://localhost:8080
- Users: typo3 / typo3secure123

## 📊 Architecture

```
┌─────────────────────────────────────────────────┐
│                  Users Browser                   │
└─────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────┐
│           Nginx Web Server (Port 80)             │
│  - Static file serving                          │
│  - Reverse proxy to PHP-FPM                    │
│  - Security headers                            │
└─────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────┐
│         PHP-FPM Application Server              │
│  - TYPO3 CMS                                   │
│  - Business logic                              │
│  - Admin interface                             │
└─────────────────────────────────────────────────┘
                        │
        ┌───────────────┼───────────────┐
        ▼               ▼               ▼
┌──────────────┐ ┌──────────────┐ ┌──────────────┐
│ PostgreSQL   │ │   Redis      │ │   MailHog    │
│  Database    │ │    Cache     │ │    SMTP      │
│ (Port 5432)  │ │ (Port 6379)  │ │ (Port 1025)  │
└──────────────┘ └──────────────┘ └──────────────┘
```

## 🔧 Common Commands

```bash
# View status of all services
make status

# View logs
make logs              # All services
make logs-app         # App only
make logs-web         # Web server only
make logs-db          # Database only

# Shell access
make shell-app        # App container shell
make shell-db         # PostgreSQL shell
make php-shell        # PHP interactive shell

# Cache management
make flush-cache      # Clear TYPO3 cache

# Database operations
make db-backup        # Create backup
make db-restore       # Restore from backup.sql

# Lifecycle
make restart          # Restart all services
make down             # Stop all services
make clean            # Remove containers/volumes
make clean-hard       # Remove everything
```

## 📁 Project Structure

```
typo3-demo/
├── Dockerfile                    # Application image
├── docker-compose.yml            # Service orchestration
├── Makefile                      # Development commands
├── composer.json                 # PHP dependencies (create this)
├── composer.lock                 # PHP lock file (create this)
├── .env.example                  # Environment template
├── .env                          # Your configuration (create from template)
├── .gitignore                    # Git exclusions
├── .dockerignore                 # Docker build exclusions
│
├── QUICKSTART.md                 # Setup guide
├── DOCKER_SETUP.md               # Full documentation
├── README.md                     # Project overview
│
├── docker/
│   ├── entrypoint.sh            # Container startup
│   ├── healthcheck.sh           # Health verification
│   ├── php/
│   │   ├── php.ini              # PHP config
│   │   └── php-fpm.conf         # FPM config
│   ├── nginx/
│   │   ├── nginx.conf           # Nginx main config
│   │   └── conf.d/
│   │       └── typo3.conf       # TYPO3 site config
│   └── postgres/
│       └── init.sql             # DB initialization
│
├── public/                       # Web root (create)
│   └── index.php                # TYPO3 entry point
├── fileadmin/                    # User uploads (create)
├── typo3conf/                    # TYPO3 config (create)
├── typo3temp/                    # TYPO3 temps (create)
├── var/
│   ├── cache/                   # Application cache (create)
│   ├── log/                     # Application logs (create)
│   └── lock/                    # Lock files (create)
└── vendor/                       # Composer deps (created by composer)
```

## 🔐 Security Notes

### Development
- Default passwords used for convenience
- MailHog for email testing
- Debug features enabled
- HTTPS not configured

### Production Checklist
Before deploying:

1. **Change All Passwords**
   ```env
   DB_PASSWORD=your-secure-password-123
   ```

2. **Update Trusted Hosts**
   ```env
   TRUSTED_HOSTS_PATTERN=^yourdomain\.com$
   TYPO3_HOST=yourdomain.com
   ```

3. **Configure SSL/TLS**
   - Add certificates to `docker/nginx/ssl/`
   - Enable SSL in `docker/nginx/conf.d/typo3.conf`

4. **Disable Debug Services**
   - Remove MailHog from production
   - Remove phpMyAdmin from production
   - Set `TYPO3_CONTEXT=Production/Live`

5. **Set Production Context**
   ```env
   TYPO3_CONTEXT=Production/Live
   ```

6. **Configure Real Email**
   ```env
   MAIL_HOST=smtp.provider.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@domain.com
   MAIL_PASSWORD=your-app-password
   ```

## 📝 Next Steps

### 1. Create Required PHP Files

Create `composer.json` (if not exists):
```json
{
  "name": "dentist-directory/typo3-mvp",
  "description": "TYPO3 Dentist Directory MVP - Bucharest",
  "require": {
    "php": "^8.2",
    "typo3/cms-core": "^12.4"
  }
}
```

### 2. Initialize Directories
```bash
mkdir -p public fileadmin typo3conf typo3temp var/{cache,log,lock}
```

### 3. Start Development
```bash
make setup
# Then visit http://localhost
```

### 4. Configure Your Domain
Edit `.env`:
```env
TYPO3_HOST=yourdomain.local
TRUSTED_HOSTS_PATTERN=yourdomain\.local
```

Add to `/etc/hosts` (Linux/Mac) or `C:\Windows\System32\drivers\etc\hosts` (Windows):
```
127.0.0.1 yourdomain.local
127.0.0.1 www.yourdomain.local
```

## 🆘 Troubleshooting

### Port Already in Use
```bash
# Change port in .env
HTTP_PORT=8080

# Or find process using port 80
# Linux/Mac: lsof -i :80
# Windows: netstat -ano | findstr :80
```

### Docker Won't Build
```bash
make clean
docker-compose build --no-cache
make up
```

### Database Connection Failed
```bash
make logs-db
# Check for errors in output
```

### Permission Issues
```bash
docker-compose exec app chmod -R 755 var/ fileadmin/ typo3temp/
```

## 📚 Useful Resources

- [TYPO3 Official Docs](https://docs.typo3.org/)
- [Docker Documentation](https://docs.docker.com/)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [Nginx Documentation](https://nginx.org/en/docs/)
- [TYPO3 System Requirements](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Installation/Index.html)

## 🎯 MVP Feature Roadmap

### Phase 1: Foundation (Current)
- ✅ Docker infrastructure
- ✅ TYPO3 CMS
- ⏳ Basic site setup

### Phase 2: Directory Module
- Dentist profile content type
- Search and filter functionality
- Categorization by specialty

### Phase 3: Access Management
- User registration
- Subscription system
- Payment integration
- Access control

### Phase 4: Enhancement
- Admin dashboard
- Analytics
- Reporting
- Marketing tools

## 💡 Tips & Tricks

### Development Workflow
```bash
# Watch logs while developing
make logs-app

# Quick cache clear after changes
make flush-cache

# Test database changes
make shell-db
```

### Performance Tips
- Remember to clear cache after code changes
- Use Redis for session storage
- Enable OPcache in production

### Backup Strategy
```bash
# Daily backup script
*/0 * * * * cd /path/to/typo3-demo && make db-backup
```

## 📞 Support & Contact

For issues:
1. Check `DOCKER_SETUP.md` troubleshooting section
2. Review container logs with `make logs`
3. Check TYPO3 system logs in `var/log/`
4. Visit TYPO3 Slack community

---

## ✅ You're All Set!

Your TYPO3 MVP Docker environment is ready for:
- 🚀 Development
- 🧪 Testing  
- 📦 Deployment
- 🎯 Production (with security adjustments)

**Next: Run `make setup` and start building!**


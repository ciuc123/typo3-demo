# TYPO3 Dentist Directory MVP - Bucharest

A scalable, containerized TYPO3 CMS platform for managing and selling access to a curated directory of dentists in Bucharest.

## 🏥 Project Overview

This MVP enables dentists to register, create profiles, and be listed in a curated directory that potential patients can search and access through a subscription model.

**Key Features:**
- Curated dentist directory with profiles and specializations
- Search and filtering capabilities
- Subscription/access management
- Secure user authentication
- Admin panel for directory curation
- Email notifications
- Responsive design

## 📋 Prerequisites

- Docker & Docker Compose (20.10+)
- Git
- ~2GB free disk space
- Port availability: 80, 443, 5432, 6379

## 🚀 Quick Start

### 1. Clone and Setup

```bash
git clone <your-repo>
cd typo3-demo

# Copy environment template
cp .env.example .env

# Edit .env with your configuration (optional for development)
# nano .env
```

### 2. Initialize TYPO3 Structure (First Time Only)

Before building, create the necessary TYPO3 directories:

```bash
mkdir -p public fileadmin typo3conf typo3temp var/cache var/log var/lock
```

### 3. Create Composer Files

Create `composer.json` in the project root:

```json
{
  "name": "dentist-directory/typo3-mvp",
  "description": "TYPO3 Dentist Directory MVP - Bucharest",
  "require": {
    "php": "^8.2",
    "typo3/cms-core": "^12.4",
    "typo3/cms-backend": "^12.4",
    "typo3/cms-frontend": "^12.4",
    "typo3/cms-fluid": "^12.4",
    "typo3/cms-form": "^12.4"
  },
  "require-dev": {
    "typo3/cms-styleguide": "^12.4"
  },
  "config": {
    "vendor-dir": "vendor",
    "bin-dir": "vendor/bin"
  }
}
```

### 4. Build and Start Containers

```bash
# Build the Docker image
docker-compose build

# Start all services
docker-compose up -d

# View logs
docker-compose logs -f
```

### 5. Access Services

After containers start, access:

- **TYPO3 Frontend**: http://localhost
- **TYPO3 Backend**: http://localhost/typo3 (install password will be shown on first access)
- **MailHog (Email Testing)**: http://localhost:8025
- **phpMyAdmin**: http://localhost:8080
  - User: `typo3`
  - Password: `typo3secure123` (from .env)

## 📁 Project Structure

```
typo3-demo/
├── Dockerfile                 # Multi-stage build for TYPO3
├── docker-compose.yml         # Orchestration configuration
├── composer.json              # PHP dependencies
├── .env.example               # Environment variables template
├── public/                    # Web root
│   └── index.php             # TYPO3 entry point
├── fileadmin/                # User uploads
├── typo3conf/                # TYPO3 configuration
├── typo3temp/                # TYPO3 temporary files
├── var/
│   ├── cache/                # Application cache
│   ├── log/                  # Application logs
│   └── lock/                 # Lock files
├── vendor/                   # Composer dependencies
└── docker/                   # Docker configuration
    ├── php/
    │   ├── php.ini           # PHP configuration
    │   └── php-fpm.conf      # PHP-FPM configuration
    ├── nginx/
    │   ├── nginx.conf        # Nginx main configuration
    │   └── conf.d/
    │       └── typo3.conf    # TYPO3 site configuration
    ├── postgres/
    │   └── init.sql          # Database initialization
    └── entrypoint.sh         # Container startup script
```

## 🐳 Services

### Database (PostgreSQL)
- **Container**: typo3-dentist-db
- **Port**: 5432
- **Default Credentials**: 
  - User: `typo3`
  - Password: `typo3secure123`
  - Database: `typo3_dentist`

### Redis Cache
- **Container**: typo3-dentist-cache
- **Port**: 6379
- Improves performance with caching

### Application (PHP-FPM)
- **Container**: typo3-dentist-app
- **Port**: 9000
- TYPO3 CMS application server

### Web Server (Nginx)
- **Container**: typo3-dentist-web
- **Ports**: 80 (HTTP), 443 (HTTPS)
- Reverse proxy and static file server

### Mail Service (MailHog)
- **Container**: typo3-dentist-mailhog
- **SMTP Port**: 1025
- **UI Port**: 8025
- Development email testing

### Database Management (phpMyAdmin)
- **Container**: typo3-dentist-phpmyadmin
- **Port**: 8080
- Development database administration

## 🔐 Security Configuration

### Production Checklist

Before deploying to production:

1. **Environment Variables**
   ```bash
   cp .env.example .env
   # Edit .env with strong passwords and production values
   ```

2. **SSL/TLS Configuration**
   - Place your certificates in `docker/nginx/ssl/`
   - Update `docker/nginx/conf.d/typo3.conf` with certificate paths
   - Uncomment SSL directives

3. **Change Default Passwords**
   - Database password
   - TYPO3 install tool password
   - All admin credentials

4. **Configure Trusted Hosts**
   ```env
   TRUSTED_HOSTS_PATTERN=^(www\.)?yourdomain\.com$
   ```

5. **Disable Development Tools**
   - Comment out MailHog service in production
   - Comment out phpMyAdmin service in production
   - Set `TYPO3_CONTEXT=Production/Live`

6. **Set Proper Ownership**
   ```bash
   chown -R 1000:1000 var/ fileadmin/ typo3conf/ typo3temp/
   ```

## 📊 Database

### PostgreSQL Details

- Modern relational database
- Better suited for large datasets than MySQL
- Full-text search capabilities
- JSON support
- UUID support for distributed systems

### Backup

```bash
# Backup database
docker-compose exec database pg_dump -U typo3 typo3_dentist > backup.sql

# Restore database
docker-compose exec -T database psql -U typo3 typo3_dentist < backup.sql
```

## 🔄 Common Commands

### View Logs
```bash
# All services
docker-compose logs

# Specific service
docker-compose logs app
docker-compose logs web

# Real-time logs
docker-compose logs -f
```

### Execute Commands
```bash
# Run PHP commands in app container
docker-compose exec app php bin/typo3 cache:flush

# Access database
docker-compose exec database psql -U typo3 -d typo3_dentist

# Access PHP shell
docker-compose exec app php
```

### Stop/Start Services
```bash
# Stop all services
docker-compose down

# Start services
docker-compose up -d

# Restart specific service
docker-compose restart app
```

### Clear Cache
```bash
# Clear TYPO3 cache
docker-compose exec app rm -rf var/cache/*

# Verify cache is cleared
docker-compose exec app ls -la var/cache/
```

## 📝 TYPO3 Installation

After containers are running:

1. Access http://localhost in your browser
2. TYPO3 installer will guide you through setup
3. Create admin user
4. Create a site configuration
5. Begin building your directory

## 🌐 Configuring Your Domain

### Development (localhost)
Already configured for `*.localhost` in `docker-compose.yml`

### Production Domain
1. Update `.env`:
   ```env
   TYPO3_HOST=yourdomain.com
   TRUSTED_HOSTS_PATTERN=^(www\.)?yourdomain\.com$
   ```

2. Update `docker/nginx/conf.d/typo3.conf`:
   ```nginx
   server_name yourdomain.com www.yourdomain.com;
   ```

3. Configure DNS to point to your server IP

## 📧 Email Configuration

### Development (MailHog)
Configured by default. Emails are captured at http://localhost:8025

### Production (Real SMTP)
Update `.env`:
```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
```

Then configure in TYPO3: Admin Tools → Settings → Mail

## 🚨 Troubleshooting

### Container Won't Start
```bash
# Check logs
docker-compose logs app

# Rebuild without cache
docker-compose build --no-cache
```

### Database Connection Error
```bash
# Verify database is running
docker-compose ps

# Check database logs
docker-compose logs database

# Test connection
docker-compose exec database psql -U typo3 -d typo3_dentist -c "SELECT version();"
```

### Permission Denied Errors
```bash
# Fix permissions
docker-compose exec app chmod -R 755 var/ fileadmin/ typo3temp/
docker-compose exec app chown -R 1000:1000 var/ fileadmin/ typo3temp/
```

### Port Already in Use
```bash
# Change ports in .env or docker-compose.yml
# Or find and kill existing process
# For Linux: lsof -i :80
# For Windows: netstat -ano | findstr :80
```

### Unable to Connect to Redis
```bash
# Verify Redis is running
docker-compose logs redis

# Clear and restart
docker-compose down -v
docker-compose up -d redis
```

## 🔧 Performance Tuning

### PHP Memory
Update `.env`:
```env
PHP_MEMORY_LIMIT=1024M
```

### Database Connections
Edit `docker/php/php-fpm.conf`:
```
pm.max_children = 50
pm.max_requests = 5000
```

### Nginx Caching
All static assets are cached for 30 days. Update cache settings in `docker/nginx/nginx.conf`

## 📚 TYPO3 Resources

- [Official TYPO3 Documentation](https://docs.typo3.org/)
- [TYPO3 System Requirements](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Installation/Index.html)
- [TYPO3 Community](https://typo3.org/)
- [TYPO3 TER (Extensions)](https://extensions.typo3.org/)

## 🎯 Next Steps for MVP

1. **Extend TYPO3 with Custom Extensions**
   - Create dentist profile content type
   - Implement search functionality
   - Build subscription management

2. **Frontend Development**
   - Design responsive website
   - Create dentist listing page
   - Build search and filter UX
   - Implement subscription flow

3. **Backend Features**
   - User registration workflow
   - Dentist verification process
   - Payment integration
   - Analytics dashboard

4. **Deployment**
   - Configure production domain
   - Set up SSL certificates
   - Configure email service
   - Set up backups and monitoring

## 📜 License

[Add your license here]

## 👥 Support

For issues, questions, or contributions, please contact the development team.

---

**Built with ❤️ for Bucharest Dental Community**


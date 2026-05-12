.PHONY: help build up down logs shell restart clean flush-cache db-backup db-restore status

# TYPO3 Dentist Directory MVP Docker Makefile

help:
	@echo "TYPO3 Dentist Directory MVP - Docker Commands"
	@echo ""
	@echo "Build & Deployment:"
	@echo "  make build        - Build Docker images"
	@echo "  make up           - Start all services"
	@echo "  make down         - Stop all services"
	@echo "  make restart      - Restart all services"
	@echo ""
	@echo "Info & Logs:"
	@echo "  make status       - Show service status"
	@echo "  make logs         - View logs from all services"
	@echo "  make logs-app     - View app logs"
	@echo "  make logs-web     - View web server logs"
	@echo "  make logs-db      - View database logs"
	@echo ""
	@echo "Commands:"
	@echo "  make shell-app    - Access app container shell"
	@echo "  make shell-db     - Access database shell"
	@echo "  make shell-redis  - Access redis-cli"
	@echo "  make php-shell    - Access PHP interactive shell"
	@echo ""
	@echo "Cache & Database:"
	@echo "  make flush-cache  - Clear TYPO3 cache"
	@echo "  make db-backup    - Backup database"
	@echo "  make db-restore   - Restore database"
	@echo ""
	@echo "Cleanup:"
	@echo "  make clean        - Remove containers and volumes"
	@echo "  make clean-hard   - Remove all containers, volumes, and images"

# Build Docker images
build:
	@echo "Building Docker images..."
	docker-compose build

# Start services
up:
	@echo "Starting services..."
	docker-compose up -d
	@echo ""
	@echo "✓ Services started!"
	@echo ""
	@echo "Access services at:"
	@echo "  - TYPO3 Frontend:    http://localhost"
	@echo "  - TYPO3 Backend:     http://localhost/typo3"
	@echo "  - MailHog:           http://localhost:8025"
	@echo "  - phpMyAdmin:        http://localhost:8080"
	@echo ""
	@$(MAKE) status

# Stop services
down:
	@echo "Stopping services..."
	docker-compose down

# Restart services
restart:
	@echo "Restarting services..."
	docker-compose restart
	@$(MAKE) status

# Show service status
status:
	@echo "Service Status:"
	@docker-compose ps

# View logs
logs:
	docker-compose logs -f

logs-app:
	docker-compose logs -f app

logs-web:
	docker-compose logs -f web

logs-db:
	docker-compose logs -f database

logs-redis:
	docker-compose logs -f redis

# Shell access
shell-app:
	docker-compose exec app /bin/sh

shell-db:
	docker-compose exec database psql -U typo3 -d typo3_dentist

shell-redis:
	docker-compose exec redis redis-cli

php-shell:
	docker-compose exec app php -a

# TYPO3 Commands
flush-cache:
	@echo "Flushing TYPO3 cache..."
	docker-compose exec app rm -rf var/cache/*
	@echo "✓ Cache flushed"

# Database operations
db-backup:
	@echo "Creating database backup..."
	docker-compose exec database pg_dump -U typo3 typo3_dentist > backup-$$(date +%Y%m%d-%H%M%S).sql
	@echo "✓ Backup created"

db-restore:
	@echo "Restoring database from backup.sql..."
	docker-compose exec -T database psql -U typo3 typo3_dentist < backup.sql
	@echo "✓ Database restored"

# Cleanup
clean:
	@echo "Removing containers and volumes..."
	docker-compose down -v
	@echo "✓ Cleanup complete"

clean-hard:
	@echo "Removing all containers, volumes, and images..."
	docker-compose down -v
	docker-compose rmi -a
	@echo "✓ Complete cleanup done"

# Install PHP dependencies
composer-install:
	docker-compose exec app composer install

composer-update:
	docker-compose exec app composer update

# Copy environment file
env-setup:
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo "✓ .env file created from .env.example"; \
	else \
		echo ".env file already exists"; \
	fi

# Initialize project structure
init: env-setup
	@echo "Initializing TYPO3 project structure..."
	mkdir -p public fileadmin typo3conf typo3temp var/cache var/log var/lock
	@echo "✓ Directory structure created"
	@echo "✓ Now run 'make build' and 'make up'"

# Full setup (first time only)
setup: init build up
	@echo ""
	@echo "✓ TYPO3 MVP setup complete!"
	@echo ""
	@echo "Next steps:"
	@echo "  1. Visit http://localhost in your browser"
	@echo "  2. Follow TYPO3 installer"
	@echo "  3. Access backend at http://localhost/typo3"
	@echo ""

# Health check
health:
	@echo "Checking service health..."
	@docker-compose ps --format table
	@echo ""
	@echo "Detailed health check:"
	@docker-compose exec -T database pg_isready -U typo3 && echo "✓ Database: OK" || echo "✗ Database: FAILED"
	@docker-compose exec -T redis redis-cli ping > /dev/null && echo "✓ Redis: OK" || echo "✗ Redis: FAILED"
	@curl -s http://localhost:9000/status > /dev/null && echo "✓ PHP-FPM: OK" || echo "✗ PHP-FPM: FAILED"
	@curl -s http://localhost:80/health > /dev/null && echo "✓ Nginx: OK" || echo "✗ Nginx: FAILED"


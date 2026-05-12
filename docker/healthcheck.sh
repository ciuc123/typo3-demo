#!/bin/bash
# Health check script for TYPO3 MVP Docker setup

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "=========================================="
echo "TYPO3 MVP Docker Health Check"
echo "=========================================="
echo ""

# Colors for status
print_status() {
    local status=$1
    local message=$2

    if [ "$status" = "ok" ]; then
        echo -e "${GREEN}✓${NC} $message"
    elif [ "$status" = "warn" ]; then
        echo -e "${YELLOW}⚠${NC} $message"
    else
        echo -e "${RED}✗${NC} $message"
    fi
}

# Check Docker
echo "Checking Docker installation..."
if command -v docker &> /dev/null; then
    print_status "ok" "Docker is installed"
else
    print_status "error" "Docker is not installed"
    exit 1
fi

# Check Docker Compose
echo "Checking Docker Compose..."
if command -v docker-compose &> /dev/null; then
    print_status "ok" "Docker Compose is installed"
else
    print_status "error" "Docker Compose is not installed"
    exit 1
fi

echo ""
echo "Checking running containers..."

# Check if docker-compose.yml exists
if [ ! -f "docker-compose.yml" ]; then
    print_status "error" "docker-compose.yml not found. Run from project root."
    exit 1
fi

# Get container status
if docker-compose ps --services --filter "status=running" | grep -q .; then
    print_status "ok" "Services are running"
else
    print_status "warn" "No running services. Run 'docker-compose up -d'"
    exit 0
fi

echo ""
echo "Checking Service Health..."

# Database check
echo -n "PostgreSQL Database: "
if docker-compose exec -T database pg_isready -U typo3 &> /dev/null; then
    echo -e "${GREEN}✓ OK${NC}"
else
    echo -e "${RED}✗ FAILED${NC}"
fi

# Redis check
echo -n "Redis Cache: "
if docker-compose exec -T redis redis-cli ping > /dev/null 2>&1; then
    echo -e "${GREEN}✓ OK${NC}"
else
    echo -e "${RED}✗ FAILED${NC}"
fi

# Web server check
echo -n "Nginx Web Server: "
if curl -s http://localhost/health > /dev/null 2>&1; then
    echo -e "${GREEN}✓ OK${NC}"
else
    echo -e "${RED}✗ FAILED${NC}"
fi

# PHP-FPM check
echo -n "PHP-FPM Application: "
if [ -S /var/run/docker.sock ]; then
    php_status=$(docker-compose ps app | grep -c "running" || echo "0")
    if [ "$php_status" -gt 0 ]; then
        echo -e "${GREEN}✓ OK${NC}"
    else
        echo -e "${RED}✗ FAILED${NC}"
    fi
fi

# MailHog check
echo -n "MailHog (Email Testing): "
if curl -s http://localhost:8025 > /dev/null 2>&1; then
    echo -e "${GREEN}✓ OK${NC}"
else
    echo -e "${YELLOW}⚠ Not responding (may be normal)${NC}"
fi

echo ""
echo "=========================================="
echo "Container Status Summary"
echo "=========================================="
docker-compose ps

echo ""
echo "=========================================="
echo "Access Points"
echo "=========================================="
echo "Frontend:     http://localhost"
echo "Backend:      http://localhost/typo3"
echo "MailHog:      http://localhost:8025"
echo "phpMyAdmin:   http://localhost:8080"
echo ""

# Check for common issues
echo "=========================================="
echo "Checking for Common Issues..."
echo "=========================================="

# Check if .env exists
if [ -f ".env" ]; then
    print_status "ok" ".env file exists"
else
    print_status "warn" ".env file not found. Copy from .env.example"
fi

# Check disk space
available=$(df . | tail -1 | awk '{print $4}')
if [ "$available" -lt 1000000 ]; then
    print_status "warn" "Low disk space (less than 1GB available)"
else
    print_status "ok" "Sufficient disk space available"
fi

# Check for required directories
dirs=("public" "fileadmin" "typo3conf" "var")
all_exist=true
for dir in "${dirs[@]}"; do
    if [ ! -d "$dir" ]; then
        all_exist=false
        print_status "warn" "Missing directory: $dir"
    fi
done

if [ "$all_exist" = true ]; then
    print_status "ok" "All required directories exist"
fi

echo ""
echo "=========================================="
echo "Health Check Complete!"
echo "=========================================="
echo ""
echo "Useful commands:"
echo "  make status     - Show container status"
echo "  make logs       - View all logs"
echo "  make down       - Stop services"
echo "  make up         - Start services"
echo "  make help       - Show all commands"
echo ""


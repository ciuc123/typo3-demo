#!/bin/bash
set -e

MYSQL_DATADIR="/home/runner/mysql-data"
MYSQL_SOCKET="/tmp/mysql.sock"

# ─── Start MySQL ───────────────────────────────────────────────────────────────
echo "==> Starting MySQL..."
rm -f "$MYSQL_SOCKET" "$MYSQL_SOCKET.lock" 2>/dev/null || true

# Initialize datadir if not already done
if [ ! -d "$MYSQL_DATADIR/mysql" ]; then
    echo "==> Initializing MySQL data directory..."
    mysqld --initialize-insecure --datadir="$MYSQL_DATADIR" --user=runner 2>&1
fi

# Start MySQL in background (socket only, no TCP)
mysqld --no-defaults \
    --datadir="$MYSQL_DATADIR" \
    --socket="$MYSQL_SOCKET" \
    --skip-networking \
    --user=runner \
    --skip-log-bin \
    --innodb-buffer-pool-size=64M \
    2>>"$MYSQL_DATADIR/mysql-error.log" &

# Wait for MySQL socket to become available
echo "==> Waiting for MySQL to be ready..."
for i in $(seq 1 30); do
    if mysql -u root -S "$MYSQL_SOCKET" -e "SELECT 1;" >/dev/null 2>&1; then
        echo "==> MySQL is ready."
        break
    fi
    sleep 1
done

# ─── Setup database and user ───────────────────────────────────────────────────
echo "==> Setting up database..."
mysql -u root -S "$MYSQL_SOCKET" 2>/dev/null <<'SQL'
CREATE DATABASE IF NOT EXISTS typo3_dentist CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'typo3'@'localhost' IDENTIFIED BY 'typo3secret';
GRANT ALL PRIVILEGES ON typo3_dentist.* TO 'typo3'@'localhost';
FLUSH PRIVILEGES;
SQL
echo "==> Database ready."

# ─── Export environment for TYPO3 ─────────────────────────────────────────────
export TYPO3_DB_HOST=localhost
export TYPO3_DB_PORT=3306
export TYPO3_DB_DBNAME=typo3_dentist
export TYPO3_DB_USER=typo3
export TYPO3_DB_PASSWORD=typo3secret
export TYPO3_DB_SOCKET="$MYSQL_SOCKET"
export TYPO3_CONTEXT=Development
export TYPO3_ENCRYPTION_KEY=a1b2c3d4e5f6a1b2c3d4e5f6a1b2c3d4e5f6a1b2c3d4e5f6a1b2c3d4e5f6a1b2
export TYPO3_MAIL_TRANSPORT=sendmail

# ─── Create required TYPO3 directories ────────────────────────────────────────
mkdir -p public/typo3conf fileadmin typo3temp var/cache var/log var/lock var/transient

# Create FIRST_INSTALL if no LocalConfiguration exists yet
if [ ! -f var/cache/.installed ] && [ ! -f config/system/additional.php ]; then
    touch public/FIRST_INSTALL
fi

# Always enable install tool for development
touch var/transient/ENABLE_INSTALL_TOOL

# ─── Start PHP built-in server on port 5000 ───────────────────────────────────
echo "==> Starting PHP web server on port 5000..."
echo "==> Open the preview to access TYPO3 Installer."
exec php -S 0.0.0.0:5000 -t public/ public/router.php 2>&1

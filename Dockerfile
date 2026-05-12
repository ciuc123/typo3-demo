# TYPO3 Healthcare Directory MVP - Dentist Directory for Bucharest
# Multi-stage build for optimal image size

# Stage 1: Build stage with Composer dependencies
FROM php:8.2-fpm-alpine AS builder

# Install system dependencies
RUN apk add --no-cache \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    libfreetype-dev \
    libzip-dev \
    imagemagick \
    imagemagick-dev \
    mysql-client

# Install PHP extensions required by TYPO3
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    mysqli \
    pdo \
    pdo_mysql \
    opcache \
    intl

RUN pecl install imagick && docker-php-ext-enable imagick

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock* ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Stage 2: Runtime stage
FROM php:8.2-fpm-alpine

# Install runtime dependencies only
RUN apk add --no-cache \
    libpng \
    libjpeg-turbo \
    libfreetype \
    libzip \
    imagemagick \
    mysql-client \
    supercronic

# Install PHP extensions
RUN apk add --no-cache --virtual .build-deps \
    libpng-dev \
    libjpeg-turbo-dev \
    libfreetype-dev \
    libzip-dev \
    imagemagick-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    mysqli \
    pdo \
    pdo_mysql \
    opcope \
    intl \
    && pecl install imagick && docker-php-ext-enable imagick \
    && apk del .build-deps

# Create non-root user for security
RUN addgroup -g 1000 typo3 && \
    adduser -D -u 1000 -G typo3 typo3

# Set working directory
WORKDIR /app

# Copy PHP configuration
COPY --chown=typo3:typo3 docker/php/php.ini /usr/local/etc/php/php.ini
COPY --chown=typo3:typo3 docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/zz-custom.conf

# Copy vendor and application files from builder
COPY --from=builder --chown=typo3:typo3 /app /app

# Create necessary TYPO3 directories
RUN mkdir -p var/cache var/log var/lock \
    && chown -R typo3:typo3 /app

# Copy entrypoint script
COPY --chown=typo3:typo3 docker/entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# Switch to non-root user
USER typo3

# Expose PHP-FPM port
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:9000 || exit 1

# Run entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint"]
CMD ["php-fpm"]


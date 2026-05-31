FROM php:8.4-cli

WORKDIR /app

# ── 1. System packages ────────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    default-mysql-client \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ── 2. PHP extensions ─────────────────────────────────────────────────
RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath zip gd opcache

# ── 3. Composer ───────────────────────────────────────────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ── 4. Copy composer files DULU (biar layer ter-cache) ───────────────
COPY composer.json composer.lock ./

# ── 5. Install tanpa scripts (biar tidak butuh .env saat build) ──────
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts          

# ── 6. Copy semua source code ─────────────────────────────────────────
COPY . .

# ── 7. Buat folder yang dibutuhkan Laravel ────────────────────────────
RUN mkdir -p storage/logs \
             storage/framework/cache \
             storage/framework/sessions \
             storage/framework/views \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# ── 8. Start: dump-autoload → migrate → serve ─────────────────────────
CMD composer dump-autoload --optimize && \
    php artisan package:discover --ansi && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan storage:link --force 2>/dev/null || true && \
    php -S 0.0.0.0:${PORT:-8080} -t public

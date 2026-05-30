FROM php:8.4-cli

WORKDIR /app

# Install dependency sistem
RUN apt-get update && apt-get install -y \
    git curl unzip zip libpng-dev libonig-dev libxml2-dev

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permission storage (penting untuk Laravel)
RUN chmod -R 775 storage bootstrap/cache

# Expose port Railway
EXPOSE 8080

# Start Laravel server + migrate
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080
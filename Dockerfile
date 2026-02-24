FROM php:8.3-cli

# install dependency PHP + Node
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip libicu-dev git curl nodejs npm \
    && docker-php-ext-install intl zip pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# install PHP dependency
RUN composer install --no-dev --optimize-autoloader

# build Vite + Filament assets
RUN php artisan filament:install || true
RUN php artisan optimize:clear || true
RUN npm install
RUN npm run build
RUN php artisan filament:assets || true

# clear cache
RUN php artisan optimize:clear || true

CMD php artisan serve --host=0.0.0.0 --port=$PORT
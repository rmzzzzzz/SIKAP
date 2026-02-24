FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip libicu-dev \
    && docker-php-ext-install intl zip pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN php artisan key:generate || true
RUN php artisan migrate --force || true

CMD php artisan serve --host=0.0.0.0 --port=$PORT
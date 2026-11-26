# ---- Stage 1: build front-end assets (Laravel Mix) ----
FROM node:20-alpine AS assets
WORKDIR /var/www/html
COPY package.json package-lock.json ./
RUN npm ci
COPY webpack.mix.js webpack.config.js tailwind.config.js ./
COPY resources ./resources
COPY public ./public
COPY app ./app
RUN npm run development

# ---- Stage 2: PHP dependencies ----
FROM composer:2 AS vendor
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs --no-interaction

# ---- Stage 3: runtime ----
FROM php:8.1-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev libonig-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring gd zip exif bcmath \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf \
    && echo "upload_max_filesize=64M\npost_max_size=64M\nmemory_limit=512M" > /usr/local/etc/php/conf.d/app.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

COPY . .
COPY --from=vendor /var/www/html/vendor ./vendor
COPY --from=assets /var/www/html/public/js ./public/js
COPY --from=assets /var/www/html/public/css ./public/css
COPY --from=assets /var/www/html/public/mix-manifest.json ./public/mix-manifest.json

RUN composer dump-autoload --optimize --no-dev \
    && chown -R www-data:www-data storage bootstrap/cache files livewire-tmp \
    && chmod -R 775 storage bootstrap/cache files livewire-tmp

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]

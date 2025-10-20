FROM php:8.3-fpm-alpine
RUN apk add --no-cache git curl bash nodejs npm mysql-client
RUN docker-php-ext-install pdo_mysql opcache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN composer install
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
EXPOSE 9000
HEALTHCHECK --interval=30s --timeout=3s CMD curl -f http://localhost:9000 || exit 1
CMD ["php-fpm"]

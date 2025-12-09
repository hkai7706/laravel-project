FROM php:8.2-cli
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts
COPY . .
RUN composer dump-autoload --optimize
RUN chmod -R 775 storage bootstrap/cache
EXPOSE 8080
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan migrate --force && \
    php -S 0.0.0.0:$PORT -t public

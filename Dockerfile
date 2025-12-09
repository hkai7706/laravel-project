RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN chmod -R 775 storage bootstrap/cache 2>/dev/null || true
EXPOSE 8080
CMD php artisan migrate --force && \
    php artisan config:cache && \
    php -S 0.0.0.0:$PORT -t public

FROM php:8.2-apache

# Install system dependencies required by Composer (git, unzip, libzip-dev)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Install mlocati php extension installer for ultra-fast binary extension install
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions mongodb-1.16.1

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Install PHP dependencies via Composer matching PHP 8.2 & MongoDB driver
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction

# Create upload directories and set permissions
RUN mkdir -p /var/www/html/uploads/id_cards /var/www/html/uploads/profile \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 775 /var/www/html/uploads

# Expose HTTP port
EXPOSE 80

CMD ["apache2-foreground"]

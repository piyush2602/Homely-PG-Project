FROM php:8.2-apache

# Install system dependencies & PHP MongoDB extension
RUN apt-get update && apt-get install -y \
    libssl-dev \
    unzip \
    git \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Install PHP dependencies via Composer
RUN composer install --no-dev --optimize-autoloader

# Create upload directories and set permissions
RUN mkdir -p /var/www/html/uploads/id_cards /var/www/html/uploads/profile \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 775 /var/www/html/uploads

# Expose HTTP port
EXPOSE 80

CMD ["apache2-foreground"]

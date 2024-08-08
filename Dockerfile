# Use the official PHP image with PHP 8.2
FROM php:8.2-fpm

# Install necessary packages
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    unzip \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libxslt-dev \
    mariadb-client \
    git

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd intl mbstring pdo pdo_mysql zip xml xsl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Set permissions for the web server user
RUN chown -R www-data:www-data /var/www/html

# Expose port 9000 for php-fpm
EXPOSE 9000

# Start php-fpm server
CMD ["php-fpm"]

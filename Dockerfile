# Stage 1: PHP-FPM
FROM php:8.2-fpm AS php-fpm

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

# Copy current directory contents into the container
COPY . /var/www/html

# Set permissions for Drupal
RUN chown -R www-data:www-data /var/www/html

# Stage 2: NGINX
FROM nginx:latest AS nginx

# Remove the default NGINX configuration file
RUN rm /etc/nginx/conf.d/default.conf

# Copy the custom NGINX configuration file
COPY nginx.conf /etc/nginx/conf.d/

# Copy files from PHP-FPM stage
COPY --from=php-fpm /var/www/html /var/www/html

# Expose port 80
EXPOSE 80

# Start NGINX
CMD ["nginx", "-g", "daemon off;"]

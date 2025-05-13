FROM php:8.2-apache

# Install system dependencies required for Composer and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Set the working directory inside the container
WORKDIR /var/www/html

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY composer.json composer.lock* ./
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Enable Apache mod_rewrite for clean URLs
RUN a2enmod rewrite

# Change the Apache DocumentRoot to point to the 'public' folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Allow .htaccess files and grant access to the public directory
RUN echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# Enable error display for development purposes
RUN echo "display_errors=On\nerror_reporting=E_ALL" > /usr/local/etc/php/conf.d/docker-php-errors.ini

# Copy the rest of the application source code
COPY . /var/www/html/

# Set proper file ownership for Apache user
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 so the container is accessible via HTTP
EXPOSE 80

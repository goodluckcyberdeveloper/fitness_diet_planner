FROM php:8.2-apache

# Install extensions (like mysqli for MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your app to the container
COPY . /var/www/html/


# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

FROM php:8.1-apache

# Enable mysqli extension
RUN docker-php-ext-install mysqli

COPY index.php /var/www/html/
COPY metrics.php /var/www/html/

EXPOSE 80

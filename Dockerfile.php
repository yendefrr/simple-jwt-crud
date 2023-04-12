FROM php:8.1-fpm

RUN docker-php-ext-install pdo_mysql

COPY . /var/www/

RUN chown -R www-data:www-data /var/www/

EXPOSE 80
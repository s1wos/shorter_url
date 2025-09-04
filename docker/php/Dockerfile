FROM php:8.2-fpm-alpine

RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www/html

RUN mkdir -p /var/www/html/storage/logs

RUN mkdir -p /usr/local/etc/php/conf.d
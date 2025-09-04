FROM php:8.2-fpm-bookworm

RUN docker-php-ext-install pdo_mysql

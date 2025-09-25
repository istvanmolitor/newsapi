FROM php:8.1-apache

RUN apt-get update \
 && apt-get install -y git zip libzip-dev libpng-dev libicu-dev libcurl4-openssl-dev libonig-dev libxml2-dev \
 && docker-php-ext-install curl pdo_mysql mysqli mbstring gd dom intl \
 && a2enmod rewrite \
 && sed -i 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer \
 && git config --global --add safe.directory /var/www/html \
 && git config --global --add safe.directory /var/www/html/*

WORKDIR /var/www/html

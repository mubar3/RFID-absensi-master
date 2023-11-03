FROM php:7.4-apache

COPY . /var/www/html/rfid/

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip\
    nano

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN a2enmod rewrite
RUN docker-php-ext-install pdo_mysql
RUN chmod -R 777 /var/www/html/rfid
RUN service apache2 restart
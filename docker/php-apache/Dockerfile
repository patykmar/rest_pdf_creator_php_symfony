FROM php:8.2-apache

#ARG WITH_XDEBUG=false

COPY ["docker/php-apache/000-default.conf", "/etc/apache2/sites-available/000-default.conf"]


RUN a2enmod rewrite
RUN docker-php-ext-install pdo mysqli pdo_mysql

RUN pecl install xdebug;
RUN docker-php-ext-enable xdebug;
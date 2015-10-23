FROM dydx/alpine-nginx-phpfpm:latest

MAINTAINER Geshan Manandhar <geshan@yipl.com.np>

RUN apk --update add php-pgsql php-pdo_pgsql php-phar php-dom curl && rm /var/cache/apk/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY . /var/www

RUN mkdir -p /var/www/storage && chmod 0777 /var/www/storage -R

WORKDIR /var/www

RUN composer install

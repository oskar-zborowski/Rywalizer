# Composer
FROM composer as builder
WORKDIR /app/
COPY composer.* ./
RUN composer install -n

# PHP + Apache
FROM php:8.0-apache

RUN apt-get update \
    && apt-get install -y libzip-dev git wget --no-install-recommends \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install pdo mysqli pdo_mysql zip;

COPY docker/web/apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY docker/web/ssl/cert /etc/apache2/ssl/
COPY docker/web/php/php.ini /usr/local/etc/php/php.ini

RUN mkdir -p /var/www/var/cache/dev
RUN mkdir -p /var/www/var/log
RUN chmod 777 -R /var/www/var

COPY --from=builder /app/vendor /var/www/vendor
COPY . /var/www/

RUN a2enmod rewrite
RUN a2enmod ssl

EXPOSE 80
EXPOSE 443
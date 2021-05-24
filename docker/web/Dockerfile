FROM composer as builder
WORKDIR /app/
COPY composer.* ./
RUN composer install -n

# ======================================================================
FROM php:8.0-apache
 
RUN a2enmod rewrite
 
RUN apt-get update \
  && apt-get install -y libzip-dev git wget --no-install-recommends \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
 
RUN docker-php-ext-install pdo mysqli pdo_mysql zip;
 
RUN wget https://getcomposer.org/download/2.0.9/composer.phar \
    && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer

COPY docker/web/apache.conf /etc/apache2/sites-enabled/000-default.conf
COPY . /var/www/

RUN mkdir -p /var/www/var/cache/dev
RUN mkdir -p /var/www/var/log
RUN chmod 777 -R /var/www/var

CMD ["apache2-foreground"]

COPY --from=builder /app/vendor /var/www/vendor

WORKDIR /var/www
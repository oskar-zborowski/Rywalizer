FROM php:8.0-apache

RUN apt-get update --fix-missing
RUN apt-get install -y curl
RUN apt-get install -y build-essential libssl-dev zlib1g-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev wkhtmltopdf
RUN docker-php-ext-install pdo pdo_mysql zip;

# Install GD
RUN docker-php-ext-configure gd \
        --with-freetype=/usr/include/freetype2 \
        --with-jpeg=/usr/include
RUN docker-php-ext-install gd;

# Copy project and config files
COPY . /var/www/
COPY docker/web/apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf
# COPY docker/web/ssl/cert /etc/apache2/ssl/
COPY docker/web/php/php.ini /usr/local/etc/php/php.ini

# Install Composer and dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www/
RUN composer install -n --no-scripts
RUN chmod 777 /var/www/vendor -R

RUN a2enmod rewrite
RUN a2enmod ssl

EXPOSE 80
EXPOSE 443
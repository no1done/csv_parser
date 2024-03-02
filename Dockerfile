FROM php:8.3-apache

RUN apt-get update \
 && apt-get install -y git vim zlib1g-dev libicu-dev libzip-dev\
 && docker-php-ext-install zip pdo pdo_mysql \
 && docker-php-ext-configure intl \
 && docker-php-ext-install intl \
 && a2enmod rewrite \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
 && mv /var/www/html /var/www/public \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

CMD [ "composer", "serve" ]
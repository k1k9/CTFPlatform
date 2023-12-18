FROM php:8.1-apache

RUN  a2enmod headers

RUN groupadd -g 1000 dockerinit

RUN useradd -u 1000 -ms /bin/bash -g dockerinit dockerinit

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

COPY src/ /var/www/html/

RUN chown -R dockerinit:dockerinit /var/www/html && a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER dockerinit

WORKDIR /var/www/html

RUN composer install -vvv

USER root

RUN service apache2 restart

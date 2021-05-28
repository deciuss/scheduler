FROM php:8.0.2-apache-buster

ADD ./ /var/www/
ADD ./build/apache/sites-available/default.conf /etc/apache2/sites-enabled/000-default.conf

RUN cd ~ \
    && apt update \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-enable pdo pdo_mysql \
    && apt-get -y install git \
    && curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php \
    && a2enmod rewrite \
    && mkdir /.composer \
    && chmod -R 777 /var/www/ \
    && chmod -R 777 /.composer \
    && cd /var/www \
    && cp .env .env.local

WORKDIR /var/www

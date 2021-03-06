FROM php:7.2-apache
LABEL maintainer="BluePsyduck <buepsyduck@gmx.com>"
ENV FIB_ENV development

RUN apt-get update && apt-get install -y --quiet --no-install-recommends \
        git \
        libbz2-dev \
        libxml2-dev \
        ssh \
        subversion \
        zlib1g-dev \
    && docker-php-ext-install \
        bz2 \
        mbstring \
        pdo_mysql \
        xml \
        zip

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN apt-get install -y --quiet --no-install-recommends \
        gnupg \
        wget \
    && curl -sL https://deb.nodesource.com/setup_6.x | bash - \
    && apt-get install -y --quiet --no-install-recommends \
            nodejs
RUN npm install -g gulp-cli

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN rm -rf /var/www/html && ln -s /project/public /var/www/html

WORKDIR /project
FROM php:8.1.0-fpm

WORKDIR "/application"

ARG DOCKER_GID=1000
ARG DOCKER_UID=1000
ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
    && apt-get install -y \
        libicu-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libzip-dev \
        libpng-dev \
        libpq-dev \
        libxrender-dev \
        libfontconfig \
        libxext6 \
        git \
        net-tools iputils-ping iproute2 dnsutils\
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*


RUN docker-php-ext-install bcmath zip pdo_pgsql exif

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN pecl install redis xdebug \
    && docker-php-ext-enable redis.so xdebug

RUN curl https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer | php -- --quiet \
    && mv composer.phar /usr/local/bin/composer

RUN groupadd -g $DOCKER_GID dev
RUN useradd -g $DOCKER_GID -u $DOCKER_UID dev
RUN mkdir /home/dev
RUN chown -R dev:dev /home/dev

COPY ./application /application
COPY "installer/php-fpm/php-ini-overrides.ini" "/usr/local/etc/php/conf.d/99-overrides.ini"

RUN mkdir -p /var/www/.config/psysh
RUN chown 33:33 /var/www/.config/psysh
RUN chown 33:33 /application/bootstrap/cache

USER dev

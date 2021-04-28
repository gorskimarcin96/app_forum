# Install PHP + apache
FROM php:7.4-apache
RUN apt-get update -y && apt-get upgrade -y
RUN apt-get -y install openssl libssl-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xDebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install mongodb
RUN pecl config-set php_ini "${PHP_INI_DIR}/php.ini"
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install redis
RUN pecl install redis-5.1.1
RUN docker-php-ext-enable redis

# Install amqp
RUN apt-get update \
    && apt-get install -y \
        librabbitmq-dev \
        libssh-dev \
    && docker-php-ext-install \
        bcmath \
        sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp

# Install gd
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

RUN apt-get update -y && apt-get install -y libonig-dev libpng-dev
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip
RUN docker-php-ext-install gd

#
ARG uid=1000
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser

RUN useradd -ms /bin/bash ubuntu && \
    chown -R www-data:www-data /var/www/html && \
    usermod -a -G www-data ubuntu
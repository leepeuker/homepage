FROM php:7.3.4-fpm-alpine

# system update
RUN apk update && apk upgrade

# install system dependencies
RUN apk add --no-cache git              \
                       bash             \
                       nano             \
                       curl             \
                       wget             \
                       unzip            \
                       make             \
                       zip              \
                       libzip-dev       \
                       tzdata           \
                       $PHPIZE_DEPS

# Set timezone
RUN cp /usr/share/zoneinfo/Europe/Berlin /etc/localtime && echo "Europe/Berlin" > /etc/timezone

# install and enable php dependencies
RUN pecl install xdebug-beta            \
    &&                                  \
    docker-php-ext-install mysqli       \
                           pdo          \
                           pdo_mysql    \
                           zip          \
    &&                                  \
    docker-php-ext-enable xdebug

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
FROM alpine:3.13

# for laravel lumen run smoothly
RUN apk --no-cache add \
php7 \
php7-fpm \
php7-pdo \
php7-mbstring \
php7-openssl \
php7-json \
php7-curl

# for composer & our project depency run smoothly
RUN apk --no-cache add \
php7-phar \
php7-xml \
php7-xmlwriter

# if need composer to update plugin / vendor used
RUN php7 -r "copy('http://getcomposer.org/installer', 'composer-setup.php');" && \
php7 composer-setup.php --install-dir=/usr/bin --filename=composer && \
php7 -r "unlink('composer-setup.php');"

FROM php:7.3-fpm
RUN apt-get update && apt-get install -y git zip unzip \
    && apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php \
    && docker-php-ext-install opcache \
    && pecl install mongodb apcu && docker-php-ext-enable mongodb apcu opcache
# copy all of the file in folder to /src
COPY . /src
WORKDIR /src

RUN composer update

# run the php server service
# move this command to -> docker-compose.yml
# CMD php -S 0.0.0.0:8080 public/index.php
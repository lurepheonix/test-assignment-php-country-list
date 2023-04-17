FROM alpine:3.17

RUN apk add --no-cache \
    php81 \
    php81-fpm \
    php81-pdo_mysql \
    php81-opcache \
    php81-pecl-xdebug \
    composer

COPY 50_xdebug.ini /etc/php81/conf.d/50_xdebug.ini
COPY 60-overrides.ini /etc/php81/conf.d/60-overrides.ini
COPY php-fpm.conf /etc/php81/php-fpm.conf

EXPOSE 9000

CMD ["php-fpm81", "-F"]
FROM alpine:3.17

RUN apk add --no-cache \
    php81 \
    php81-fpm \
    php81-pdo_mysql \
    php81-opcache \
    php81-pecl-xdebug

COPY xdebug.ini /etc/php81/conf.d/xdebug.ini
COPY php-fpm.conf /etc/php81/php-fpm.conf

EXPOSE 9000

CMD ["php-fpm81", "-F"]
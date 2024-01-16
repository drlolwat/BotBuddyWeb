FROM php:8.2-cli-alpine

RUN apk update && apk add \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    icu-dev \
    linux-headers

RUN docker-php-ext-install pdo pdo_mysql bcmath

RUN apk add freetype-dev libpng-dev libjpeg-turbo-dev
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

RUN docker-php-ext-install sockets

RUN apk add icu-dev
RUN docker-php-ext-configure intl && docker-php-ext-install intl

COPY crontab /etc/crontabs/root

CMD ["crond", "-f"]

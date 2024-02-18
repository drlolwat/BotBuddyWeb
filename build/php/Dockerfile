FROM php:8.3-fpm-alpine

COPY php.ini /usr/local/etc/php/conf.d/custom-php.ini

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

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --update nodejs npm

RUN mkdir /.npm && chown -R 1000:1000 /.npm && chmod -R 775 /.npm

RUN mkdir -p /.config/psysh && chown -R 1000:1000 /.config/psysh && chmod -R 775 /.config/psysh

ENV GOLANG_VERSION 1.22.0
ENV GOPATH /go
ENV PATH $GOPATH/bin:/usr/local/go/bin:$PATH

RUN apk add --no-cache --virtual .build-deps bash gcc musl-dev openssl go \
    && wget -O go.tgz "https://golang.org/dl/go${GOLANG_VERSION}.linux-amd64.tar.gz" \
    && tar -C /usr/local -xzf go.tgz \
    && rm go.tgz \
    && go version \
    && apk del .build-deps

RUN mkdir -p /go/src /go/bin /go/cache && chmod -R 777 /go

ENV GOCACHE /go/cache

USER 1000:1000

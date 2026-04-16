FROM php:8.5-cli-alpine3.23@sha256:dccc3abcf3d37a6bb081477a66ed4344716784a6ef5107625ae6ba9ec52df778

RUN apk add --no-cache bash

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer xdebug

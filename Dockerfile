FROM php:8.4-cli-alpine3.21@sha256:a5ed9d13125ab057c0f356f4f82e4ceda0592543e710b8b205ad6f38be960922

RUN apk add --no-cache bash

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer xdebug

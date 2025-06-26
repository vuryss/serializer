FROM php:8.4-cli-alpine3.21@sha256:16a8c99ac0711a15cbfbee5d1632e274ae52ed3572c981c749bffc734ffa8160

RUN apk add --no-cache bash

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer xdebug

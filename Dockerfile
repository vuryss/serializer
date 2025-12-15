FROM php:8.5-cli-alpine3.21@sha256:43e78d4aad07fac24ee6f2430b7080b0f05f3f673171e8ec964a4057e01567e2

RUN apk add --no-cache bash

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer xdebug

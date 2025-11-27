FROM php:8.4-cli-alpine3.21@sha256:9c1dd92c492546d1de23decef0d67280f3f9413942ff44ec119af6db642cd9f0

RUN apk add --no-cache bash

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer xdebug

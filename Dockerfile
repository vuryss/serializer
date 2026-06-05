FROM php:8.5-cli-alpine3.23@sha256:3cfccf28acfbb58ae991324612a3b0e2062a572026bb4dca030020e5295d1633

RUN apk add --no-cache bash

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer xdebug

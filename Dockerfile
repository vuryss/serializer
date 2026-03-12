FROM php:8.5-cli-alpine3.23@sha256:26c79a16621a4dc56b0fbb2fa94df4110c972b19855e5aed1cf952ecfa466785

RUN apk add --no-cache bash

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer xdebug

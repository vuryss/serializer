FROM php:8.5-cli-alpine3.23@sha256:6ca76906d789edfac74e5f109c800b71e571bd313277133eaddc079733ee0b65

RUN apk add --no-cache bash

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer xdebug

#syntax=docker/dockerfile:1

# Adapted from https://github.com/api-platform/api-platform/blob/fa1c5808305d7cadbf7b8392e0fddb6e80fb2092/api/Dockerfile

# Versions
FROM dunglas/frankenphp:1.9-php8.4.10 AS frankenphp_upstream


# The different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target


# Base FrankenPHP image
FROM frankenphp_upstream AS frankenphp_base

WORKDIR /app

# persistent / runtime deps
# hadolint ignore=DL3008
RUN apt-get update && apt-get install --no-install-recommends -y \
	acl \
	file \
	gettext

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN set -eux; \
	install-php-extensions \
		@composer \
		apcu \
		bcmath \
		intl \
		opcache \
        xdebug \
		zip \
	;

###> recipes ###
###> doctrine/doctrine-bundle ###
RUN set -eux; \
	install-php-extensions \
      pdo_pgsql \
      mongodb
###< doctrine/doctrine-bundle ###
###< recipes ###



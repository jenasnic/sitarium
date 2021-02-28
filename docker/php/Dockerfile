FROM composer:2 AS composer
FROM php:7.4-fpm AS php

# Add PHP extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions intl pdo_mysql xdebug zip

# Set timezone
RUN echo 'Europe/Paris' > /etc/timezone

# php-cs-fixer
ENV PHP_CS_FIXER_VERSION=2.17.1
RUN curl -L https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v${PHP_CS_FIXER_VERSION}/php-cs-fixer.phar -o /usr/local/bin/php-cs-fixer \
 && chmod a+x /usr/local/bin/php-cs-fixer

# PHPStan
ENV PHP_STAN_VERSION=0.12.74
RUN curl -L https://github.com/phpstan/phpstan/releases/download/${PHP_STAN_VERSION}/phpstan.phar -o /usr/local/bin/phpstan \
 && chmod a+x /usr/local/bin/phpstan

COPY --from=composer /usr/bin/composer /usr/bin/composer

USER www-data

WORKDIR /www


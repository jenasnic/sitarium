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

#ARG USER_ID=1000
#ARG GROUP_ID=1000
#RUN mkdir /www && chown -R www-data:www-data /www
#RUN groupmod -g ${GROUP_ID} www-data && usermod -u ${USER_ID} -g ${GROUP_ID} www-data

RUN mkdir -p /.composer/cache
RUN chown -R www-data:www-data /var/www

USER www-data

WORKDIR /www

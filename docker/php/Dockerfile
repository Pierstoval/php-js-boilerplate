FROM dunglas/frankenphp:php8.4

ENV PANTHER_NO_SANDBOX=1 \
    PATH=/home/.composer/vendor/bin:$PATH \
    PATH=/home/.config/composer/vendor/bin:$PATH

LABEL maintainer="pierstoval@gmail.com"

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN \
    set -xe \
    && apt-get update \
    && apt-get upgrade -y \
      curl \
      unzip \
    \
    && install-php-extensions \
        apcu \
        curl \
        intl \
        mbstring \
        pdo_pgsql \
        opcache \
        xml \
        zip \
        xdebug \
    \
    && `# Static analysis` \
    && curl -L https://cs.symfony.com/download/php-cs-fixer-v3.phar -o /usr/local/bin/php-cs-fixer && chmod a+x /usr/local/bin/php-cs-fixer

COPY ./Caddyfile /etc/frankenphp/Caddyfile

COPY etc/php.ini /usr/local/etc/php/conf.d/99-application.ini

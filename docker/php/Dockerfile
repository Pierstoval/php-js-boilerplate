FROM debian:10-slim

LABEL maintainer="pierstoval@gmail.com"

WORKDIR /srv

CMD ["php-fpm8.2"]

ENV PHP_VERSION=8.2 \
    GOSU_VERSION=1.14 \
    PANTHER_NO_SANDBOX=1 \
    PATH=/home/.composer/vendor/bin:$PATH \
    PATH=/home/.config/composer/vendor/bin:$PATH \
    RUN_USER="_www"

COPY bin/entrypoint.sh /bin/entrypoint
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN set -xe \
    && apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        wget \
        unzip \
        dialog apt-utils `# Prevents having this issue: https://github.com/moby/moby/issues/27988` \
    \
    \
    && `# Deb Sury PHP repository` \
    && apt-get -y install apt-transport-https lsb-release ca-certificates curl \
    && wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
    && sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list' \
    && apt-get update \
    \
    \
    && `# PHP and extensions` \
    && apt-get install -y \
        php${PHP_VERSION} \
        php${PHP_VERSION}-apcu \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-fpm \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-pgsql \
        php${PHP_VERSION}-opcache \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-zip \
        php${PHP_VERSION}-xdebug \
    \
    && mkdir -p /run/php \
    && touch "/var/log/php-fpm-error.log" \
    && touch "/var/log/php-fpm-access.log" \
    \
    \
    && `# User management for entrypoint` \
    && chmod +x /bin/entrypoint \
    && curl -L -s -o /bin/gosu https://github.com/tianon/gosu/releases/download/${GOSU_VERSION}/gosu-$(dpkg --print-architecture | awk -F- '{ print $NF }') \
    && chmod +x /bin/gosu \
    && mkdir -p /home \
    && groupadd ${RUN_USER} \
    && adduser --home=/home --shell=/bin/bash --ingroup=${RUN_USER} --disabled-password --quiet --gecos "" --force-badname ${RUN_USER} \
    && chown ${RUN_USER}:${RUN_USER} /home \
    \
    \
    && `# Static analysis` \
    && curl -L https://cs.symfony.com/download/php-cs-fixer-v3.phar -o /usr/local/bin/php-cs-fixer && chmod a+x /usr/local/bin/php-cs-fixer \
    \
    \
    && `# Clean apt and remove unused libs/packages to make image smaller` \
    && runuser -l $RUN_USER -c 'composer clearcache' \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o APT::AutoRemove::SuggestsImportant=false $BUILD_LIBS \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/www/* /var/cache/* /home/.composer/cache

COPY etc/php.ini /etc/php/${PHP_VERSION}/fpm/conf.d/99-custom.ini
COPY etc/php.ini /etc/php/${PHP_VERSION}/cli/conf.d/99-custom.ini
COPY etc/fpm.conf /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf

ENTRYPOINT ["/bin/entrypoint"]

FROM node:16-buster-slim

ENV GOSU_VERSION=1.14 \
    RUN_USER="node"

COPY bin/entrypoint.sh /bin/entrypoint

WORKDIR /srv

ENTRYPOINT ["/bin/entrypoint"]

RUN export DEBIAN_FRONTEND=noninteractive \
	&& set -xe \
    && apt-get update \
    && apt-get upgrade -y curl ca-certificates \
    && npm -g i npm \
    && chmod a+x /bin/entrypoint \
    \
    \
    && `# User management for entrypoint` \
    && curl -L -s -o /bin/gosu https://github.com/tianon/gosu/releases/download/${GOSU_VERSION}/gosu-$(dpkg --print-architecture | awk -F- '{ print $NF }') \
    && chmod +x /bin/gosu \
    \
    \
    && `# Clean apt and remove unused libs/packages to make image smaller` \
    && npm cache clean --force \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o APT::AutoRemove::SuggestsImportant=false $BUILD_LIBS \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/www/* /var/cache/* /home/.composer/cache

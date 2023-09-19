# Using ubuntu because playwright isn't compatible with debian.
FROM ubuntu:22.04

ENV GOSU_VERSION=1.16 \
    NODE_VERSION=18 \
    RUN_USER="node"

COPY bin/entrypoint.sh /bin/entrypoint

WORKDIR /srv

ENTRYPOINT ["/bin/entrypoint"]

RUN export DEBIAN_FRONTEND=noninteractive \
    && set -xe \
    && apt-get update \
    && apt-get upgrade -y curl \
    && chmod a+x /bin/entrypoint \
    \
    \
    && `# Install Node.js` \
    && (curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash -) \
    && apt-get install -y nodejs \
    && npm -g i npm \
    && npm -g i yarn \
    \
    \
    && `# User management for entrypoint` \
    && curl -L -s -o /bin/gosu https://github.com/tianon/gosu/releases/download/${GOSU_VERSION}/gosu-$(dpkg --print-architecture | awk -F- '{ print $NF }') \
    && chmod +x /bin/gosu \
    && mkdir -p /home \
    && groupadd ${RUN_USER} \
    && adduser --home=/home --shell=/bin/bash --ingroup=${RUN_USER} --disabled-password --quiet --gecos "" --force-badname ${RUN_USER} \
    && chown ${RUN_USER}:${RUN_USER} /home \
    \
    \
    && `# Clean apt and remove unused libs/packages to make image smaller` \
    && npm cache clean --force \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o APT::AutoRemove::SuggestsImportant=false \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/www/* /var/cache/* /home/.composer/cache

CMD ["yarn", "run", "dev", "--", "--port", "3000", "--host", "0.0.0.0"]

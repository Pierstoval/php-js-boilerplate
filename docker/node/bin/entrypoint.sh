#!/bin/bash
set -e

uid=$(stat -c %u /srv)
gid=$(stat -c %g /srv)

# Caddy certificates handling
# This allows internal HTTP requests to the Caddy container
if [ -f /srv/build/caddy/pki/authorities/local/root.crt ] && [ ! -f /usr/local/share/ca-certificates/caddy.crt ]; then
    cp /srv/build/caddy/pki/authorities/local/root.crt /usr/local/share/ca-certificates/caddy.crt
    update-ca-certificates
fi

sed -i -r "s/${RUN_USER}:x:\d+:\d+:/${RUN_USER}:x:$uid:$gid:/g" /etc/passwd
sed -i -r "s/${RUN_USER}:x:\d+:/${RUN_USER}:x:$gid:/g" /etc/group

[[ -f /srv/package.json ]] && chown -R "${RUN_USER}:${RUN_USER}" /srv/package.json
[[ -f /srv/yarn.lock ]] && chown -R "${RUN_USER}:${RUN_USER}" /srv/yarn.lock
[[ -d /srv/public/build ]] && chown -R "${RUN_USER}:${RUN_USER}" /srv/public/build
[[ -d /srv/node_modules ]] && chown -R "${RUN_USER}:${RUN_USER}" /srv/node_modules

if [ $# -eq 0 ]; then
    printf "\033[32m[%s]\033[0m %s\n" "Node" "Please run a command"
    exit 1
else
    exec gosu "${RUN_USER}" "$@"
fi

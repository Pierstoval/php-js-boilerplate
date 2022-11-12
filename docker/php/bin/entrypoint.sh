#!/bin/bash
set -e

uid=$(stat -c %u /srv)
gid=$(stat -c %g /srv)

sed -i "s/user = .*/user = ${RUN_USER}/g" "/etc/php/${PHP_VERSION}/fpm/pool.d/www.conf"
sed -i "s/group = .*/group = ${RUN_USER}/g" "/etc/php/${PHP_VERSION}/fpm/pool.d/www.conf"
sed -i -r "s/${RUN_USER}:x:\d+:\d+:/${RUN_USER}:x:$uid:$gid:/g" /etc/passwd
sed -i -r "s/${RUN_USER}:x:\d+:/${RUN_USER}:x:$gid:/g" /etc/group

chown -R "${RUN_USER}:${RUN_USER}" "${HOME}"
chown -R "${RUN_USER}:${RUN_USER}" "/srv/"
chown -R "${RUN_USER}:${RUN_USER}" "/run/php"
find /var/log/ -iname "*php*" -type f -exec chown -R "${RUN_USER}:${RUN_USER}" {} \;

if [ $# -eq 0 ]; then
    echo "Please run a command."
    exit 1
else
    exec gosu "${RUN_USER}" "$@"
fi

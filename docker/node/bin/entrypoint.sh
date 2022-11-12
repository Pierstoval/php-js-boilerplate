#!/bin/bash
set -e

uid=$(stat -c %u /srv)
gid=$(stat -c %g /srv)

sed -i -r "s/${RUN_USER}:x:\d+:\d+:/${RUN_USER}:x:$uid:$gid:/g" /etc/passwd
sed -i -r "s/${RUN_USER}:x:\d+:/${RUN_USER}:x:$gid:/g" /etc/group

chown -R "${RUN_USER}:${RUN_USER}" /srv/

if [ $# -eq 0 ]; then
    printf "\033[32m[%s]\033[0m %s\n" "Node" "Please run a command"
    exit 1
else
    exec gosu "${RUN_USER}" "$@"
fi

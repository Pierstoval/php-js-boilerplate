#
#
# Caddy
FROM caddy:2-alpine

RUN apk add nss

COPY ./Caddyfile /etc/caddy/Caddyfile

RUN caddy validate --config /etc/caddy/Caddyfile

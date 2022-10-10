FROM ruby:alpine

RUN apk add --no-cache \
        sqlite-libs

RUN apk add --no-cache --virtual .build-deps \
        build-base \
        sqlite-dev \
 && gem install mailcatcher --no-document \
 && apk del .build-deps

EXPOSE 1080
EXPOSE 1025

CMD ["--smtp-port", "1025", "--http-port", "1080", "--ip", "0.0.0.0", "-f"]
ENTRYPOINT ["mailcatcher"]

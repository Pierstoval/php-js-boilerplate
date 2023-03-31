#!/usr/bin/env bash

set -eu

CWD=$(realpath "$(dirname "${BASH_SOURCE[0]}")")

cd "$CWD"

info() {
    printf " %s" "$1"
}
err() {
    printf " \033[31m[ERROR]\033[0m %s\n" "$1"
}
ok() {
    printf " \033[32m%s\033[0m\n" "Done!"
}

yarn --cwd=frontend upgrade --latest

composer --working-dir=backend update --with-all-dependencies --no-scripts --no-interaction

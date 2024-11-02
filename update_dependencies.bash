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

info "Remove dependencies" 
rm -rf frontend/node_modules backend/vendor
ok

info "Update frontend dependencies"
pnpm --dir=frontend upgrade --latest
ok

info "Update backend dependencies"
composer --working-dir=backend update --with-all-dependencies --no-scripts --no-interaction
ok


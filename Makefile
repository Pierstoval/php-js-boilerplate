# Custom project config

PHP_CONTAINER_NAME    ?= php
PHP_APP_DIR           ?= backend
NODE_CONTAINER_NAME   ?= node
NODE_APP_DIR          ?= frontend
NODE_PKG_MANAGER_NAME ?= yarn

# --------------

_WARN := "\033[33m[WARNING]\033[0m %s\n"  # Yellow text for "printf"
_INFO := "\033[32m[INFO]\033[0m %s\n" # Green text for "printf"
_ERROR := "\033[31m[ERROR]\033[0m %s\n" # Red text for "printf"

SHELL=bash

DOCKER                ?= docker
DOCKER_COMPOSE        ?= docker-compose
DOCKER_COMPOSE_EXEC   ?= $(DOCKER_COMPOSE) exec -T
DOCKER_COMPOSE_RUN    ?= $(DOCKER_COMPOSE) run --rm

PHP                   ?= $(DOCKER_COMPOSE_EXEC) $(PHP_CONTAINER_NAME) entrypoint
COMPOSER              ?= $(DOCKER_COMPOSE_RUN) $(PHP_CONTAINER_NAME) composer
SF_CONSOLE            ?= $(PHP) bin/console
NODE                  ?= $(DOCKER_COMPOSE_EXEC) $(NODE_CONTAINER_NAME) entrypoint
NODE_PKG_MANAGER      ?= $(NODE) $(NODE_PKG_MANAGER_NAME)
NODE_PKG_MANAGER_RUN  ?= $(DOCKER_COMPOSE_RUN) --no-deps $(NODE_CONTAINER_NAME) $(NODE_PKG_MANAGER_NAME)

##
## Project
## =======
##

.DEFAULT_GOAL := help
help: ## Show this help message
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-25s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help

install: ## Install and start the project
install: build node_modules start e2e-setup vendor db test-db openapi-export
.PHONY: install

build: ## Build the Docker images
	@$(DOCKER_COMPOSE) build --compress
.PHONY: build

start: ## Start all containers and the PHP server
	@$(DOCKER_COMPOSE) up -d --remove-orphans
.PHONY: start

stop: ## Stop all containers and the PHP server
	@$(DOCKER_COMPOSE) stop
.PHONY: stop

restart: stop start## Restart the containers & the PHP server
.PHONY: restart

kill: ## Stop all containers
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans
.PHONY: kill

reset: ## Stop and start a fresh install of the project
reset: kill install
.PHONY: reset

clean: ## Stop the project and remove generated files and configuration
clean: kill
	git clean --force -d -x -- \
		$(PHP_APP_DIR)/ \
		$(NODE_APP_DIR)/ \

.PHONY: clean

vendor: ## Install PHP vendors
	$(COMPOSER) install
.PHONY: vendor

node_modules: ## Install JS vendors
	mkdir -p $(NODE_APP_DIR)/node_modules/
	$(NODE_PKG_MANAGER_RUN) install
	$(DOCKER_COMPOSE) up -d $(NODE_CONTAINER_NAME)
.PHONY: node_modules

openapi-export: ## Export OpenAPI data to JSON and create a JS client for frontend use
	$(SF_CONSOLE) --no-interaction api:openapi:export --output=var/openapi/openapi.json
	$(DOCKER_COMPOSE_EXEC) $(NODE_CONTAINER_NAME) mkdir -p build
	@$(DOCKER_COMPOSE_EXEC) $(PHP_CONTAINER_NAME) chown -R 1000:1000 var # Bit hacky, isn't it... But it should work
	@$(DOCKER_COMPOSE_EXEC) $(NODE_CONTAINER_NAME) chown -R 1000:1000 build # same hack here
	cp $(PHP_APP_DIR)/var/openapi/openapi.json \
		$(NODE_APP_DIR)/build/openapi.json
	$(NODE_PKG_MANAGER_RUN) orval
.PHONY: openapi-export

##
## Backend application
## -------------------
##

db:
	$(SF_CONSOLE) --no-interaction doctrine:database:drop --force --if-exists
	$(SF_CONSOLE) --no-interaction doctrine:database:create
	$(SF_CONSOLE) --no-interaction doctrine:migration:migrate
	$(SF_CONSOLE) --no-interaction doctrine:fixtures:load
.PHONY: db

test-db:
	$(SF_CONSOLE) --no-interaction --env=test doctrine:database:drop --force --if-exists
	$(SF_CONSOLE) --no-interaction --env=test doctrine:database:create
	$(SF_CONSOLE) --no-interaction --env=test doctrine:migration:migrate
	$(SF_CONSOLE) --no-interaction --env=test doctrine:fixtures:load
.PHONY: test-db

test-backend: ## Run backend tests
	$(PHP) bin/phpunit
.PHONY: test-backend

php-cs: ## Run php-cs-fixer to format PHP files
	$(PHP) php-cs-fixer fix
.PHONY: php-cs

##
## Frontend application
## --------------------
##

assets-build: ## Build frontend as static site
	$(NODE_PKG_MANAGER) build
.PHONY: assets-build

e2e-setup:
	$(DOCKER_COMPOSE_EXEC) $(NODE_CONTAINER_NAME) $(NODE_PKG_MANAGER_NAME) run playwright install-deps
	$(NODE) $(NODE_PKG_MANAGER_NAME) run playwright install
.PHONY: e2e-setup

test-frontend: ## Run frontend tests
	$(NODE_PKG_MANAGER) run test
.PHONY: test-frontend

prettier: ## Run Prettier on JS/TS files to format them properly
	$(NODE_PKG_MANAGER) run format
.PHONY: prettier

##

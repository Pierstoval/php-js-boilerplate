
_WARN := "\033[33m[%s]\033[0m %s\n"  # Yellow text for "printf"
_TITLE := "\033[32m[%s]\033[0m %s\n" # Green text for "printf"
_ERROR := "\033[31m[%s]\033[0m %s\n" # Red text for "printf"

SHELL=bash

DOCKER               ?= docker
DOCKER_COMPOSE       ?= docker-compose
DOCKER_COMPOSE_EXEC  ?= $(DOCKER_COMPOSE) exec -T
DOCKER_COMPOSE_RUN   ?= $(DOCKER_COMPOSE) run --rm

PHP                  ?= $(DOCKER_COMPOSE_EXEC) php entrypoint
COMPOSER             ?= $(DOCKER_COMPOSE_RUN) php composer
SF_CONSOLE           ?= $(PHP) bin/console
NODE                 ?= $(DOCKER_COMPOSE_EXEC) node entrypoint
YARN                 ?= $(NODE) yarn
YARN_RUN             ?= $(DOCKER_COMPOSE_RUN) node yarn

##
## Project
## =======
##

.DEFAULT_GOAL := help
help: ## Show this help message
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-25s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help

install: ## Install and start the project
install: build node_modules start vendor db openapi-export
.PHONY: install

build: ## Build the Docker images
	@$(DOCKER_COMPOSE) pull --include-deps
	@$(DOCKER_COMPOSE) build --force-rm --compress
.PHONY: build

start: ## Start all containers and the PHP server
	@$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate
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
	rm -rf \
		backend/vendor \
		backend/var/cache/* \
		backend/var/log/* \
		backend/var/sessions/* \
		frontend/node_modules \
		frontend/build \

.PHONY: clean

vendor: ## Install PHP vendors
	$(COMPOSER) install
.PHONY: vendor

node_modules: frontend/yarn.lock ## Install JS vendors
	mkdir -p frontend/node_modules/
	$(YARN_RUN) install
	$(DOCKER_COMPOSE) up -d node
.PHONY: node_modules

openapi-export: ## Export OpenAPI data to JSON and create a JS client for frontend use
	$(SF_CONSOLE) --no-interaction api:openapi:export --output=var/openapi/openapi.json
	mkdir -p frontend/build
	cp backend/var/openapi/openapi.json frontend/build/openapi.json
	$(NODE) rm -rf src/lib/openapi/
	$(YARN) run orval
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

test-backend: ## Run backend tests
	$(PHP) bin/phpunit
.PHONY: test-backend

##
## Frontend application
## --------------------
##

test-frontend: ## Run frontend tests
	$(YARN) run test
.PHONY: test-frontend

##

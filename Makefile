.PHONY: help \
start \
stop \
web-enter \
node-enter \
key-generate \
migrate \
migrate-seed \
permission \
env-create \
composer-install \
npm-install \
npm-dev \
npm-build \
install \
uninstall

PROJECT_NAME = newsapi

# Alapértelmezett változók
PHP_CONTAINER_NAME = $(PROJECT_NAME)_php
NODE_CONTAINER_NAME = $(PROJECT_NAME)_node

# Alapértelmezett cél: segítség megjelenítése
.DEFAULT_GOAL := help

MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
PWD := $(dir $(MAKEPATH))

USER_ID=$(shell id -u)
GROUP_ID=$(shell id -g)

PHP_CONTAINER=docker exec -it -u $(USER_ID):$(GROUP_ID) $(PHP_CONTAINER_NAME)
NODE_CONTAINER=docker run -it -u $(USER_ID):$(GROUP_ID) -w /application -v $(PWD):/application $(NODE_CONTAINER_NAME)

help: ## Segítség megjelenítése
	@echo "Lehetőségek:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

start: ## Konténerek elindítása
	docker-compose up -d

stop: ## Konténerek leállítása
	docker-compose down

web-enter: ## Belépés a web konténerbe
	$(PHP_CONTAINER) bash

node-enter: ## Belépés a node konténerbe
	docker exec -it $(NODE_CONTAINER_NAME) bash

permission: ## Jogosultságok beállítása
	sudo chmod -R 777 storage
	sudo chmod -R 777 bootstrap

env-create: ## .env létrehozása
	cp -f .env.example .env

npm-install: ## Run npm install
	$(NODE_CONTAINER) npm install

npm-dev: ## Run npm run dev
	$(NODE_CONTAINER) npm run dev

npm-build: ## Run npm run build
	$(NODE_CONTAINER) npm run build

composer-install: ## composer install
	$(PHP_CONTAINER) git config --global --add safe.directory /var/www/html || true
	$(PHP_CONTAINER) composer install

key-generate: ## Alkalmazás kulcs generálása
	$(PHP_CONTAINER) php artisan key:generate

migrate: ## Adatbázis migrálása
	$(PHP_CONTAINER) php artisan migrate

migrate-seed: ## Adatbázis migrálása és seed
	$(PHP_CONTAINER) php artisan migrate --seed

migrate-refresh: ## Adatbázis frissítése
	$(PHP_CONTAINER) php artisan migrate:refres --seed

uninstall: ## Rendszer törlése
	$(PHP_CONTAINER) php artisan migrate:reset
	rm -f .env
	rm -f public/keycloak.json
	rm -f storage/logs/*.log
	rm -f storage/app/public/*
	rm -rf storage/framework/cache/data/*
	rm -rf vendor
	rm -rf node_modules

test: ## Run phpunit test
	$(PHP_CONTAINER) ./vendor/bin/phpunit --testdox --configuration phpunit.xml

install: start env-create composer-install key-generate npm-build permission ## Rendszer telepítése

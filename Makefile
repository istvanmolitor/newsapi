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

# Alapértelmezett változók
PHP_CONTAINER = newsapi_php
NODE_CONTAINER = newsapi_node

# Alapértelmezett cél: segítség megjelenítése
.DEFAULT_GOAL := help

MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
PWD := $(dir $(MAKEPATH))

USER_ID=$(shell id -u)
GROUP_ID=$(shell id -g)

NODE=docker run -it -u $(USER_ID):$(GROUP_ID) -w /application -v $(PWD):/application $(NODE_CONTAINER)

help: ## Segítség megjelenítése
	@echo "Lehetőségek:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

start: ## Konténerek elindítása
	docker-compose up -d

stop: ## Konténerek leállítása
	docker-compose down

web-enter: ## Belépés a web konténerbe
	docker exec -it $(PHP_CONTAINER) bash

node-enter: ## Belépés a node konténerbe
	docker exec -it $(NODE_CONTAINER) bash

key-generate: ## Alkalmazás kulcs generálása
	docker exec -it $(PHP_CONTAINER) php artisan key:generate

migrate: ## Adatbázis migrálása
	docker exec -it $(PHP_CONTAINER) php artisan migrate

migrate-seed: ## Adatbázis migrálása és seed
	docker exec -it $(PHP_CONTAINER) php artisan migrate --seed

migrate-refresh: ## Adatbázis frissítése
	docker exec -it $(PHP_CONTAINER) php artisan migrate:refres --seed

permission: ## Jogosultságok beállítása
	sudo chmod -R 777 storage
	sudo chmod -R 777 bootstrap

env-create: ## .env létrehozása
	cp .env.example .env

composer-install: ## composer install
	docker exec -it $(PHP_CONTAINER) git config --global --add safe.directory /var/www/html || true
	docker exec -it $(PHP_CONTAINER) composer install

npm-install: ## Run npm install
	$(NODE) npm install

npm-dev: ## Run npm run dev
	$(NODE) npm run dev

npm-build: ## Run npm run build
	$(NODE) npm run build

install: start env-create composer-install key-generate npm-build permission ## Rendszer telepítése

uninstall: ## Rendszer törlése
	docker exec -it $(PHP_CONTAINER) php artisan migrate:reset
	rm -f .env
	rm -f public/keycloak.json
	rm -f storage/logs/*.log
	rm -f storage/app/public/*
	rm -rf storage/framework/cache/data/*
	rm -rf vendor
	rm -rf node_modules

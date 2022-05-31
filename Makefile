SHELL := /bin/bash
install: export APP_ENV=dev
install:
	docker-compose up -d
	composer install
.PHONY: install

init-db: export APP_ENV=dev
init-db:
	symfony console doctrine:database:drop --if-exists --force
	symfony console doctrine:database:create
	symfony console doctrine:migration:migrate --no-interaction
	symfony console doctrine:fixtures:load --no-interaction
.PHONY: init-db

start: export APP_ENV=dev
start:
	symfony serve -d
.PHONY: start

tests: export APP_ENV=test
tests: reset-test
	symfony php bin/phpunit
.PHONY: tests

reset-test: export APP_ENV=test
reset-test:
	symfony console doctrine:database:drop --if-exists --force
	symfony console doctrine:database:create
	symfony console doctrine:migration:migrate --no-interaction
	symfony console doctrine:fixtures:load --no-interaction
.PHONY: reset-test




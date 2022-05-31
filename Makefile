SHELL := /bin/bash

install: export APP_ENV=dev
install:
	docker-compose up -d
	composer install
	symfony console d:d:c
	symfony console d:m:m --no-interaction
	symfony console d:f:l --no-interaction
.PHONY: install

start: export APP_ENV=dev
start:
	symfony serve -d
.PHONY: start

fixtures: export APP_ENV=dev
fixtures:
	symfony console d:f:l --no-interaction
.PHONY: fixtures

stop: export APP_ENV=dev
stop:
	symfony server:stop
.PHONY: stop

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




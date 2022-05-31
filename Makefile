SHELL := /bin/bash

install: export APP_ENV=dev
install:
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
	bin/phpunit
.PHONY: tests

reset-test: export APP_ENV=test
reset-test:
	symfony console d:d:d --if-exists --force
	symfony console d:d:c
	symfony console d:m:m --no-interaction
	symfony console d:f:l --no-interaction
.PHONY: reset-test


#!make
include .env
ENV=.env
ifneq (,$(wildcard ./.env.local))
    include .env.local
    ENV=.env.local
    export
endif
DC=docker-compose
DCR=${DC} run --rm -v ${PWD}:${DOCKER_APP_PATH} -w ${DOCKER_APP_PATH} --no-deps
RFR=sudo chown -R ${USER}:${USER} ./

ps:
	${DC} ps

init:
	${DCR} php composer install
	${DCR} node yarn install --force

up:
	${DC} --env-file ${ENV} up -d
	${DC} ps

up.build:
	${DC} --env-file ${ENV} up -d --build

down:
	${DC} down

down.clear:
	${DC} down -v --remove-orphans

restart: down up

reset-file-rights:
	${RFR}

# Example: make composer cmd=dump-autoload
composer:
	${DCR} php composer $(cmd)
	${RFR}

# Example: make composer cmd=make:migration
console:
	${DCR} php bin/console $(cmd)
	${RFR}

yarn:
	${DCR} node yarn $(cmd)
	${RFR}

phpunit:
	${DCR} php bin/phpunit $(cmd)

# Example: make fix-cs flags="--dry-run"
fix.cs:
	${DCR} php vendor/bin/php-cs-fixer fix ./src $(flags)
	${DCR} php vendor/bin/php-cs-fixer fix ./tests $(flags)

phpstan:
	${DCR} php vendor/bin/phpstan analyse --xdebug -c phpstan.neon

db.migrate:
	${DCR} php bin/console d:s:u --force
	${DCR} php bin/console d:f:l --no-interaction


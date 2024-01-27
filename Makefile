phpunit:
	vendor/bin/phpunit

phpspec:
	vendor/bin/phpspec run --ansi --no-interaction -f dot

phpstan:
	vendor/bin/phpstan analyse

psalm:
	vendor/bin/psalm

behat-js:
	APP_ENV=test vendor/bin/behat --colors --strict --no-interaction -vvv -f progress

install:
	composer install --no-interaction --no-scripts

backend:
	tests/Application/bin/console sylius:install --no-interaction
	tests/Application/bin/console sylius:fixtures:load default --no-interaction

frontend:
	(cd tests/Application && yarn install --pure-lockfile)
	(cd tests/Application && GULP_ENV=prod yarn build)

behat:
	APP_ENV=test vendor/bin/behat --colors --strict --no-interaction -vvv -f progress

init: install backend frontend

ci: init phpstan psalm phpunit phpspec behat

integration: init phpunit behat

static: install phpspec phpstan psalm

bash: ## login into container
	docker-compose exec app bash
test: ## Run tests
	docker-compose exec app bin/phpunit --colors=always --coverage-text --coverage-html=build/coverage

build: ## Builds the Docker images
	docker-compose build --pull --no-cache

up: ## Start containers in detached mode (no logs)
	docker-compose up --detach

stop: ## Stop containers
	docker-compose app

remove: ## Remove containers
	docker-compose stop
	docker-compose rm -fv app

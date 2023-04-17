include .env
DCF = docker-compose.yml

.PHONY: node-enter
node-enter:
	@docker compose --file=$(DCF) run --rm node /bin/sh

.PHONY: npm-i
npm-i:
	@docker compose --file=$(DCF) run --rm node /bin/sh -c "npm i --save-dev"

.PHONY: npm-watch
npm-watch:
	@docker compose --file=$(DCF) run --rm node /bin/sh -c "npm run watch"

.PHONY: php-enter
php-enter:
	@docker compose --file=$(DCF) exec -it php /bin/sh

.PHONY: composer-i
composer-i:
	@docker compose --file=$(DCF) exec php /bin/sh -c "composer install"

.PHONY: restore-db
restore-db:
	@docker compose --file=$(DCF) exec db /bin/sh -c "/usr/bin/mysql -u root --password=$(MYSQL_ROOT_PASSWORD) -e 'DROP DATABASE $(MYSQL_DATABASE);'"
	@docker compose --file=$(DCF) exec db /bin/sh -c "/usr/bin/mysql -u root --password=$(MYSQL_ROOT_PASSWORD) -e 'CREATE DATABASE $(MYSQL_DATABASE) charset utf8 collate utf8_general_ci;'"
	@cat dump.sql | pv | docker compose --file=$(DCF) exec -T db /usr/bin/mysql -u root --password=$(MYSQL_ROOT_PASSWORD) $(MYSQL_DATABASE)

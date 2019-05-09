include .env

# Project management
####################
init: rebuild
	cp .docker/resources/nginx/leepeuker.de.conf.${APP_ENV}.dist .docker/resources/nginx/leepeuker.de.conf
	# make db_import


# Container management
######################
up:
	docker-compose -f .docker/docker-compose.yaml up -d

down:
	docker-compose -f .docker/docker-compose.yaml down

reup: down up

rebuild: down
	docker-compose -f .docker/docker-compose.yaml build
	
	
# Composer
##########
composer_install:
	docker exec leepeuker-php bash -c "composer install"

composer_update:
	docker exec psafeed-leepeuker bash -c "composer update"


# Laravel
#########
generate_app_key:
	docker exec leepeuker-php bash -c "php artisan key:generate"


# Database
##########
db_import:
	# get dump.sql
	docker cp .docker/tmp/dump.sql webproxy-mysql:/tmp/dump.sql
	docker exec webproxy-mysql bash -c 'mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) < /tmp/dump.sql'
	docker exec webproxy-mysql bash -c 'rm /tmp/dump.sql'

db_export:
	docker exec webproxy-mysql bash -c 'mysqldump --databases --add-drop-database -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) > /tmp/dump.sql'
	docker cp webproxy-mysql:/tmp/dump.sql .docker/tmp/dump-new.sql
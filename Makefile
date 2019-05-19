include .env


# Container interaction
#######################
connect_php_bash:
	docker exec -it php bash

connect_nginx_shell:
	docker exec -it nginx sh

connect_mysql_cli:
	docker exec -it mysql sh -c "mysql -u${DB_USERNAME} -p${DB_PASSWORD} ${DB_DATABASE}"

logs_php:
	docker logs -f php

logs_nginx:
	docker logs -f nginx

	

# Composer
##########
composer_install:
	docker exec php bash -c "composer install"

composer_update:
	docker exec php bash -c "composer update"


# Laravel
#########
artisan_generate_key:
	docker exec php bash -c "php artisan key:generate"
	
artisan_link_storage:
	docker exec php bash -c "php artisan storage:link"


# Database
##########
db_import:
	docker cp $(FILE) mysql:/tmp/dump.sql
	docker exec mysql bash -c 'mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) < /tmp/dump.sql'
	docker exec mysql bash -c 'rm /tmp/dump.sql'

db_export:
	docker exec mysql bash -c 'mysqldump --databases --add-drop-database -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) > /tmp/dump.sql'
	docker cp mysql:/tmp/dump.sql ./tmp/leepeuker-`date +%Y-%m-%d-%H-%M-%S`.sql
	docker exec mysql bash -c 'rm /tmp/dump.sql'
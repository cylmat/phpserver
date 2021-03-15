SHELL := /bin/bash

###
# Compose
###
compose-fpm:
	docker-compose -f docker-compose-fpm.yml up --build -d

compose-srv:
	docker-compose -f docker-compose-srv.yml up --build -d

###
# Check
###
check-fpm:
	docker exec fpm bash -c "php -f '/var/www/shell/check-fpm.php'"

###
# list
###
image-list:
	docker image ls

container-list:
	docker container ls

container-ps:
	docker container ps -a
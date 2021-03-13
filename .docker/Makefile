SHELL := /bin/bash

###
# Compose
###
compose-fpm:
	docker-compose build -f docker-compose-fpm.yml --pull --no-cache

compose-srv:
	docker-compose build -f docker-compose-srv.yml --pull --no-cache

###
# list
###
image-list:
	docker image ls

container-list:
	docker container ls

container_ps:
	docker container ps -a

###
# Servers
###
check-servers:
	docker container
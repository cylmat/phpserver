SHELL := /bin/bash

###
# Compose
###
compose-fpm:
	docker-compose -f docker-compose-fpm.yml up --build -d

compose-srv:
	docker-compose -f docker-compose-srv.yml up --build -d

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
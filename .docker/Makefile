SHELL := /bin/bash

###
# Compose
###
compose-db:
	docker-compose -f docker-compose-db.yml --env ../.env up --build -d

compose-fpm:
	docker-compose -f docker-compose-fpm.yml --env ../.env up --build -d

compose-proxy:
	docker-compose -f docker-compose-proxy.yml --env ../.env up --build -d

compose-srv:
	docker-compose -f docker-compose-srv.yml --env ../.env up --build -d

###
# Check
###
check-db:
	# @todo
	#docker exec fpm bash -c "php -f '/var/www/shell/check-db.php'"

	docker logs --tail 1000 -f mysql
	#docker exec mysql bash -c "MYSQL_PWD=pass mysql -A mydb -u user -e 'SHOW TABLES'"
	docker exec mysql bash -c "service mysql status"

check-fpm:
	docker exec fpm bash -c "php -f '/var/www/shell/check-fpm.php'"

check-proxy:
	docker exec haproxy bash -c "apt-get update && apt-get install -y curl"
	docker exec haproxy bash -c "curl localhost:80/check-proxy.php"

# need fpm running
check-srv:
	docker exec apache bash -c "apt-get update && apt-get install -y curl"
	docker exec nginx bash -c "apt-get update && apt-get install -y curl"
	docker exec apache bash -c "curl localhost:80/check-srv.php"
	docker exec nginx bash -c "curl localhost:80/check-srv.php"

###
# list
###
image-list:
	docker image ls

container-list:
	docker container ls

container-ps:
	docker container ps -a
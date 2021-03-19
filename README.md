[![PhpServer](https://github.com/clymate/phpserver/actions/workflows/main.yml/badge.svg)](https://github.com/clymate/phpserver/actions/workflows/main.yml)

PHP Boilerplate
===
Functional installation of Php environment on Docker

Databases
---------
* DBA ext
* MariaDb 10.0
* Memcached 1.6
* Mysql 5.7
* PostgreSql 12.4
* Redis 6.0
* SQlite 3

Messages
--------
* Rabbitmq 3.8
* ZeroMq ext

Servers
-------
* Apache 2.4
* Nginx 1.17
* Php-fpm 7.4

## Proxies
* HAProxy 2.2
* Varnish 6.0

## Sample structure
               USER
                |
            Virtual IP
                |
     HAProxy1   -   HAProxy2 
    |     |           |      |
    |  Varnish1 - Varnish2   |
    |     |           |      |
     -> Server 1, 2, 3 ... <-
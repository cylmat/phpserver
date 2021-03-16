[![PhpServer](https://github.com/clymate/phpserver/actions/workflows/main.yml/badge.svg)](https://github.com/clymate/phpserver/actions/workflows/main.yml)

PHP Boilerplate
===
Installation fonctionnelle de l'environnement Php sur Docker

## Modules mis en places
* HAProxy
* Nginx
* Varnish

## Bases de données
* DBA
* MariaDb
* Memcached
* Mysql
* Redis
* SQlite

## Sample Structure
               USER
                |
            Virtual IP
                |
     HAProxy1   -   HAProxy2 
    |     |           |      |
    |  Varnish1 - Varnish2   |
    |     |           |      |
     -> Server 1, 2, 3 ... <-
[![PhpServer](https://github.com/cylmat/phpserver/actions/workflows/main.yml/badge.svg)](https://github.com/cylmat/phpserver/actions/workflows/main.yml)

PHP Boilerplate
===
Functional installation of Php environment on [Docker](https://www.docker.com)

Databases
---------
* [DBA Php ext](https://www.oracle.com/database/berkeley-db/db.html)
* [MariaDb 10.0](https://mariadb.org)
* [Memcached 1.6](https://memcached.org)
* [Mysql 5.7](https://www.mysql.com)
* [PostgreSql 12.4](https://www.postgresql.org)
* [Redis 6.0](https://redis.io)
* [SQlite 3](https://www.sqlite.org)

Messages
--------
* [Rabbitmq 3.8](https://www.rabbitmq.com)
* [ZeroMq ext](https://zeromq.org)

Servers
-------
* [Apache 2.4](https://httpd.apache.org)
* [Nginx 1.17](https://www.nginx.com)
* [Php-fpm 7.4](https://www.php.net/manual/fr/install.fpm.php)

Proxies
-------
* [HAProxy 2.2](http://www.haproxy.org)
* [Varnish 6.0](https://varnish-cache.org)

Sample structure
----------------
               USER
                |
            Virtual IP
                |
     HAProxy1   -   HAProxy2 
    |     |           |      |
    |  Varnish1 - Varnish2   |
    |     |           |      |
     -> Server 1, 2, 3 ... <-

#### Ref
* [cylmat/phpconfig](https://github.com/cylmat/phpconfig/)
* [cylmat/homeconfig](https://github.com/cylmat/homeconfig) 

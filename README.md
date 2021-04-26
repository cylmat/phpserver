[![PhpServer](https://github.com/cylmat/phpserver/actions/workflows/check.yml/badge.svg)](https://github.com/cylmat/phpserver/actions/workflows/check.yml)
[![License: Unlicense](https://img.shields.io/badge/license-Unlicense-blue.svg)](http://unlicense.org/)

PHP Server Boilerplate
===
Functional installation of Php environment using [Docker](https://www.docker.com).  

Usage
-----
Simply clone the repository and run it as a boilerplate for your project.
```
git clone --depth=1 https://github.com/cylmat/phpserver my_app
rm ./my_app/.git -rf 
```

Versions of servers:
---
**Servers**  
* [Apache 2.4](https://httpd.apache.org)
* [Nginx 1.17](https://www.nginx.com)
* [Php-fpm 7.4](https://www.php.net/manual/fr/install.fpm.php)

**Databases**  
* [DBA Php](https://www.oracle.com/database/berkeley-db/db.html)
* [MariaDb 10.0](https://mariadb.org)
* [Memcached 1.6](https://memcached.org)
* [Mysql 5.7](https://www.mysql.com)
* [PostgreSql 12.4](https://www.postgresql.org)
* [Redis 6.0](https://redis.io)
* [SQlite 3](https://www.sqlite.org)

**Messages**  
* [Rabbitmq 3.8](https://www.rabbitmq.com)
* [ZeroMq Php extension](https://zeromq.org)

**Proxies**  
* [HAProxy 2.2](http://www.haproxy.org)
* [Varnish 6.0](https://varnish-cache.org)

## See also
* [cylmat/homeconfig](https://github.com/cylmat/homeconfig) - Home configuration with custom prompts and editors.
* [cylmat/phpconfig](https://github.com/cylmat/phpconfig/) - PHP dev environment, specs and testing frameworks.

License
---
PHPServer is licensed under Unlicense.

This is free and unencumbered software released into the public domain.

Anyone is free to copy, modify, publish, use, compile, sell, or
distribute this software, either in source code form or as a compiled
binary, for any purpose, commercial or non-commercial, and by any
means.

In jurisdictions that recognize copyright laws, the author or authors
of this software dedicate any and all copyright interest in the
software to the public domain. We make this dedication for the benefit
of the public at large and to the detriment of our heirs and
successors. We intend this dedication to be an overt act of
relinquishment in perpetuity of all present and future rights to this
software under copyright law.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

For more information, please refer to <https://unlicense.org>

ARG BUILD=full

FROM phpdockerio/php74-fpm:latest AS SMALL
# Ubuntu (debian) 20.04 LTS (Focal Fossa)

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install
RUN apt-get update && BUILD=1
RUN apt-get install -y libzip4 libzip-dev lbzip2 zlib1g-dev libicu-dev libxml2-dev
RUN apt-get install -y apt-utils bzip2 curl gnupg gnupg2 netcat wget g++ vim zip

# Php 7.4 ext
RUN apt-get install -y php7.4-apcu php7.4-dev php7.4-ds php7.4-intl php7.4-mbstring php7.4-mcrypt php7.4-psr php7.4-soap

# Amqp
RUN apt-get install -y php7.4-amqp libczmq4 php7.4-zmq

FROM SMALL AS FULL

###################################
#             DATABASES           #
###################################

# Db
RUN apt-get install -y sqlite3 php7.4-mysql php7.4-pgsql php7.4-sqlite
RUN apt-get install -y php7.4-memcached php7.4-mongodb php7.4-redis

# Php-Redis: https://github.com/phpredis/phpredis/
RUN echo 'yes' | pecl install redis-5.3.1

# DBA BERKELEY
RUN apt-get install -y php7.4-dba

# ODBC
# /etc/odbc.ini not needed
# config in /etc/odbcinst.ini
# WORKDIR /tmp
# RUN apt-get install -y odbcinst1debian2 libodbc1
# RUN wget https://dev.mysql.com/get/Downloads/Connector-ODBC/8.0/mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb
# RUN md5sum mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb
# RUN test "$(md5sum mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb)" = "62e280b8d8e5d3531c6de2b57c37b1a5  mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb"
# RUN dpkg -i mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb 
# RUN apt-get install -y php7.4-odbc

########################
# Coverage & profiling #
########################

RUN apt-get install -y php7.4-pcov php7.4-phpdbg php7.4-xdebug

# Coverage
# RUN pecl install pcov

# XHPROF (tideways)
RUN apt-get install -y graphviz php7.4-tideways
# WORKDIR /tmp
# RUN wget https://github.com/tideways/php-xhprof-extension/archive/master.zip && unzip master.zip && rm master.zip
# WORKDIR /tmp/php-xhprof-extension-master 
# RUN phpize && ./configure
# RUN make && make install
# RUN echo "extension=tideways_xhprof.so" > /etc/php/7.4/fpm/conf.d/xhprof.ini

# Apache bench
RUN apt-get install -y apache2-utils

# clean
RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /var/www
FROM phpdockerio/php74-fpm:latest
# Ubuntu (debian) 20.04 LTS (Focal Fossa)

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install
RUN apt-get update && BUILD=1
RUN apt-get install -y libzip4 libzip-dev lbzip2 zlib1g-dev libicu-dev libxml2-dev
RUN apt-get install -y apt-utils curl gnupg gnupg2 bzip2 sqlite3 vim netcat wget g++ zip

# php 7.4 ext
RUN apt-get install -y php7.4-dev php7.4-mysql php7.4-redis php7.4-intl php7.4-memcached php7.4-soap php7.4-pgsql php7.4-sqlite 

###################################
#             DATABASES           #
###################################

# DBA BERKELEY
RUN apt-get install -y php7.4-dba

# ODBC inst
#/etc/odbc.ini not needed
#/etc/odbcinst.ini
WORKDIR /tmp
RUN apt-get install -y odbcinst1debian2 libodbc1
RUN wget https://dev.mysql.com/get/Downloads/Connector-ODBC/8.0/mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb
RUN md5sum mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb
RUN test "$(md5sum mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb)" = "62e280b8d8e5d3531c6de2b57c37b1a5  mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb"
RUN dpkg -i mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb 
RUN apt-get install -y php7.4-odbc

# Php-Redis: https://github.com/phpredis/phpredis/
RUN echo 'yes' | pecl install redis-5.3.1

###########################
#         BENCH           #
###########################

# Apache bench
RUN apt-get install -y apache2-utils

# clean
RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /var/www
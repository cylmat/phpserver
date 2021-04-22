#!/bin/bash

#############
# MySql 5.7 #
#############
apt-get install -y debconf-utils gnupg lsb-release 
# Use: debconf-get-selections | grep mysql
wget https://dev.mysql.com/get/mysql-apt-config_0.8.15-1_all.deb
# check md5sum mysql-apt-config_0.8.15-1_all.deb = "4126e44e0867531a4724ab0c21d1d645  mysql-apt-config_0.8.15-1_all.deb"
# Configuration
# Debian: "debian, buster, Ok"
export ROOT_PASSWORD="root"
echo "mysql-apt-config mysql-apt-config/repo-distro select debian" | /usr/bin/debconf-set-selections
echo "mysql-apt-config mysql-apt-config/repo-codename select buster" | /usr/bin/debconf-set-selections
echo "mysql-apt-config mysql-apt-config/repo-url string http://repo.mysql.com/apt/" | /usr/bin/debconf-set-selections
echo "mysql-apt-config mysql-apt-config/select-product select Ok" | /usr/bin/debconf-set-selections
echo "mysql-apt-config mysql-apt-config/unsupported-platform select abort" | /usr/bin/debconf-set-selections
echo "mysql-apt-config mysql-apt-config/select-tools select Enable" | /usr/bin/debconf-set-selections
echo "mysql-apt-config mysql-apt-config/select-server select mysql-5.7" | /usr/bin/debconf-set-selections
echo "mysql-community-server mysql-community-server/root-pass password $ROOT_PASSWORD" | /usr/bin/debconf-set-selections
echo "mysql-community-server mysql-community-server/re-root-pass password $ROOT_PASSWORD" | /usr/bin/debconf-set-selections
echo "mysql-community-server mysql-community-server/remove-data-dir boolean false" | /usr/bin/debconf-set-selections
echo "mysql-community-server mysql-community-server/data-dir note" | /usr/bin/debconf-set-selections
# Installation
export DEBIAN_FRONTEND=noninteractive
echo -e " - Installation de mysql - "
echo '4' | dpkg --install mysql-apt-config_0.8.15-1_all.deb
apt-get update
apt-get --yes install mysql-server

########
# ODBC #
########
# /etc/odbc.ini not needed
# config in /etc/odbcinst.ini
# WORKDIR /tmp
# RUN apt-get install -y odbcinst1debian2 libodbc1
# RUN wget https://dev.mysql.com/get/Downloads/Connector-ODBC/8.0/mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb
# RUN md5sum mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb
# RUN test "$(md5sum mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb)" = "62e280b8d8e5d3531c6de2b57c37b1a5  mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb"
# RUN dpkg -i mysql-connector-odbc_8.0.21-1ubuntu20.04_amd64.deb 
# RUN apt-get install -y php7.4-odbc

#############
# Rabbitmq with erlang 22
#############
# https://www.rabbitmq.com/install-debian.html#apt
apt-get install -y curl gnupg apt-transport-https
curl -fsSL https://github.com/rabbitmq/signing-keys/releases/download/2.0/rabbitmq-release-signing-key.asc | apt-key add -
echo "deb https://dl.bintray.com/rabbitmq-erlang/debian buster erlang" > /etc/apt/sources.list.d/bintray.rabbitmq.list
echo "deb https://dl.bintray.com/rabbitmq/debian buster main" >> /etc/apt/sources.list.d/bintray.rabbitmq.list
apt-get update -y
# Amqp php ext
apt-get install -y rabbitmq-server --fix-missing
apt-get install -y amqp-tools librabbitmq-dev
echo "yes" | pecl install amqp
echo "extension=amqp.so" > /usr/local/etc/php/conf.d/amqp.ini

##############
# PostGreSql #
##############
apt-get install -y postgresql-11 postgresql-server-dev-11
# PGadmin
sed -ie "s/#listen_addresses = 'localhost'/listen_addresses = '*'/" /etc/postgresql/11/main/postgresql.conf
sed -ie "s#127.0.0.1/32#0.0.0.0/0#" /etc/postgresql/11/main/pg_hba.conf

##########
# XHPROF #
##########
# WORKDIR /tmp
# RUN wget https://github.com/tideways/php-xhprof-extension/archive/master.zip && unzip master.zip && rm master.zip
# WORKDIR /tmp/php-xhprof-extension-master 
# RUN phpize && ./configure
# RUN make && make install
# RUN echo "extension=tideways_xhprof.so" > /etc/php/7.4/fpm/conf.d/xhprof.ini
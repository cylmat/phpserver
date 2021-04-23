# From docker-compose
#ARG buildno
#ARG servername
#RUN echo "Build number: $buildno from $servername"

###############
# SMALL IMAGE #
###############

FROM phpdockerio/php74-fpm:latest AS SMALL
# Ubuntu (debian) 20.04 LTS (Focal Fossa)

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install
RUN apt-get update && BUILD=1
RUN apt-get install -y libzip4 libzip-dev lbzip2 zlib1g-dev libicu-dev libxml2-dev
RUN apt-get install -y apt-utils bzip2 curl gnupg gnupg2 netcat wget g++ vim zip

# Php 7.4 ext
RUN apt-get install -y php7.4-apcu php7.4-dev php7.4-ds php7.4-intl php7.4-mbstring php7.4-mcrypt php7.4-psr

# Sqlite
RUN apt-get install -y sqlite3 php7.4-sqlite

# Amqp
RUN apt-get install -y php7.4-amqp libczmq4 php7.4-zmq

############
# DB IMAGE #
############

FROM SMALL AS DB

# Common
RUN apt-get install -y build-essential busybox software-properties-common

#############
# Databases #
#############

# Db
RUN apt-get install -y php7.4-mysql php7.4-pgsql php7.4-mongodb php7.4-redis
RUN apt-get install -y libmemcached-dev memcached php7.4-memcached

# Php-Redis: https://github.com/phpredis/phpredis/
RUN pecl channel-update pecl.php.net
RUN echo 'yes' | pecl install redis-5.3.1

# DBA Berkeley abstraction
RUN apt-get install -y php7.4-dba

##############
# FULL IMAGE #
##############

FROM DB AS FULL

##############
# Useful EXT #
##############

RUN apt-get install -y php7.4-soap

########################
# Coverage & Profiling #
########################

RUN apt-get install -y php7.4-pcov php7.4-phpdbg php7.4-xdebug

# XHPROF (tideways)
RUN apt-get install -y graphviz php7.4-tideways

# Apache bench
RUN apt-get install -y apache2-utils

# clean
RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
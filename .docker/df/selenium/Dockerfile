#################
# SELENIUM/BASE #
#################
# FROM ubuntu:16.04
# MAINTAINER Selenium <selenium-developers@googlegroups.com>

# #================================================
# # Customize sources for apt-get
# #================================================
# RUN  echo "deb http://archive.ubuntu.com/ubuntu xenial main universe\n" > /etc/apt/sources.list \
#   && echo "deb http://archive.ubuntu.com/ubuntu xenial-updates main universe\n" >> /etc/apt/sources.list \
#   && echo "deb http://security.ubuntu.com/ubuntu xenial-security main universe\n" >> /etc/apt/sources.list

# #========================
# # Miscellaneous packages
# # Includes minimal runtime used for executing non GUI Java programs
# #========================
# RUN apt-get update -qqy \
#   && apt-get -qqy --no-install-recommends install \
#     bzip2 \
#     ca-certificates \
#     openjdk-8-jre-headless \
#     sudo \
#     unzip \
#     wget \
#   && rm -rf /var/lib/apt/lists/* \
#   && sed -i 's/securerandom\.source=file:\/dev\/random/securerandom\.source=file:\/dev\/urandom/' ./usr/lib/jvm/java-8-openjdk-amd64/jre/lib/security/java.security

# #==========
# # Selenium
# #==========
# RUN  mkdir -p /opt/selenium \
#   && wget --no-verbose https://selenium-release.storage.googleapis.com/3.0-beta4/selenium-server-standalone-3.0.0-beta4.jar -O /opt/selenium/selenium-server-standalone.jar

# #========================================
# # Add normal user with passwordless sudo
# #========================================
# RUN sudo useradd seluser --shell /bin/bash --create-home \
#   && sudo usermod -a -G sudo seluser \
#   && echo 'ALL ALL = (ALL) NOPASSWD: ALL' >> /etc/sudoers \
#   && echo 'seluser:secret' | chpasswd


################################################
# nkovacs/selenium-standalone-phantomjs:latest #
################################################
FROM selenium/base:latest

USER root

ENV DEBIAN_FRONTEND noninteractive
ENV DEBCONF_NONINTERACTIVE_SEEN true

#===================
# Timezone settings
# Possible alternative: https://github.com/docker/docker/issues/3359#issuecomment-32150214
#===================
ENV TZ "US/Pacific"
RUN echo "${TZ}" > /etc/timezone \
  && dpkg-reconfigure --frontend noninteractive tzdata

#==============
# PhantomJS
#==============
RUN apt-get update -y
RUN apt-get install bzip2 libfreetype6 libfontconfig1  -y
RUN wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-2.1.1-linux-x86_64.tar.bz2
RUN tar -xvjf phantomjs-2.1.1-linux-x86_64.tar.bz2 && rm phantomjs-2.1.1-linux-x86_64.tar.bz2
RUN mv /phantomjs-2.1.1-linux-x86_64 /usr/local/phantomjs-2.1.1-linux-x86_64
RUN ln -s /usr/local/phantomjs-2.1.1-linux-x86_64/bin/phantomjs /usr/local/bin/phantomjs
#ADD phantomjs /usr/local/bin/phantomjs
RUN chmod a+x /usr/local/bin/phantomjs

#============================
# Some configuration options
#============================
ENV SCREEN_WIDTH 1360
ENV SCREEN_HEIGHT 1020
ENV SCREEN_DEPTH 24
ENV DISPLAY :99.0

USER root

#====================================
# Scripts to run Selenium Standalone
#====================================
COPY entry_point.sh /opt/bin/entry_point.sh
RUN chmod +x /opt/bin/entry_point.sh

# phantomjs will write its log here
RUN mkdir -p /var/log/selenium && chmod a+w /var/log/selenium

USER seluser

EXPOSE 4444

CMD ["/opt/bin/entry_point.sh"]






#
# PhantomJs
#
# FROM debian

# RUN apt update && apt install -y curl wget bzip2 gcc build-essential chrpath  libssl-dev  \
  # libxft-dev libfreetype6-dev libfreetype6 libfontconfig1-dev libfontconfig1
# RUN apt install -y libfontconfig
# #n
# # RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -
# # RUN apt install -y nodejs
# # RUN npm --version
# ENV OPENSSL_CONF=/etc/ssl/
# #export OPENSSL_CONF=/etc/ssl/

# WORKDIR /usr/local/share

# RUN wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-1.9.7-linux-x86_64.tar.bz2
# RUN bzip2 -dk phantomjs-1.9.7-linux-x86_64.tar.bz2
# RUN tar -xf phantomjs-1.9.7-linux-x86_64.tar
# RUN cp phantomjs-1.9.7-linux-x86_64/bin/phantomjs /usr/local/bin

# RUN phantomjs --version```
# #
# # PhantomJsfor downgrade to version 1.9.7
# #
# FROM nkovacs/selenium-standalone-phantomjs
# #ENV OPENSSL_CONF=/etc/ssl/
# #export OPENSSL_CONF=/etc/ssl/
# #WORKDIR /usr/local/bin
# WORKDIR /tmp
# RUN wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-1.9.7-linux-x86_64.tar.bz2
# RUN bzip2 -dk phantomjs-1.9.7-linux-x86_64.tar.bz2
# RUN tar -xf phantomjs-1.9.7-linux-x86_64.tar
# RUN sudo mv /usr/local/bin/phantomjs /usr/local/bin/bak_phantomjs
# RUN sudo cp phantomjs-1.9.7-linux-x86_64/bin/phantomjs /usr/local/bin
# WORKDIR /
# RUN phantomjs --version






#p
#RUN npm install phantomjs-prebuilt 
# /tmp/phantomjs/phantomjs-2.1.1-linux-x86_64.tar.bz2
#RUN ln -s /var/www/html/node_modules/phantomjs-prebuilt/bin/phantomjs /usr/local/bin/phantomjs
#RUN cp /usr/local/share/node_modules/phantomjs-prebuilt/bin/phantomjs /usr/local/bin/phantomjs



#https://jonczyk.me/2017/02/18/first-attempt-selenium-phpunit/
#docker run -it --name web -d phph

# RUN apt-get update -y 
# RUN apt-get install -y wget build-essential chrpath libssl-dev libxft-dev libfreetype6-dev libfreetype6 libfontconfig1-dev libfontconfig1
# RUN curl https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/
#s
#RUN wget https://bit.ly/2TlkRyu


#https://github.com/Medium/phantomjs/releases/download/v2.1.1/phantomjs-2.1.1-linux-x86_64.tar.bz2
#RUN wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-2.1.1-linux-x86_64.tar.bz2
#RUN tar xvjf phantomjs-2.1.1-linux-x86_64.tar.bz2 -C /usr/local/share/
#RUN ln -s /usr/local/share/phantomjs-2.1.1-linux-x86_64/bin/phantomjs /usr/local/bin/
#COPY ./node-v12.14.1.tar.gz /usr/local/share/installer
#RUN tar xvzf /usr/local/share/installer/node-v12.14.1.tar.gz -C /usr/local/share
#RUN ln -s /usr/local/share

#composer require --dev facebook/webdriver phpunit/phpunit
#npm install --save-dev phantomjs
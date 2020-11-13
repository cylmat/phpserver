#!/bin/bash

###########
# https://www.certeurope.fr/blog/guide-csr-certificat/
#
# CSR: certificat request (nom du serveur, entreprise, etc...)
# openssl req ...
#
# ex:
# openssl genrsa -des3 -out key private/SERVEUR.key -sha256 rsa:2048; -des3 (without pas)
# 
# Demande avec fichier de conf reçu du CA
# openssl-OI-SAN.cnf
# openssl req -new -out certeurope-seal-2048.csr -key certeurope-seal-2048.key -config [openssl-OI-Cachet.cnf]
# openssl req –in certeurope-seal-2048.csr – noout –text.
#
# pem: Privacy Enhanced Mail

cd /etc/ssl/

########## tools
# apt-get install -y net-tools && netstat -lpan | grep :443
#

##########################################################
# Create key
# without password (Common Name: <www-url-docker>)
openssl genrsa 2048 > private/docker.key 
openssl req -new -key private/docker.key -out certs/docker.csr 

########## certificat to sign
# Auto certificat key with pass, and certificat (pass: aaaa, Common: Cert_CA)
openssl genrsa -des3 4096 > private/ca.key 
openssl req -new -x509 -key private/ca.key -days 365 -out certs/ca.crt

# ######## Signing certificat
openssl x509 -req -in certs/docker.csr -out certs/docker.crt \
-CA certs/ca.crt -CAkey private/ca.key -CAcreateserial -CAserial ca.srl

#######################################################
# Quicker: both in one
# https://www.codeflow.site/fr/article/how-to-create-a-self-signed-ssl-certificate-for-nginx-in-ubuntu-18-04
#openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout private/docker.key -out certs/docker.crt

##########################################################
# Own cert let's encrypt
# https://letsencrypt.org/docs/certificates-for-localhost/
##########
#openssl req -x509 -out certs/docker.crt -keyout private/docker.key \
#  -newkey rsa:2048 -nodes -sha256 \
#  -subj '/CN=my_server_name' -extensions EXT -config <( \
#   printf "[dn]\nCN=my_server_name\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:my_server_name\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth")

###############
# verify values
###############
#openssl pkey -in private/docker.key -pubout -outform pem | sha256sum
#openssl x509 -in certs/docker.crt -pubkey -noout -outform pem | sha256sum
#openssl req -in certs/docker.csr -pubkey -noout -outform pem | sha256sum 

update-ca-certificates 
nginx -s reload
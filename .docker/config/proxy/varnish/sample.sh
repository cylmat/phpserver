#!/bin/bash

###############
# CLI samples #
###############
# varnish> ban req.url ~ "logo.*[.]png" 
# varnish> param.set prefer_ipv6 true
# varnish> stop

############################
#       LOGGING            #
############################
# $ varnishlog -g raw -b(ackend) -c(lient)
# $ varnishlog -d -q 'RespStatus == 503' -g request
# $ varnish-top/hist/stat
# $ varnishadm backend.list
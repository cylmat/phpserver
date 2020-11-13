#
# This is an example VCL file for Varnish.
#
# It does not do anything by default, delegating control to the
# builtin VCL. The builtin VCL is called when there is no explicit
# return statement.
#
# See the VCL chapters in the Users Guide at https://www.varnish-cache.org/docs/
# and https://www.varnish-cache.org/trac/wiki/VCLExamples for more examples.

# Marker to tell the VCL compiler that this VCL has been adapted to the
# new 4.0 format.

# tips
# https://varnish-cache.org/docs/6.5/users-guide/increasing-your-hitrate.html

vcl 4.0;

###########
# LOADING VCL
###########
include "/etc/varnish/https.vcl";
#vcl.label my_included_vcl_label my_included_vcl

###############
# CLI samples #
###############
# varnish> ban req.url ~ "logo.*[.]png" 
# varnish> param.set prefer_ipv6 true
# varnish> stop

#############
# ref: https://varnish-cache.org/docs/6.5/reference/varnishd.html#list-of-parameters
#############

# Default backend definition. Set this to point to your content server.
# backend default none;
backend default {
    .host = "nginx"; 
    .port = "82";
    .connect_timeout = 6000s;
    .first_byte_timeout = 6000s;
    .between_bytes_timeout = 6000s;
}

backend default_ssl {
    .host = "nginx"; 
    .port = "82";
}

# sample
backend back_java_sample { 
    .host = "127.0.8.1";
    .port = "8999";

    #health check
    .probe = { 
        .url = "/"; # send a get request to /
        .timeout = 1s;
        .interval = 5s;
        .window = 5; # max try
        .threshold = 3; # considéré valide if 3 on 5 are ok
    }
}

#########################
#       VCL INIT
#########################
import directors;   # load the directors

sub vcl_init {
    new bar_director = directors.round_robin(); # create a group of backends
    bar_director.add_backend(default);
    bar_director.add_backend(back_java_sample);
}


#####################
# access control list
#####################
acl acl_local_sample {
    "localhost";         // myself
    "192.0.2.0"/24;      // and everyone on the local network
    ! "192.0.2.23";      // except for the dialin router
}

# specific purge
acl acl_purge {
        "localhost";
        "192.168.55.0"/24;
}

############
# subroutines
# call <subroutine>;
#
# ref: https://varnish-cache.org/docs/6.5/users-guide/vcl-built-in-subs.html
#
# CLIENT SIDE #
#
# vcl_recv      - at the beginning of a request
# vcl_hash      - after recv
# vcl_pipe
# vcl_pass      - pass request
# vcl_purge
# vcl_miss      - after cache lookup if not found
# vcl_hit       - when cache lookup successful
# vcl_deliver   - before any object except a vcl_synth result is delivered to the client
# vcl_synth     - deliver synthetic object (never enter the cache)
#      return fail, synth(code, reason), restart, pass, pipe, hash, purge, vcl(...), lookup
#
# BACKEND SIDE #
#
# vcl_backend_fetch    - before sending the backend request
# vcl_backend_response - after the response headers have been successfully retrieved from the backend
# vcl_backend_error    - called if we fail the backend fetch or if max_retries has been exceeded
#      return fail, abandon, fetch, error(code, reason), deliver, retry
# on 304 (not modified)
# beresp.ttl / beresp.grace / beresp.keep
#
# VCL LOAD / DISCARD #
#
# vcl_init - return ok, fail
# vcl_fini - return ok
#
# OBJECTS #
#
# req - request object
# resp
# bereq - back-end request
# beresp - back-end response
# obj - stored in cache, Read-Only
############


###########################
#     1. CLIENT SIDE
###########################
# Happens before we check if we have this in cache already.
#
sub vcl_recv {
    # Typically you clean up the request here, removing cookies you don't need,
    # rewriting the request, etc.
    
    #set req.http.X-Forwarded-Proto = "https";

    # non-standard micro$oft
    #set req.http.X-Forwarded-Ssl = "on"; 
    #set req.http.X-Url-Scheme= "https";

    #########
    # SSL
    #########
    call https_vcl_recv; #SSL
    if (req.http.X-Forwarded-Proto ~ "https") {
        set req.http.passed-by-recv-http = "yes";
        set req.backend_hint = default_ssl;
        return (pass);
    }

    if (req.http.User-agent ~ "/mobile/") {
        #set req.backend_hint = mob;
    }

    ###############
    # DIRECTORS : LOAD-BALANCING
    ###############
    if (req.url ~ "^/url/from/java/") {
        set req.backend_hint = back_java_sample;
    } elsif (req.http.host ~ "bar.com" || req.http.host == "www.foo.com") { # sample.bar.com
        set req.backend_hint = bar_director.backend(); # SAMPLE WITH DIRECTOR
    } else {
        set req.backend_hint = default;
    }

    # Use ACL
    if (client.ip ~ acl_local_sample) { # ~ = == ! && ||
        return (pipe);
    }

    # split geo ip SAMPLE
    #set req.http.X-Country-Code = geoip.lookup(client.ip);

    # included vcl files
    if (req.http.host ~ "\.varnish-cache\.org$") {
        #return (vcl(my_included_vcl_label));
    }

    # remove all cookies when not in admin
    if (!(req.url ~ "^/admin/")) {
        unset req.http.Cookie;

        # reg all "; +" to ";"
        set req.http.Cookie = regsuball(req.http.Cookie, "; +", ";");
    }

    ##############
    # purge cache
    ############
    if (req.method == "PURGE") {
        if (client.ip ~ acl_purge) {
            return (purge);
        } else {
            return (synth(403, "Access denied"));
        }
    }

    # cache miss
    # if (req.http.always_miss) {
    #     set req.hash_always_miss = true;
    #     return miss;
    # }

    ####
    # BAN 
    # $ varnishadm ban req.http.host == example.com '&&' req.url '~' '\\.png$'
    ####
    if (req.method == "BAN") {
        # Same ACL check as above:
        if (!client.ip ~ acl_purge) {
                return(synth(403, "Not allowed."));
        }
        ban("req.http.host == " + req.http.host + " && req.url == " + req.url);

        # Throw a synthetic page so the
        # request won't go to the backend.
        return(synth(200, "Ban added"));
    }

    # websocket sample
    if (req.http.upgrade ~ "(?i)websocket") {
        return (pipe);
    }

    # based on user-agent
    if (req.http.User-Agent ~ "(?i)iphone") {
        set req.http.X-UA-Device = "mobile-iphone";
    }
    # redirect mobiles
    if (req.http.X-UA-Device ~ "^mobile" || req.http.X-UA-device ~ "^tablet") {
        return(synth(750, "Moved Temporarily"));
    }

    # Synth
    if (req.url ~ "/synth") {
        return (synth(302, "http://my-varnish-cache.org"));
    }

    # default behavior, deliver result
    set req.http.passed-by-recv-http = "full";
    return (pass);
}



#######
# SYNTH 
#######
sub vcl_synth {
    if (resp.status == 301 || resp.status == 302) {
        set resp.http.location = resp.reason;
        set resp.reason = "Moved";
        return (deliver);
    }
}

########
# HASH #
########
sub vcl_hash {
    # used when storing object

    call https_vcl_hash; #SSL

    hash_data(req.url); # default
    if (req.http.host) {
        hash_data(req.http.host);
    } else {
        hash_data(server.ip);
    }
    return (lookup);

    # splitted geo ip
    hash_data(req.http.X-Country-Code);
}


##############
# PIPE for exemple 
# bidirectionnal http Websocket
##############
sub vcl_pipe {
    if (req.http.upgrade) {
        set bereq.http.upgrade = req.http.upgrade;
        set bereq.http.connection = req.http.connection;
    }
}


######################################
#       2. BACKEND RESPONSE
######################################
# Happens after we have read the response headers from the backend.
#
sub vcl_backend_response {
    # Here you clean the response headers, removing silly Set-Cookie headers
    # and other mistakes your backend does.

    # TOTAL grace + keep10m
    # https://varnish-cache.org/docs/6.5/users-guide/vcl-grace.html

    #GRACE, "smooth": sert l'object après la fin du TTL, 
    #pendant qu'il récupère un nouveau du backend
    set beresp.grace = 2m;

    #KEEP: garde l'object en "défaut" si le contenu ne peut être chargé
    #If-Modified-Since: and/or Ìf-None-Match: headers 304 Not Modified
    set beresp.keep = 8m;

    # stop cache for 500
    if (beresp.status >= 500 && bereq.is_bgfetch) {
          return (abandon);
    }

    # ex: override ttl
    if (bereq.url ~ "\.(png|gif|jpg)$") {
        unset beresp.http.set-cookie;
        set beresp.ttl = 1h;
    }

    # use mobile device sample
    if (bereq.http.X-UA-Device) {
        if (!beresp.http.Vary) { # no Vary at all
            set beresp.http.Vary = "X-UA-Device";
        }
    }

    # keep server pragma (ignored by varnish)
    if (beresp.http.Pragma ~ "nocache") {
        set beresp.uncacheable = true;
        set beresp.ttl = 120s; # how long not to cache this url.
    }

    ####
    # COMPRESS
    ####
    if (beresp.http.content-type ~ "text") {
        set beresp.do_gzip = true;
    }
}



############################
#       3. DELIVER 
############################
# Happens when we have all the pieces we need, and are about to send the response to the client.
#
sub vcl_deliver {
    #
    # You can do accounting or modifying the final object here.
    # set header depends on mobile device
    if ((req.http.X-UA-Device) && (resp.http.Vary)) {
        set resp.http.Vary = regsub(resp.http.Vary, "X-UA-Device", "User-Agent");
    }
}




############################
#       LOGGING
############################
# $ varnishlog -g raw -b(ackend) -c(lient)
# $ varnish-top/hist/stat

# $ varnishlog -d -q 'RespStatus == 503' -g request



# ##################
# ORIGINAL CONFIG #
# ##################
# backend default {
#     .host = "127.0.0.1";
#     .port = "8080";
# }

# sub vcl_recv {
# sub vcl_backend_response {
# sub vcl_deliver {
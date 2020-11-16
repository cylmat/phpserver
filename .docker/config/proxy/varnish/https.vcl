# HITCH
# https://hitch-tls.org/
#
# https://docs.varnish-software.com/varnish-cache-plus/features/client-ssl/
# https://tutoandco.colas-delmas.fr/software/varnish/installation-hitch-utiliser-https-varnish/

import std; # for local stdin/out

sub https_vcl_recv {
    # on PROXY connections the server.ip is the IP the client connected to.
	# (typically the DNS-visible SSL/TLS virtual IP)
    std.log("Client connected to " + server.ip);
    set req.http.passed-by-recv-http1 = server.ip;
    set req.http.passed-by-recv-http2 = std.port(client.ip);

    # port used in host(443), not by hitchproxy(8443)
    if (std.port( server.ip ) == 443) {
      set req.http.X-Forwarded-Proto = "https";
      set req.http.https = "on";

      set req.http.passed-by-recv-http = "stdport";
      std.log("Real client connecting over SSL/TLS from " + client.ip);
   }

   # redirect http to https
   # if (std.port(server.ip) != 443) {
   #      set req.http.location = "https://" + req.http.host + req.url;
   #      return(synth(301));
   #  }
}
 
sub https_vcl_hash {
   if ( req.http.X-Forwarded-Proto ) {
      hash_data( req.http.X-Forwarded-Proto );
   }
}
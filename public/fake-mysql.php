<?php
/**
 * @author: allyshka https://github.com/allyshka
 * @source: https://github.com/allyshka/Rogue-MySql-Server
 *
 * https://dev.mysql.com/doc/internals/en/connection-phase-packets.html
 */
function unhex($str) { return pack("H*", preg_replace('#[^a-f0-9]+#si', '', $str)); }

$srv = stream_socket_server("tcp://0.0.0.0:3306");

while (true) {
  echo "[.] Waiting for connection on 0.0.0.0:3306\n";
  $s = stream_socket_accept($srv, -1, $peer);

  echo "[+] Connection from $peer - greet... "; #GREETING PACKET
  fwrite($s, unhex('45 00 00 00 0a 35 2e 31  2e 36 33 2d 30 75 62 75
                    6e 74 75 30 2e 31 30 2e  30 34 2e 31 00 26 00 00
                    00 7a 42 7a 60 51 56 3b  64 00 ff f7 08 02 00 00
                    00 00 00 00 00 00 00 00  00 00 00 00 64 4c 2f 44
                    47 77 43 2a 43 56 63 72  00                     '));
  fread($s, 8192);

  echo "auth ok... "; #MYSQL SERVER HAS GONE AWAY
  fwrite($s, unhex('07 00 00 02 00 00 00 02  00 00 00'));
  fread($s, 8192);
  
  stream_socket_shutdown($s, STREAM_SHUT_WR); 
  echo "\n";
  #  break;
}

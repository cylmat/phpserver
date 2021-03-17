<?php

// init
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://nginx:80/test-server.php');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// nginx 1
$exec = curl_exec($ch);
if (!$exec) {
    echo 'Error nginx 1'.PHP_EOL;
    var_dump(curl_error($ch));
} else {
    echo 'Nginx 1-ok'.PHP_EOL;
}

// nginx 2
curl_setopt($ch, CURLOPT_URL, 'http://nginx2:80/test-server.php');
$exec = curl_exec($ch);
if (!$exec) {
    echo 'Error nginx 2'.PHP_EOL;
    var_dump(curl_error($ch));
} else {
    echo 'Nginx 2-ok'.PHP_EOL;
}
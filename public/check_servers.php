<?php

/**
 * Check servers from Ajax
 */
if (array_key_exists('check', $_GET)) {
    $res = [];
    foreach ($servers as $srv => $port) {
        exec("nc -z phpenv-server_$srv\_1 80", $out, $ret);
        $res[$srv] = $ret;
    }
    die(json_encode($res));
}
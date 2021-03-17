<?php

if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'haproxy') !== false) {
    // HAProxy
    echo $_SERVER['SERVER_SOFTWARE'] . " [OK]\n";
} elseif (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'varnish') !== false) {
    // Varnish
    echo $_SERVER['SERVER_SOFTWARE'] . " [OK]\n";
} else {
    exit(1);
}

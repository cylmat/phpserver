<?php

if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'apache') !== false) {
    // Apache
    echo $_SERVER['SERVER_SOFTWARE'] . " [OK]\n";
} elseif (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'nginx') !== false) {
    // Nginx
    echo $_SERVER['SERVER_SOFTWARE'] . " [OK]\n";
} else {
    exit(1);
}



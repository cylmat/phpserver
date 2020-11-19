<?php

/**
 * Servers url
 */
$menu_links = ['Nginx' => 8001, 'Apache' => 8002, 'HAProxy' => 8010, 'Varnish' => 8011 ];

/**
 * Get server variables
 */
ob_start();
phpinfo(INFO_VARIABLES);
$content = ob_get_clean();

/**
 * Set menu
 */
$bootstrap = '<!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">';

foreach($menu_links as $label => $port) {
    $scheme = $_SERVER['REQUEST_SCHEME'];
    $host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);
    $menu_btn .= "\n".'<li class="nav-item"><a class="nav-link" '. 
            "href=\"$scheme://$host:$port\">$label</a></li>";
}

/**
 * Display template
 */
echo preg_replace_callback_array([
    "/<title>.*<\/title>/" => function($match) use ($bootstrap) { 
        return "<title>Php environment server</title>$bootstrap"; 
    },
    "/<body>/" => function($match) use ($menu_btn) { 
        $menu = "\n" . 
            '<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-center">' . 
            '<ul class="navbar-nav">' . $menu_btn . "</ul></nav>" . "\n";
        return "<body>$menu"; 
    }
], $content);
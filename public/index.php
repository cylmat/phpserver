<?php

/**
 * Servers url
 */
$servers = ['Nginx' => 8001, 'Apache' => 8002, 'HAProxy' => 8010, 'Varnish' => 8011 ];
$js_ports = "'".join("','", array_values($servers))."'";
extract($servers);
$scheme = $_SERVER['REQUEST_SCHEME'];
$host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);
$title = "Php environment server";

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

$menu_btn = '';
foreach($servers as $label => $port) {
    $menu_btn .= "\n".'<li class="nav-item"><a class="status-'.$port.' nav-link" '. 
            "href=\"$scheme://$host:$port\">$label</a></li>";
}

/**
 * Display template
 */
echo preg_replace_callback_array([
    "/<title>.*<\/title>/" => function($match) use ($title, $bootstrap) { 
        return "<title>$title</title>$bootstrap"; 
    },
    "/<body>/" => function($match) use ($menu_btn) { 
        $menu = "\n" . 
            '<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-center">' . 
            '<ul class="navbar-nav">' . $menu_btn . "</ul></nav>" . "\n";
        return "<body>$menu"; 
    }
], $content);

echo <<<S
<script src="/jquery-3.5.1.min.js"></script>
<script>
    get_server(); // init
    setInterval( function() { console.clear(); get_server(); }, 10000);

    function status(port, status) {
        $(".status-" + port).css('color', true === status ? "green" : "red");
    }
    function get_server() {
        $.get("{$scheme}://{$host}:8001/", function(data) { status(8001, true) })
            .fail(function() { status(8001, false) });

        $.get("{$scheme}://{$host}:8002/", function(data) { status(8002, true) }, "jsonp")
            .fail(function() { status(8002, false) });

        $.get("{$scheme}://{$host}:8010/", function(data) { status(8010, true) }, "jsonp")
            .fail(function() { status(8010, false) });

        $.get("{$scheme}://{$host}:8011/", function(data) { status(8011, true) }, "jsonp")
            .fail(function() { status(8011, false) });
    }
</script>
S;
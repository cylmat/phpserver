<?php

$servers = ['nginx'=>8001, 'apache'=>8002,'haproxy'=>8010, 'varnish'=>8011];

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

/**
 * Servers url
 */
$cscheme = $_SERVER['REQUEST_SCHEME'];
$chost = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);
$cport = parse_url($_SERVER['HTTP_HOST'], PHP_URL_PORT);
$title = "Php environment server";

/**
 * Get headers
 */
$headers = getallheaders();
ksort($headers);
$display_headers = "<h2>Headers</h2><table><tr class='h'><th>Variable</th><th>Value</th></tr>";
array_walk($headers, function($v, $k) use (&$display_headers) { 
    $display_headers .= "<tr><td class=\"e\">$k</td><td class=\"v\">$v</td></tr>\n"; 
});
$display_headers .= "</table>";

/**
 * Get server variables
 */
ob_start();
phpinfo(INFO_VARIABLES | INFO_ENVIRONMENT);
$content = ob_get_clean();

/**
 * Set menu
 */
$bootstrap = '<!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">';

$menu_btn = '';
foreach($servers as $label => $port) {
    $menu_btn .= "\n".'<li class="nav-item"><a class="status-'.$label.' nav-link text-capitalize" '. 
            "href=\"$cscheme://$chost:$port\">$label</a></li>";
}

/**
 * Links
 */
$links_nginx = include 'links/nginx.php';
$links =  "<div class='jumbotron float-left' style='position:absolute'><h2>Snippets</h2><div>";
foreach ($links_nginx['snippets'] as $label => $href) {
    $links .= "<a href='$href'>$label</a>";
}
$links .= "</div></div>";

/**
 * Display template
 */
echo preg_replace_callback_array([
    "/<title>.*<\/title>/" => function($match) use ($title, $bootstrap) { 
        return "<title>$title</title>$bootstrap"; 
    },
    "/<h2>Env/" => function($match) use ($display_headers) {
        return "$display_headers<h2>Env"; 
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
    get_servers(); // init
    setInterval( function() { console.clear(); get_servers(); }, 3000);

    function status(srv, status) {
        $(".status-" + srv).css('color', 0 === status ? "green" : "red");
    }
    function get_servers() {
        $.get("{$cscheme}://{$chost}:{$cport}/?check", function(data) { 
            var res = JSON.parse(data);
            for (var [srv, stat] of Object.entries(res)) {
                status(srv, stat);
            }
        });
    }
</script>
S;
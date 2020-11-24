<?php

$servers = ['nginx'=>8001, 'apache'=>8002,'haproxy'=>8010, 'varnish'=>8011];

/**
 * Check servers from Ajax
 */
include 'check_servers.php';

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
preg_match("/<style.*style>/", str_replace("\n", "\r", $content), $php_css);
preg_match("/<div.*div>/", str_replace("\n", "\r", $content), $php_env);
$php_css = str_replace("\r", "\n", $php_css[0]);
$php_env = str_replace("\r", "\n", $php_env[0]);

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

$menu = "\n" . 
'<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-center">' . 
'<ul class="navbar-nav">' . $menu_btn . "</ul></nav>" . "\n";

/**
 * Links
 */
$links_nginx = include 'links/nginx.php';
$links =  "<div class='nav flex-column float-left' style='position:absolute'><h2>Nginx</h2><div>";
foreach ($links_nginx as $label => $href) {
    $links .= "<a href='$href'>$label</a>";
}
$links .= "</div></div>";

/**
 * Scripts
 */
$script = <<<S
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

/**
 * Template
 */
include 'template.php';
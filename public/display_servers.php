<?php

ini_set('display_errors','on');
error_reporting(-1);

// For curl
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    echo $_SERVER['SERVER_SOFTWARE']." OK\n";
    return 0;
}

/**
 * Variables
 */
$apache = preg_match('/apache/', strtolower($_SERVER['SERVER_SOFTWARE'])) ? $_SERVER['SERVER_SOFTWARE'] : 'Apache';
$nginx = preg_match('/nginx/', strtolower($_SERVER['SERVER_SOFTWARE'])) ? ucfirst($_SERVER['SERVER_SOFTWARE']) : 'Nginx';
$ok_a = $apache == 'Apache' ? '[<span class="g">OK</span>]' : '';
$ok_n = $nginx == 'Nginx' ?  '[<span class="g">OK</span>]' : '';
$host = parse_url($_SERVER['HTTP_HOST'])['host'];

$dbkv = gets('dbkv');
$dbsql = gets('dbsql');
$mq = '';
[$php_css, $php_env] = variables();


/**
 * Template
 */
echo <<<TEMPLATE
<!doctype html>
<html>
<head>
    $php_css
    <style type="text/css">
        .r {color:red}
        .g {color:green}
        .b {color:blue}
    </style>
</head>
<body>
    <center>
    <h1>My Application</h1>
    Host <em class="b">{$_SERVER['HTTP_HOST']}</em> functionnal on address <em class="b">{$_SERVER['SERVER_ADDR']}</em><br/>
    Fpm server n°<em class="b">{$_SERVER['HOSTNAME']}</em>
    <h2>Servers</h2>
    <a href="http://$host:8001/">$apache</a> $ok_n<br/>
    <a href="http://$host:8002/">$nginx</a> $ok_a
    <h2>Databases</h2>
    $dbkv<br/>
    $dbsql

    $php_env
    </center>
</body>
</html>
TEMPLATE;

function gets(string $type): string {
    $file = __DIR__ . "/../shell/check-$type.php";
    if (file_exists($file)) {
        ob_start();
        include_once $file;
        $res = nl2br(ob_get_clean());
        $res = preg_replace('/ok/', '<span class="g">OK</span>', $res);
        $res = preg_replace('/failed/', '<span class="r">failed</span>', $res);
        return $res;
    }
    return '';
}

function variables(): array {
    ob_start();
    phpinfo(INFO_VARIABLES | INFO_ENVIRONMENT);
    $content = ob_get_clean();
    preg_match("/<style.*style>/", str_replace("\n", "\r", $content), $php_css);
    preg_match("/<div.*div>/", str_replace("\n", "\r", $content), $php_env);
    $php_css = str_replace("\r", "\n", $php_css[0]);
    $php_env = str_replace("\r", "\n", $php_env[0]);
    return [$php_css, $php_env];
}

/*
NGINX
'Deny access' => "/disable/myfile.php",
'Php files' => '/httpheaders.php'

HAPROXY
'Check' => '/haproxycheck',
'Stats' => "/docker-haproxy?stats",
'Stats51' => "$cscheme://$chost:51510/stats"
 */
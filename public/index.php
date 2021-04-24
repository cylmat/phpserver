<?php


$dbkv = gets('dbkv');
$dbsql = gets('dbsql');
[$php_css, $php_env] = variables();

echo <<<TEMPLATE
<!doctype html>
<html>
<head>
    $php_css
    <style type="text/css">
        .g {color:green}
        .r {color:red}
    </style>
</head>
<body>
    <h1>My Application</h1>
    App host <em>{$_SERVER['HTTP_HOST']}</em> functionnal on address <em>{$_SERVER['SERVER_ADDR']}</em>
    <h2>Servers</h2>
    <a href="">{$_SERVER['SERVER_SOFTWARE']}</a> [<span class="g">OK</span>]
    <h2>Databases</h2>
    $dbkv<br/>
    $dbsql
    <h2>PhpInfo</h2>
    $php_env
</body>
</html>
TEMPLATE;

function gets(string $type): string {
    $file = __DIR__ . "/../shell/check-$type.php";
    if (file_exists($file)) {
        ob_start();
        include_once $file;
        $res = nl2br(ob_get_clean());
        $res = preg_replace('/ok|OK/', '<span class="g">OK</span>', $res);
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
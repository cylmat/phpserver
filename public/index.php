<?php

$dbsql = gets('dbsql');

echo <<<TEMPLATE
<!doctype html>
<html>
<head>
    <style type="text/css">
        .g {color:green}
    </style>
</head>
<body>
    <h1>My Application</h1>
    App host <em>{$_SERVER['HTTP_HOST']}</em> functionnal on address <em>{$_SERVER['SERVER_ADDR']}</em>
    <h2>Servers</h2>
    <a href="">{$_SERVER['SERVER_SOFTWARE']}</a> [<span class="g">OK</span>]
    <h2>Databases</h2>
    $dbsql
</body>
</html>
TEMPLATE;

function gets(string $type): string{
    $file = __DIR__ . "/../shell/check-$type.php";
    if (file_exists($file)) {
        ob_start();
        include_once $file;
        return nl2br(ob_get_clean());
    }
    return '';
}
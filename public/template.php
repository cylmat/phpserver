<?php

/**
 * Display template
 */

echo <<<TEMPLATE
<!DOCTYPE HTML>
<html>
<head>
    <title>$title</title>
    $bootstrap
    $php_css
</head>
<body>
    $menu
    $links
    <div class="center">
        $display_headers
        $php_env
    </div>
    $script
</body>
</html>
TEMPLATE;
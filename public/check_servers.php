<?php

// Disable cache 
header("Cache-Control: no-cache,no-store,must-revalidate,pre-check=0,post-check=0");

/**
 * Check servers from Ajax
 */
if (array_key_exists('check', $_GET)) {
    $res = [];
    foreach ($servers as $srv => $port) {
        exec("nc -z phpenv-server_$srv\_1 80", $out, $ret);
        $res[$srv] = $ret=== 0 ? 1 : 0;
    }
    die(json_encode($res));
}

/**
 * Check databases from Ajax
 */
if (array_key_exists('check_db', $_GET)):

function pdo_check($host, $url) {
    try {
        $pdo = new PDO("$url", $_SERVER['DB_USER'], $_SERVER['DB_PASS']);
        $r = $pdo->query("CREATE TEMPORARY TABLE IF NOT EXISTS tests_my (id int, my int);");
        if (false === $r) throw new PDOException("$host Query failed!");
        return 1;
    } catch (PDOException $e) {
        return 0;
    }
}

$res["mysql"]   = pdo_check("mysql", "mysql:host=mysql;port=3306;dbname=mydb");
$res["mariadb"] = pdo_check("mariadb", "mysql:host=mariadb;port=3306;dbname=madb");
$res["sqlite"]  = pdo_check("sqlite", "sqlite:/sqlite/sqlite.db3");

/***********************  cache */
// REDIS
try { 
    $redis = new Redis;
    @$redis->connect('redis', 6379); // @throws error
    $redis->set("key", "ok");
    $res["redis"] = $redis->get("key") === "ok" ? 1 : 0;
} catch(\Exception $e) {
    $res["redis"] = 0;
}

// MEMCACHED
try {
    $mc = new Memcached; 
    $mc->addServer("memcached", 11211); 
    $mc->set("key", "ok"); 
    $res["memcached"] = $mc->get("key") === "ok" ? 1 : 0;
} catch(\Exception $e) {
    $res["memcached"] = 0;
}

// DBA
try {
    $id = dba_open("/tmp/test.db", "n", "db4");
    if (!$id) {
        $res["dba"] = 0;
    }
    dba_replace("key", "sample", $id);
    if (dba_exists("key", $id)) {
        dba_delete("key", $id);
        $res["dba"] = 1;
    }
    dba_close($id);
} catch (\Exception $e) {
    $res["dba"] = 0;
}

die(json_encode($res));
endif;

/**
 * For servers checking
 */
if (!isset($_SERVER['HTTP_HOST'])) die;
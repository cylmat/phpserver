<?php

/**
 * Check data connections
 */
class Check
{
    static private $count = 0;

    static function getCount(): int
    {
        return self::$count;
    }

    static function reset(): void
    {
        self::$count = 0;
    }
    
    static function total(int $total, string $type)
    {
        if ($total === self::getCount()) {
            echo "$type count: ".self::getCount()."/$total [ok]\n";
        } else {
            echo "$type count: ".self::getCount()."/$total [failed]\n";
            if (PHP_SAPI === 'cli') {
                exit(1);
            }
        }
    }

    static function pdo(string $type, string $dsn)
    {
        $table = 'test';
        try {
            $pdo = new PDO($dsn, $_SERVER['DB_USER'], $_SERVER['DB_PASS']);
            $pdo->exec("SET CHARACTER SET utf8");
            $pdo->exec("CREATE TABLE IF NOT EXISTS $table (id INT, my VARCHAR(20))");
            $pdo->exec("TRUNCATE TABLE $table;");
            $pdo->exec("DELETE FROM $table;");
            $pdo->exec("INSERT INTO $table (id, my) VALUES (96, 'ok')");
            $r = $pdo->query("SELECT * FROM $table");
            if (is_array($res = $r->fetch())) {
                echo $type . ':' . $res['my'] . PHP_EOL;
                self::$count++;
            } else {
                throw new PDOException("Query $type empty Error:".$r->errorInfo()[2]);
            }
        } catch (PDOException $e) {
            echo " $type:failed **" . $e->getMessage() . '**' . PHP_EOL;
        }
    }

    /**
     * CACHE K-V
     */
    static function dba(string $file) // Berkeley abstraction
    {
        // dba_handlers() => cdb, cdb_make, db4, inifile, flatfile, qdbm, lmdb
        $dba = dba_open($file, "n", "db4"); //n: rwc
        if (!$dba) {
            echo "DBA:-Open- method failed\n";
        }
        dba_replace("key", "dba:ok" . PHP_EOL, $dba);
        if (dba_exists("key", $dba)) {
            echo dba_fetch("key", $dba);
            dba_delete("key", $dba);
            self::$count++;
        } else {
            echo "DBA:-Fetching key- failed\n";
        }
        dba_close($dba);
    }

    static function redis()
    {
        try {
            $redis = new \Redis;
            $redis->connect('redis', 6379);
            $redis->set("key", "redis:ok", 5);
            echo $redis->get("key") . PHP_EOL;
            self::$count++;
        } catch (\Exception $e) {
            echo 'redis:failed ' . $e->getMessage() . PHP_EOL;
        }
    }

    static function mem()
    {
        try {
            $mc = new \Memcached;
            $mc->addServer("memcached", 11211);
            $mc->set("test", "memcached:ok", 5);
            $res = $mc->get("test");
            if ($mc->getResultCode() !== 0) {
                throw new Exception("Error when trying to set result");
            }
            echo $res.PHP_EOL;
            self::$count++;
        } catch (\Exception $e) {
            echo 'memcached:failed '.$e->getMessage() . PHP_EOL;
        }
    }

    /**
     * Uncomment to test Odbc
     */
    /*
    static function odbc(string $dsn)
    {
        $table = "odbc";

        // odbc mysql
        $connection = odbc_connect($dsn, $_SERVER['DB_USER'], $_SERVER['DB_PASS']);
        if (!$connection) {
            echo ' ODBC fail connection ' . PHP_EOL;
            return;
        }
        odbc_exec($connection, "CREATE TABLE IF NOT EXISTS $table (id INT, my VARCHAR(20))");
        odbc_exec($connection, "TRUNCATE TABLE $table;");
        odbc_exec($connection, "INSERT INTO $table (id, my) VALUES (21, 'ok') ON DUPLICATE KEY UPDATE id=id");
        $res = odbc_exec($connection, "SELECT * FROM $table WHERE id=21");
        odbc_fetch_row($res, 0);
        if ($r = odbc_result($res, 'my')) {
            echo "odbc:$r\n";
            self::$count++;
        } else {
            echo ' ODBC:fail query ' . PHP_EOL;
        }
        odbc_close($connection);
    }
    */

    static function curl(string $url, int $port)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://$url:$port/");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        echo($err);
    }
}

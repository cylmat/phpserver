<?php

$TOTAL = 6;

// SQL
if (defined('SQL')) {
    $TOTAL += 2;
    Check::pdo('maria', 'mysql:host=maria;port=3306;dbname=madb');
    Check::pdo('mysql', 'mysql:host=mysql;port=3306;dbname=mydb');
}

// PDO
Check::pdo('postg', 'pgsql:host=postgres;port=5432;dbname=pgdb');
Check::pdo('sqlit', 'sqlite:/sqlite/sqlite.db3');

// Php ext for MySql
Check::odbc("DRIVER={MySQL ODBC 8.0 Unicode Driver};Server=mysql;Database=mydb;Port=3306;String Types=Unicode");

// Key-value
Check::dba("/tmp/test.db4");
Check::redis();
Check::mem();

if ($TOTAL === $a = Check::getCount()) {
    echo "count: $a/$TOTAL [OK]\n";
} else {
    echo "count: $a/$TOTAL [failed]\n";
    exit(1);
}

/**
 * Check data connections
 */
class Check
{
    static private $count = 0;

    static function getCount()
    {
        return self::$count;
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
                var_dump($r->errorInfo()[2]);
                throw new PDOException("Query $type empty");
            }
        } catch (PDOException $e) {
            echo " $type:" . $e->getMessage() . PHP_EOL;
            exit(1);
        }
    }

    static function odbc(string $dsn)
    {
        $table = "odbc";

        // odbc mysql
        $connection = odbc_connect($dsn, $_SERVER['DB_USER'], $_SERVER['DB_PASS']);
        if (!$connection) {
            echo ' ODBC fail connection ' . PHP_EOL;
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
            exit(1);
        }
        odbc_close($connection);
    }

    /**
     * CACHE K-V
     */
    static function dba(string $file) //berkeley
    {
        //echo (implode(' ',dba_handlers())); //=> cdb, cdb_make, db4, inifile, flatfile, qdbm, lmdb
        $dba = dba_open($file, "n", "db4"); //n: rwc
        if (!$dba) {
            echo " dba_open failed \n";
        }
        dba_replace("key", "dba:ok" . PHP_EOL, $dba);
        if (dba_exists("key", $dba)) {
            echo dba_fetch("key", $dba);
            dba_delete("key", $dba);
            self::$count++;
        } else {
            echo " DBA:failed \n";
            exit(1);
        }
        dba_close($dba);
    }

    static function redis()
    {
        try {
            $redis = new \Redis;
            $redis->connect('redis', 6379);
            $redis->set("key", "redis:ok");
            echo $redis->get("key") . PHP_EOL;
            self::$count++;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            exit(1);
        }
    }

    static function mem()
    {
        try {
            $mc = new \Memcached;
            $mc->addServer("memcached", 11211);
            $mc->set("test", "memcached:ok");
            echo $mc->get("test") . PHP_EOL;
            self::$count++;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            exit(1);
        }
    }
}

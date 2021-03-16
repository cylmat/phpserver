<?php

Check::pdo('mysql','mysql:host=mysql;dbname=mydb');
Check::pdo('maria','mysql:host=mariadb;dbname=madb');
Check::pdo('sqlite','sqlite:/sqlite/sqlite.db3'); 

Check::pg('pgsql:host=postgres;port=5432;dbname=pgdb');
Check::odbc("DRIVER={MySQL ODBC 8.0 Unicode Driver};Server=mysql;Database=mydb;Port=3306;String Types=Unicode");

Check::dba("/tmp/test.db4");
Check::redis();
Check::mem();


/**
 * Check data connections
 */
class Check
{
    static function pdo(string $type, string $dsn)
    {
        $table = 'test_tb';
        try {
            $pdo = new PDO($dsn, $_SERVER['DB_USER'], $_SERVER['DB_PASS']);
            $pdo->exec("SET CHARACTER SET utf8");
            $pdo->exec("CREATE TABLE IF NOT EXISTS $table (id INT, my TEXT);");
            $pdo->exec("TRUNCATE TABLE $table;");
            $pdo->exec('INSERT INTO '.$table.' (id, my) VALUES (96, " '.$type.':ok ") ON DUPLICATE KEY UPDATE id=id;');
            $r = $pdo->query("SELECT * FROM $table");
            if (false === $r) throw new PDOException("Query failed");
            if(is_array($res = $r->fetch())) {
                echo $res['my'] . PHP_EOL;
            }
        } catch (PDOException $e) {
            echo " $type:".$e->getMessage().PHP_EOL;
        }
    }

    static function pg(string $dsn)
    {
        // Postgres
        try {
            $pdo = new PDO($dsn, $_SERVER['DB_USER'], $_SERVER['DB_PASS']);
            $r = $pdo->exec("CREATE TABLE IF NOT EXISTS test_tb (id INT, my TEXT);");
            if(false !== $r) {
                echo ' pg-ok'.PHP_EOL;
            }
        } catch (PDOException $e) {
            echo " PG:".$e->getMessage().PHP_EOL;
        }
    }

    static function odbc(string $dsn)
    {
        // odbc mysql
        $connection = odbc_connect($dsn, $_SERVER['DB_USER'], $_SERVER['DB_PASS']);
        if(!$connection) {
            echo ' ODBC fail connection '.PHP_EOL;
        }
        odbc_exec($connection,'INSERT INTO tests_my (id, my) VALUES (21, " odbc-ok ")');
        $res = odbc_exec($connection, "SELECT * FROM tests_my WHERE id=21");
        odbc_fetch_row ($res, 0);
        if($r = odbc_result($res, 'my')) {
            echo $r.PHP_EOL;
        } else {
            echo ' ODBC:fail query '.PHP_EOL;
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
        dba_replace("key", " dba-ok ".PHP_EOL, $dba);
        if (dba_exists("key", $dba)) {
            echo dba_fetch("key", $dba);
            dba_delete("key", $dba);
        } else {
            echo " DBA:failed \n";
        }
        dba_close($dba);
    }

    static function redis()
    {
        try { 
            $redis = new Redis;
            $redis->connect('redis', 6379);
            $redis->set("key", " redis-ok ");
            echo $redis->get("key").PHP_EOL;
        } catch(\Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }
    }

    static function mem()
    {
        try {
            $mc = new Memcached; 
            $mc->addServer("memcached", 11211); 
            $mc->set("test", " memcached-ok "); 
            echo $mc->get("test").PHP_EOL;
        } catch(\Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }
    }
}

echo PHP_EOL;
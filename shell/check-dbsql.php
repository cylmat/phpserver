<?php

/**
 * Check PDO Sql databases
 */

include_once __dIR__.'/check.class.php';

Check::reset();

Check::pdo('maria', 'mysql:host=maria;port=3306;dbname=madb');
Check::pdo('mysql', 'mysql:host=mysql;port=3306;dbname=mydb');
Check::pdo('postgres', 'pgsql:host=postgres;port=5432;dbname=pgdb');

// Php ext for MySql Odbc
// Check::odbc("DRIVER={MySQL ODBC 8.0 Unicode Driver};Server=mysql;Database=mydb;Port=3306;String Types=Unicode");

Check::total(3, 'SQL');

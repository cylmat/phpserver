<?php

/**
 * Check Sqlite and Key-Value databases
 */

include_once __dIR__.'/check.class.php';

Check::reset();

// Sqlite
Check::pdo('sqlite', 'sqlite:/tmp/sqlite.db3');

// Key-value
Check::dba("/tmp/dba.db4");
Check::redis();
Check::mem();

Check::total(4, 'KV');

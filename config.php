<?php

$dbname = 'testdb';
$dbtype = 'mysql';
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';

try {
	$db_conn = new PDO($dbtype.':host='.$dbhost.';dbname='.$dbname, $dbuser, $dbpass);
} catch (PDOException $e) {
	echo "Could not connect to Database";
}

?>
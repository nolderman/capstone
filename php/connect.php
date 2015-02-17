<?php
require_once 'login.php';

$db_server = mysql_connect($db_hostname, $db_username,$db_password);

if(!$db_server) {
	die("Unable to connect to MySQL: " .mysql_error());
}

$db_selected = mysql_select_db($db_database, $db_server);

if (!$db_selected) {
    die ('The provided database name was invalid: ' . mysql_error());
}

?>
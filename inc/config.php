<?php
error_reporting();

//***************** HOST**************//
//$dbserver   = "mysql1008.mochahost.com";
//$dbName     = "icounane_fleet";
//$dbuser     = "icounane_fleet";
//$dbpassword = "i8wloBigpSTr";
//***************** LOCAL **************//
$dbserver   = "localhost";
$dbName     = "elmagd";
$dbuser     = "root";
$dbpassword = "";

$db = new DB($dbuser,$dbpassword,$dbName,$dbserver);

?>

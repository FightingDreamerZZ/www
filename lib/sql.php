<?php
/*
* Copyright © 2013 Elaine Warehouse
* File: lib/sql.php
* mysql DB connection configuration
*/

//host ip address
$mysql_host = 'localhost';
//DB username
$mysql_user = 'root';
//DB password
$mysql_password = 'agtEcars123';
//Database name
$mysql_dbname = 'eware';


$link = mysql_connect($mysql_host, $mysql_user, $mysql_password)
    or die('Check Database Connection: ' . mysql_error() . "!!!");
mysql_select_db($mysql_dbname) or die('Could not select target database!!!');

?>
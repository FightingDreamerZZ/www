<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: index.php
* This file display a user panel to access all frequently used functions.
*/
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('../lib/sql.php');
include('../lib/user_lib.php');

if($_GET['api_key'] == md5("agt") && isset($_GET['bar'])){
	//a2922ec60c2c56d3f2e0beb5cab1d6e0
	$barcode = $_GET['bar'];
	$table = get_table($barcode);
	$sql_code = "select * from ".$table." where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	echo($a_check['quantity']);
}else{
	echo NULL;
}

//echo date("Ymd") . sprintf("%04s", 1);
?>

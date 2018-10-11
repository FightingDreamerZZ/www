<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: login.php
* This file provides a login portal for user.
*/

error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('../lib/sql.php');
header('Content-type: application/json');


if($_POST) {
	$user = $_POST["user"];
	$pass = $_POST["pass"];
	$sql_code = "select * from ew_user where user = '".$user."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	$ew_verified = $a_check['pass'];
	if($ew_verified == $pass){
		echo '{"success":1}';
	}else{
		echo '{"success":0,"error_message":"Username and/or password is invalid."}';
	}
} else {
	echo '{"success":0,"error_message":"Username and/or password is invalid."}';
	}

?>
<?php
/*
* Copyright © 2013 Elaine Warehouse
* File: admin_lib.php
* This file provides some generally used functions for admin module.
*/

function stop($msg){
		die("<script>window.alert('$msg');history.go(-1);</script>");
	}
function check($str,$len,$name){
	if(strlen($str)>$len){
		stop("$name Exceeded Max lenth");
		}
	if(strlen($str)<1){
		stop("$name Can not be empty!");
		}
}

function check_data($table_name,$key_name,$key){
	$sql_code = "SELECT * FROM `$table_name` WHERE `$key_name` = '$key'";
	//echo($sql_code);
	$result_info = mysql_query($sql_code);
	if ( mysql_num_rows($result_info) == 0){
		return false;
	}
	return true;
}
?>
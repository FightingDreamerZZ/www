<?php
/*
* Copyright © 2013 Elaine Warehouse
* File: user_lib.php
* This file provides some generally used functions for all modules.
*/


//input a message, die and alert this message, jump back to previous page.
function stop($msg){
		die("<script>window.alert('$msg');history.go(-1);</script>");
	}

//void(), check cookie existed, if not, die and jump to login page	
function check_user_cookie(){
	if(!$_COOKIE['ec_user_name'] || !$_COOKIE['ec_user_verified']){
		die('<meta http-equiv="refresh" content="0;URL=../login.php">');
	}
}	

//input a string, max lenth, string name; call stop() if string exceeded max lenth or is empty.
function check($str,$len,$name){
	if(strlen($str)>$len){
		stop("$name Exceeded Max lenth");
		}
	if(strlen($str)<1){
		stop("$name Can not be empty!");
		}
}

//input value, value name; call stop() if input value is not positive int 
function check_int($value,$name){
	if(!preg_match('/^[0-9]+$/', $value)){
			stop("$name: Invalid type!~");
		}
}

//input value, value name; call stop() if input value is not numeric 
function check_num($value,$name){
	if(!is_numeric($value)){
			stop("$name: Invalid type!");
		}
}

//input table name, keyname and key; return true if find any record.
function check_data($table_name,$key_name,$key){
	$sql_code = "SELECT * FROM `$table_name` WHERE `$key_name` = '$key'";
	//echo($sql_code);
	$result_info = mysql_query($sql_code);
	if ( mysql_num_rows($result_info) == 0){
		return false;
	}
	return true;
}


//input: username, a message string. insert a record into ew_log crossponding to the user
function sys_log($user,$message){
	$sql_code ="INSERT INTO `ew_log` (`log_id`, `time`, `user`, `msg`) VALUES (NULL, CURRENT_TIMESTAMP, '".$user."', '".$message."');";
	
		if(!($result=mysql_query($sql_code))) { 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}
}

//input text, some words; return text with input words highlighted
function highlight($text, $words) {
    preg_match_all('~\w+~', $words, $m);
    if(!$m){
        return $text;
		}
    $re = '~\\b(' . implode('|', $m[0]) . ')\\b~i';
    return preg_replace($re, '<b>$0</b>', $text);
}

//input a string, remove everything behind this string from URL_QUERY_STRING, return the result.
function trim_url($remove){
	$urlqs=$_SERVER['QUERY_STRING'];
	return preg_replace("/".$remove.".*/", "", $urlqs);
}

function update_interact($cid){
	$sql_code = "UPDATE  `ec_customer` SET  `interact` = CURRENT_TIMESTAMP WHERE `cid` ='$cid';";
	$result_info = mysql_query($sql_code);
}

function record_event($time){
	if($time == "0000-00-00 00:00:00"){
		return "Record";
	}else{
		return "Event";
	}
}

function check_event($date,$user){
	$sql_code = "SELECT * FROM `ec_commlog` WHERE `event_time` BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59' AND `user` = '$user';";
	//echo($sql_code);
	$result_info = mysql_query($sql_code);
	if ( mysql_num_rows($result_info) == 0){
		return false;
	}
	return true;
}

function check_event_all($date){
	$sql_code = "SELECT * FROM `ec_commlog` WHERE `event_time` BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59';";
	//echo($sql_code);
	$result_info = mysql_query($sql_code);
	if ( mysql_num_rows($result_info) == 0){
		return false;
	}
	return true;
}

function get_cname($cid){
	$sql_code = "SELECT `name` FROM `ec_customer` WHERE `cid` = '$cid';";
	//echo($sql_code);
	$result_info = mysql_query($sql_code);
	$row_2 = mysql_fetch_row($result_info);
	return $row_2[0];
}

?>
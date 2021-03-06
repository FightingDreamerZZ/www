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
	if(!$_COOKIE['ew_user_name'] || !$_COOKIE['ew_user_verified']){
		die('<meta http-equiv="refresh" content="0;URL=login.php">');
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

//input barcode, return which table this barcode is stored
function get_table($barcode){
	if(check_data('ew_car','barcode',$barcode)){
		return "ew_car";
		
	}else if(check_data('ew_part','barcode',$barcode)){
		return "ew_part";
	}else{
		stop("Barcode not found!");
	}
}

//input usertype code, return usertype name
function get_type($typecode){
	if($typecode =='0'){
		return "admin";
	}else if($typecode =='1'){
		return "superuser";
	}else{
		return "user";
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

//input username, barcode, type, amount; add a record to transaction based on the given inputs
function tran($user, $barcode,$type,$quantity) {
    $sql_code = "INSERT INTO `eware`.`ew_transaction` (`tid`, `user`, `barcode`, `type`, `quantity`, `time`) VALUES (NULL, '".$user."', '".$barcode."', '".$type."', '".$quantity."', CURRENT_TIMESTAMP);";
	if(!($result=mysql_query($sql_code))) { 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}
}

//input username,barcode,quantity,table; add/update a record to cart based on the given inputs
function cart($user,$barcode,$quantity,$table) {

	$sql_check = "SELECT * FROM `ew_cart` WHERE `barcode` = '".$barcode."' AND `user` = '".$user."';";

	$result_check=mysql_query($sql_check);

	if(mysql_num_rows($result_check) == 0){
		$sql_code = "INSERT INTO `eware`.`ew_cart` (`cid`, `barcode`, `user`, `table`, `quantity`) VALUES (NULL, '".$barcode."', '".$user."', '".$table."', '".$quantity."');";
	}else{
		$a_check = mysql_fetch_array($result_check);
		$new_quantity = $a_check[quantity] + $quantity;
		$sql_code = "UPDATE `ew_cart` SET `quantity` = '".$new_quantity."' WHERE `barcode` = '".$barcode."' AND `user` = '".$user."';";
	}   
	if(!($result=mysql_query($sql_code))) { 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}
}

//input pid, delete record from pending table where pid = input.
function del_pending($pid) {

	$sql_check = "DELETE FROM `ew_pending` WHERE `pid` = '".$pid."';";
	if(!($result=mysql_query($sql_check))) { 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}
}

//input username and barcode, return the amount of this item in cart crossponding to this user
function cart_amount($user,$barcode) {

	$sql_check = "SELECT * FROM `ew_cart` WHERE `barcode` = '".$barcode."' AND `user` = '".$user."';";

	$result_check=mysql_query($sql_check);

	if(mysql_num_rows($result_check) == 0){
		return 0;
	}else{
		$a_check = mysql_fetch_array($result_check);
		return $a_check[quantity];
	}   

}

//input table name, return the proper view page for this type of item
function get_view($table){
	if($table == "ew_car"){
		return 'view_car.php';
	}else{
		return 'view_part.php';
	}
}

//input a string, remove everything behind this string from URL_QUERY_STRING, return the result.
function trim_url($remove){
	$urlqs=$_SERVER['QUERY_STRING'];
	return preg_replace("/".$remove.".*/", "", $urlqs);
}

//input barcode, return car/part name
function get_name($barcode){
	$table = get_table($barcode);
	$sql_code = "select `name` from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	return $a_check[name];
}

//input barcode and field name, return the value of this field
function get_anything($barcode,$key){
	$table = get_table($barcode);
	$sql_code = "select `".$key."` from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	return $a_check[$key];
}

//input barcode, return true if amount in cart is less than stock amount
function cart_valid($barcode){
	if((get_anything($barcode,"quantity") + cart_amount($_COOKIE['ew_user_name'],$barcode)) < 0){
		return false;
	}else{
		return true;
	}
}

//input username, client info, barcode, amount, table; insert a record into pending pool
function pending($user,$client,$barcode,$quantity,$table) {

	$sql_code = "INSERT INTO `ew_pending` (`pid`, `barcode`, `user`, `client`, `quantity`, `table`, `time`) VALUES (NULL, '".$barcode."', '".$user."', '".$client."', '".$quantity."', '".$table."', CURRENT_TIMESTAMP);";
	if(!($result=mysql_query($sql_code))) { 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}
}

//send all the records in cart crossponding to the current user to message center, then generate a csv file.
function send_msg(){
	$sql_code_2 = "SELECT * FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."' ORDER BY `cid` ASC;";
	$result_info_2 = mysql_query($sql_code_2);
	if ( mysql_num_rows($result_info_2) == 0){
		exit("Empty Cart =_=, you have nothing to save!");
	}
	$string = "Barcode,Name,Amount,Dealer Price,Retail Price<br>";
	$i = 0;
	$list[$i] = array('Barcode','Name','Amount','Dealer Price','Retail Price');
	while ($row_2 = mysql_fetch_assoc($result_info_2)) {
		$i = $i+1;
		$list[$i] = array($row_2[barcode],get_name($row_2[barcode]),$row_2[quantity],get_anything($row_2[barcode],'w_price'),get_anything($row_2[barcode],'r_price'));
		$string = $string.$row_2[barcode].",".get_name($row_2[barcode]).",".$row_2[quantity].",".get_anything($row_2[barcode],'w_price').",".get_anything($row_2[barcode],'r_price')."<br>"; 
	}
	$new_file_name = "upload/".round(microtime(true) * 1000).".csv";
	$new_file_path = "../".$new_file_name;
	$file=fopen($new_file_path,"x") or exit("Unable to open file!");
	
	foreach ($list as $fields) {
    fputcsv($file, $fields);
	}

	fclose($file);
	
	$sql_msg = "INSERT INTO `ew_message` (`mid`, `user`, `message`, `time`, `path`) VALUES (NULL, '".$_COOKIE['ew_user_name']."', '".$string."', CURRENT_TIMESTAMP,'".$new_file_name."');";
	if(!($result=mysql_query($sql_msg))){ 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}else{
			echo "<p>Your List has been save in <a href=\"msg.php\">Message Center</a>!</p>";
		}
}


function check_pass($var){
	if($var){
	
		echo "<style>body {background-color: #FF0000;}</style>";
	}else{
		echo "<img src=\"images/fail.png\"/>";
	}
}

//input: username, a message string. insert a record into ew_log crossponding to the user
function sys_log($user,$message){
	$sql_code ="INSERT INTO `ew_log` (`log_id`, `time`, `user`, `msg`) VALUES (NULL, CURRENT_TIMESTAMP, '".$user."', '".$message."');";
	
		if(!($result=mysql_query($sql_code))) { 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}
}

?>
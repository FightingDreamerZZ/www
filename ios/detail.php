<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: search.php
* This file provides a search portal for user.
*/
header('Content-type: application/json');
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('../lib/sql.php');


if (isset($_GET["barcode"])) { 
	$barcode = $_GET["barcode"]; 
	$table = get_table($barcode);
}else{
	die("barcode not found");
}


$sql_code = "select * from ".$table." where barcode = '".$barcode."';";
$result_info = mysql_query($sql_code);
$a_check = mysql_fetch_assoc($result_info);
$a_check[photo_url] = get_thumb($a_check[photo_url]);
//echo $a_check[photo_url];

$rows[] =array('item_detail' => $a_check);

echo json_encode($rows);


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
	}
	return NULL;
}

function get_thumb($origin_image_path){
	$thumb_path = str_replace('upload/', 'upload/thumb/', $origin_image_path);
	$thumb_path_temp = str_replace('upload/', '../upload/thumb/', $origin_image_path);
	if (file_exists($thumb_path_temp)) {
		return $thumb_path;
	} else {
		return $origin_image_path;
	}
}

?>
<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: search.php
* This file provides a search portal for user.
*/
header('Content-type: application/json');
error_reporting(E_ALL ^ E_NOTICE);
include('../lib/sql.php');
//handle search request

if (isset($_GET["keywords"])) { 
	$temp_key = $_GET["keywords"]; 
} else { 
	$temp_key = '';
}
if (isset($_GET["type"])) { 
	$table = $_GET[type]; 
} else { 
	$table = 'ew_car';
}

$keyword = explode(',', $temp_key);
$sqltag = '';
foreach ($keyword as &$value) {
	if($sqltag ==''){
		$sqltag = "`xsearch` LIKE '%$value%' ";
	}else{
		$sqltag = $sqltag."AND `xsearch` LIKE '%$value%' ";
	}
}

if($table == "ew_part"){
	$sql_code_1 = "SELECT * FROM `".$table."` WHERE (`w_quantity` != '-1') AND (".$sqltag.") ORDER BY `barcode` DESC";
}else{
	$sql_code_1 = "SELECT * FROM `".$table."` WHERE (`quantity` > '0') AND (".$sqltag.") ORDER BY `barcode` DESC";
}
$result_info_1 = mysql_query($sql_code_1);
/*
echo '{';
echo '"item":[';
while ($row_1 = mysql_fetch_assoc($result_info_1)) {
	echo '{"Barcode":"'.$row_1["barcode"].'",';
	echo '"Name":"'.$row_1["name"].'",';
	echo '"Stock":"'.$row_1["quantity"].'"';
	echo '},';
}
echo '{}';
echo ']';
echo '}';
*/

//$rows[] = "hello";


while ($row_1 = mysql_fetch_assoc($result_info_1)) {
	 $rows[] =array('item' =>$row_1);
}
//var_dump($rows);
//echo '{"item":';
echo json_encode($rows);
//echo '}';
?>
<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: search.php
* This file provides a search portal for user.
*/
header('Content-type: application/json');
error_reporting(E_ALL ^ E_NOTICE);
include('../lib/sql.php');

$sqltag="";
$urltag="";
$default_sort=" ORDER BY `time` DESC ";
$limit = "20";

if (isset($_GET["load_limit"]) && !$_GET["load_limit"]=="") { 
	$limit = $_GET["load_limit"];
}

if (isset($_GET["start"]) && !$_GET["start"]=="" && isset($_GET["end"]) && !$_GET["end"]=="") { 
	$sqltag="WHERE `time` BETWEEN '".$_GET["start"]." 00:00:00' AND '".$_GET["end"]." 23:59:59'";	
}

if (isset($_GET["type"]) && !$_GET["type"]=="") { 
	if($sqltag==""){
		$sqltag="WHERE `type` = '".$_GET["type"]."'";
	}else{
		$sqltag= $sqltag."AND `type` = '".$_GET["type"]."'";
	}
}

if (isset($_GET["tran_type"]) && !$_GET["tran_type"]=="") { 
	if($_GET["tran_type"] =="enter"){
		$operator = ">";
	}else{
		$operator = "<";
	}
	if($sqltag==""){
		$sqltag="WHERE `quantity` ".$operator." '0'";
	}else{
		$sqltag= $sqltag."AND `quantity` ".$operator." '0'";	
	}
}

$sql_code_1 = "SELECT * FROM `transaction_view`".$sqltag.$default_sort." LIMIT ".$limit.";";
$result_info_1 = mysql_query($sql_code_1);

while ($row_1 = mysql_fetch_assoc($result_info_1)) {
	if($row_1["quantity"] > 0){
		$row_1["quantity"] = "+".$row_1["quantity"];
	}
	$rows[] =array('records' => $row_1);
}
echo json_encode($rows);



?>
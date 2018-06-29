<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: search.php
* This file provides a search portal for user.
*/
header('Content-type: application/json');
error_reporting(E_ALL ^ E_NOTICE);
include('../lib/sql.php');

function stats($sql_staus){
	if($result_info_s = mysql_query($sql_staus)){
		$row_s = mysql_fetch_row($result_info_s); 
	}else{
		return "ERROR";
	}
	if($row_s[0]==""){
		return '0';
	}else{
		return $row_s[0]; 
	}
}

$stats[total] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0');");
$stats[finish] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`category` = 'finish' );");
$stats[semi] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`category` = 'semi' );");
$stats[newcar] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`condition` = 'new' );");
$stats[used] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`condition` = 'used' );");
$stats[demo] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`condition` = 'demo' );");
$stats[damage] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`condition` = 'damaged' );");

$stats[total_parts] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`w_quantity` != '-1');");
$stats[total_parts_amount] = stats("SELECT SUM(quantity) FROM `ew_part` WHERE (`w_quantity` != '-1');");

$stats[body_type] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`w_quantity` != '-1') AND (`category`='body');");
$stats[body_amount] = stats("SELECT SUM(quantity) FROM `ew_part` WHERE (`w_quantity` != '-1') AND (`category`='body');");

$stats[acc_type] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`w_quantity` != '-1') AND (`category`='accessory');");
$stats[acc_amount] = stats("SELECT SUM(quantity) FROM `ew_part` WHERE (`w_quantity` != '-1') AND (`category`='accessory');");

$stats[mech_type] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`w_quantity` != '-1') AND (`category`='mechanical');");
$stats[mech_amount] = stats("SELECT SUM(quantity) FROM `ew_part` WHERE (`w_quantity` != '-1') AND (`category`='mechanical');");

$stats[ele_type] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`w_quantity` != '-1') AND (`category`='electrical');");
$stats[ele_amount] = stats("SELECT SUM(quantity) FROM `ew_part` WHERE (`w_quantity` != '-1') AND (`category`='electrical');");

$stats[rim_type] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`w_quantity` != '-1') AND (`category`='tire_and_rim');");
$stats[rim_amount] = stats("SELECT SUM(quantity) FROM `ew_part` WHERE (`w_quantity` != '-1') AND (`category`='tire_and_rim');");

$stats[total_short] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE `ew_part`.w_quantity > `ew_part`.quantity;");
$stats[total_out] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`quantity` = '0') AND (`w_quantity` != '-1');");
$stats[total_in] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`quantity` > '0') AND (`w_quantity` != '-1');");


$rows[] =array('status' => $stats);
echo json_encode($rows);



?>
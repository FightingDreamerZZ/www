<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: ajax/pending.php
* This file handles pending request.
*/

error_reporting(E_ALL ^ E_NOTICE);
include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();


//pending($_COOKIE['ew_user_name'],"test","123",1,"ew_cart")

if(isset($_GET['pendto'])){
	$client = $_GET['pendto'];
	$sql_get_cart = "SELECT * FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."';";
	$result_cart = mysql_query($sql_get_cart);
	
	if ( mysql_num_rows($result_cart) == 0){
		exit("Empty Cart =_=, you have nothing to pend!");
	}
	while ($cart_row = mysql_fetch_assoc($result_cart)) {
		$new_quantity = get_anything($cart_row[barcode],'quantity') + $cart_row[quantity];
		$update_sql = "UPDATE `".$cart_row[table]."` SET  `quantity` =  '".$new_quantity."' WHERE `barcode` =  '".$cart_row[barcode]."';";
		if (!($result=mysql_query($update_sql))) { 
			stop('DB Error!');
		}else{
			pending($_COOKIE['ew_user_name'],$client,$cart_row[barcode],$cart_row[quantity],$cart_row[table]);
		}
	}
	send_msg();
	$sql_del = "DELETE FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."';";
	if(!($result=mysql_query($sql_del))){ 
			stop('DB Error!');
		}else{
			echo "<p>Your List has been moved to <a href=\"pending.php\">Pending Pool</a></p>";
		}
}



?>







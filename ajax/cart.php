<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: cart.php
* This file handles clear() and proceed() request.
*/

error_reporting(E_ALL ^ E_NOTICE);
include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

if($_GET['do'] == 'clear'){
	clear_cart();
}

if($_GET['do'] == 'proceed'){
	$sql_get_cart = "SELECT * FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."';";
	$result_cart = mysql_query($sql_get_cart);
	
	if ( mysql_num_rows($result_cart) == 0){
		exit("Empty Cart =_=, you have nothing to proceed!");
	}
	while ($cart_row = mysql_fetch_assoc($result_cart)) {
		$new_quantity = get_anything($cart_row[barcode],'quantity') + $cart_row[quantity];
		$update_sql = "UPDATE `".$cart_row[table]."` SET  `quantity` =  '".$new_quantity."' WHERE `barcode` =  '".$cart_row[barcode]."';";
		if (!($result=mysql_query($update_sql))) { 
			stop('DB Error!');
		}else{
			if($cart_row[quantity] != 0){
				tran($_COOKIE['ew_user_name'],$cart_row[barcode],str_replace("ew_", "",$cart_row[table]),$cart_row[quantity]);
			}
		}
	}
	send_msg();
	$sql_del = "DELETE FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."';";
	if(!($result=mysql_query($sql_del))){ 
			stop('DB Error!');
		}else{
			echo "<p>Your List has been proceeded successfully!</p>";
		}
}


function clear_cart(){
	$sql_del = "DELETE FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."';";
	if(!($result=mysql_query($sql_del))){ 
			stop('DB Error!');
	}
}

$sql_code_1 = "SELECT * FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."' ORDER BY `cid` ASC;";
$result_info_1 = mysql_query($sql_code_1);


?>
<style>
table,th,td
{
border-collapse:collapse;
border-style:dotted;
border-width:1px;
text-align: center;
}
</style>
<table>
<tr>

<td>No</td>
<td>Barcode</td>
<td>Amount</td>
<td>Name</td>

</tr>
<?php 
$i = 0;
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
$i = $i+1;
?> 
<tr>
<td><?php echo $i."."; ?></td>
<td><a href="?barcode=<?php echo $row_1[barcode]; ?>"><?php echo $row_1[barcode]; ?></a></td>
<td><?php echo $row_1[quantity]; ?></td>
<td><?php echo get_name($row_1[barcode]); ?></td>
</tr>
<?php 
}; 
?> 
</table>





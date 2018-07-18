<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: cart.php
* This file handles clear() and proceed() request.
*/

error_reporting(E_ALL ^ E_NOTICE);
include('../lib/sql.php');//zz path forwardSlash tempForMac
include('../lib/user_lib.php');

check_user_cookie();

if($_GET['do'] == 'clear'){ //zz get的参数‘do’作为flag标识了本次执行的行动
	clear_cart();
}

if($_GET['do'] == 'proceed'){
	$sql_get_cart = "SELECT * FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."';";//zz proceed前check的逻辑是cart表中所有和当前用户相关的记录都会被使用（当做是全部的购物车--并没有购物车历史的记录，有的就是当前的）
	$result_cart = mysql_query($sql_get_cart);
	
	if ( mysql_num_rows($result_cart) == 0){
		exit("Empty Cart =_=, you have nothing to proceed!");
	}
	while ($cart_row = mysql_fetch_assoc($result_cart)) {
		$new_quantity = get_anything($cart_row[barcode],'quantity') + $cart_row[quantity];
		//zz 下面更新part主表的剩余数量、进行实质减法
		$update_sql = "UPDATE `".$cart_row[table]."` SET  `quantity` =  '".$new_quantity."' WHERE `barcode` =  '".$cart_row[barcode]."';";
		if (!($result=mysql_query($update_sql))) { 
			stop('DB Error!');
		}else{
			if($cart_row[quantity] != 0){
			    //zz tran()用于添加transaction表的记录(上面已经改了实质的part或者car表了，这里再添加上关于本次trans的信息到trans表)。type就是car或者part、就这两个string取其一。
				tran($_COOKIE['ew_user_name'],$cart_row[barcode],str_replace("ew_", "",$cart_row[table]),$cart_row[quantity],$cart_row[application]);
			}
		}
	}
	send_msg();
	$sql_del = "DELETE FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."';";
	if(!($result=mysql_query($sql_del))){ 
			stop('DB Error!');
		}else{
			echo "<p>Your List has been proceeded successfully!</p>";//zz 这个echo就相当于return一个string了、由于这个file是以ajax res的形式存在的
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

    <td>No.</td>
    <td>Barcode</td>
    <td>Amount</td>
    <td>Name</td>
    <td>Application</td>

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
    <td><?php echo $row_1["application"]; ?></td>
</tr>
<?php 
}; 
?> 
</table>





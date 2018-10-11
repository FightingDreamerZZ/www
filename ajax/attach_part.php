<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: attach_part.php
* This file performs associate part related requests.
*/
////error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

if(isset($_GET['main'])){
	$sql_code_1 = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$_GET['main']."' ORDER BY `rid` ASC;";
	$result_info_1 = mysql_query($sql_code_1);
}else{
	exit("oh no!");
}

if($_GET['do'] == 'add'){
	$sql_code_2 = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$_GET['main']."' AND `attach_part`='".$_GET['attach']."';";
	$result_2 = mysql_query($sql_code_2);
	if ( mysql_num_rows($result_2) == 0){
		$sql_code_3 ="INSERT INTO `ew_relation` (`rid`, `main_part`, `attach_part`, `amount`) VALUES (NULL, '".$_GET['main']."', '".$_GET['attach']."', '".$_GET['amount']."');";
		$result_3 = mysql_query($sql_code_3);
		die("<meta http-equiv=\"refresh\" content=\"0;URL=../edit_part.php?barcode=".$_GET['main']."\">");
	}else{
		exit("Relation already existed!");
	}
}

if($_GET['do'] == 'del'){
	$sql_code_2 = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$_GET['main']."' AND `attach_part`='".$_GET['attach']."';";
	$result_2 = mysql_query($sql_code_2);
	if ( mysql_num_rows($result_2) != 0){
		$sql_code_3 ="DELETE FROM `ew_relation` WHERE `main_part` = '".$_GET['main']."' AND `attach_part` = '".$_GET['attach']."';";
		$result_3 = mysql_query($sql_code_3);
		die("<meta http-equiv=\"refresh\" content=\"0;URL=../edit_part.php?barcode=".$_GET['main']."\">");
	}else{
		exit("Relation not existed!");
	}
}

if($_GET['option'] == 'view'){
	$view_option = true;
}

if($_GET['option'] == 'edit'){
	$edit_option = true;
}

?>

<table>
<tr>

<td>Barcode</td>
<td>Name</td>
<td>Amount</td>
<?php if($view_option){ echo "<td>Stock</td>";} ?> 
<?php if($view_option){ echo "<td>MAX SET</td>";} ?> 
<?php if($edit_option){ echo "<td>Action</td>";} ?> 
</tr>

<?php 
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
?> 

<tr>
<td><?php echo $row_1[attach_part]; ?></td>
<td><?php echo get_name($row_1[attach_part]); ?></td>
<td><?php echo $row_1[amount]; ?></td>
<?php if($view_option){ echo "<td>".get_anything($row_1[attach_part],'quantity')."</td>";} ?> 
<?php if($view_option){ echo "<td>".floor(get_anything($row_1[attach_part],'quantity')/$row_1[amount])."</td>";} ?> 
<?php if($edit_option){ echo "<td><a href=\"ajax/attach_part.php?do=del&main=".$_GET['main']."&attach=".$row_1[attach_part]."\">Del</td>";} ?> 
</tr>

<?php 
}; 
?> 
</table>





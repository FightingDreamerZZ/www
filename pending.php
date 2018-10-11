<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: pending.php
* This file performs pending pool operations
*/

error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();


//restore items from pending pool to inventory
if($_POST['cancel']){
	$sql_code_c = "SELECT * FROM `ew_pending` ORDER BY `pid` DESC;";
	$result_info_c = mysql_query($sql_code_c);
	while ($row_c = mysql_fetch_assoc($result_info_c)) { 
		if($_POST[$row_c["pid"]] == "1"){
			//code
			$new_quantity = get_anything($row_c[barcode],'quantity') + abs($row_c[quantity]);
			$update_sql = "UPDATE `".$row_c[table]."` SET  `quantity` =  '".$new_quantity."' WHERE `barcode` =  '".$row_c[barcode]."';";
			if (!($result=mysql_query($update_sql))) { 
				stop('DB Error!');
			}else{
				del_pending($row_c['pid']);
			}
		}
	}
}

//depart items from pending pool
if($_POST['depart']){
	$sql_code_c = "SELECT * FROM `ew_pending` ORDER BY `pid` DESC;";
	$result_info_c = mysql_query($sql_code_c);
	while ($row_c = mysql_fetch_assoc($result_info_c)) { 
		if($_POST[$row_c["pid"]] == "1"){
			//code
			tran($_COOKIE['ew_user_name'],$row_c[barcode],str_replace("ew_", "",$row_c[table]),$row_c[quantity]);
			//echo $row_c['pid'].",";
			del_pending($row_c['pid']);
		}
	}
	
}
$bar_str ="";
$sql_code = "SELECT * FROM `ew_pending` ORDER BY `pid` DESC;";
$result_info = mysql_query($sql_code);


include('header.php');
?>

<script type="text/javascript">
checked = false;
function checkedAll() 
{
	if (checked == false){
		checked = true;
	}else{
		checked = false;
	}
	for (var i = 0; i < document.getElementById('pending_pool').elements.length; i++) {
		document.getElementById('pending_pool').elements[i].checked = checked;
	}
}

</script>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">



<h2>Pending Pool</h2>

<form id="pending_pool" name="form2" method="post" action="pending.php" onsubmit="return confirm('Do you really want to process selected items?');" >


Check all: <input type='checkbox' name='checkall' onclick='checkedAll();'>
<input type="submit" name="cancel" class="submit_btn" value="Restore"/>
<input type="submit" name="depart" class="submit_btn" value="Depart"/>
<table>
<tr>

<td>CK</td>
<td>Barcode</td>
<td>Name</td>
<td>Type</td>
<td>Amount</td>
<td>User</td>
<td>Client</td>
<td>Time</td>

<?php 
while ($row = mysql_fetch_assoc($result_info)) { 
?> 
<tr>
<td><input type="checkbox" name="<?php echo $row["pid"]; ?>" value="1"></td>
<td><a href="view_<?php echo trim($row["table"], "ew_"); ?>.php?barcode=<?php echo $row["barcode"]; ?>" target="_blank"><?php echo $row["barcode"]; ?></a></td>
<td><?php echo get_name($row["barcode"]); ?></td>
<td><?php echo trim($row["table"], "ew_"); ?></td>
<td><?php echo $row["quantity"]; ?></td>
<td><?php echo $row["user"]; ?></td>
<td><?php echo $row["client"]; ?></td>
<td><?php echo $row["time"]; ?></td>

</tr>

<?php 
}; 
?> 

</table>
</form>

<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>
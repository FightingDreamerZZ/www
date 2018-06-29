<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: list.php
* This file display item lists based on inputs
*/
error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

$sqltag="";
$urltag="";
$default_sort=" ORDER BY `barcode` DESC ";

//check inventory
if ($_GET['check']=='inventory') { 
	$sqltag="WHERE `quantity` > '0'";
	$urltag=$urltag."&check=inventory";
}

//check inventory
if ($_GET['check']=='bin') { 
	$sqltag="WHERE `w_quantity` = '-1'";
	$urltag=$urltag."&check=bin";
}

//check out of stock
if ($_GET['check']=='out') { 
	$sqltag="WHERE `quantity` = '0' AND `w_quantity` != '-1'";
	$urltag=$urltag."&check=out";
}

//List of parts or cars? default cars
if (isset($_GET["table"])) { 
	$table = $_GET["table"];
	$urltag= $urltag."&table=$table";
} else { 
	$table = "ew_car";
	$urltag= $urltag."&table=ew_car";
}

//check short supply
if ($_GET['check']=='short') { 
	$sqltag= "WHERE `$table`.w_quantity > `$table`.quantity";
	$urltag=$urltag."&check=short";
}

//sort list based on inputs
if($_GET['sort']=='name'){
	$sort = " ORDER BY `name` ASC ";
	$urltag= $urltag."&sort=".$_GET['sort'];
}else if($_GET['sort']=='color'){
	$sort = " ORDER BY `color` ASC ";
	$urltag= $urltag."&sort=".$_GET['sort'];
}else if($_GET['sort']=='category'){
	$sort = " ORDER BY `category` ASC ";
	$urltag= $urltag."&sort=".$_GET['sort'];
}else{
	$sort = $default_sort;
}

//load lists with page spliter
$split_by = '40';

if (isset($_GET["page"])) { 
	$page = $_GET["page"]; 
} else { 
	$page=1; 
}
$start_from = ($page-1) * $split_by;
$sql_code_1 = "SELECT * FROM `".$table."` ".$sqltag.$sort."LIMIT ".$start_from.",".$split_by;
//$sql_code_1 = "SELECT * FROM `ew_car` WHERE `quantity` > '0' ORDER BY `barcode` ASC LIMIT ".$start_from.",".$split_by;
//echo $sql_code_1;
$result_info_1 = mysql_query($sql_code_1);

$sql_code_2 = "SELECT COUNT(barcode) FROM `".$table."` ".$sqltag.";"; 
//$sql_code_2 = "SELECT COUNT(barcode) FROM `ew_car` WHERE `quantity` > '0';"; 
$result_info_2 = mysql_query($sql_code_2);
$row_2 = mysql_fetch_row($result_info_2); 
$total_records = $row_2[0]; 
$total_pages = ceil($total_records / $split_by);

$title_by_page = "All Parts";
include('header.php');

?>
<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">

<p><?php echo($total_records); ?> result(s) was found in this query. Sort by <a href ="list.php?<?php echo trim_url("&sort="); ?>">[Default]</a> <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=name">[Name]</a> <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=color">[Color]</a>  <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=category">[Category]</a></p>


<p>Page:
<?php 

for ($i=1; $i<=$total_pages; $i++) { 
            echo "<a href='list.php?page=".$i.$urltag."'>".$i."</a> "; 
}; 
?>
</p>


<table>
<tr>

<td>Barcode</td>
<td>Name</td>
<td>Category</td>
<?php if($table == "ew_part"){echo "<td>For</td>";} ?>
<?php if($table == "ew_car"){echo "<td>Model</td>";} ?>
<td>Color</td>
<?php if($table == "ew_car"){echo "<td>Condition</td>";} ?>
<td>InStock</td>
<td>Warning</td>
<td>Location</td>
<td>Action</td>
</tr>
<?php 
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
?> 
            <tr>
            
            <td><a href="view_<?php if($table == "ew_car"){echo "car";}else{echo "part";} ?>.php?barcode=<?php echo $row_1["barcode"]; ?>"><?php echo $row_1["barcode"]; ?></a></td>
            <td><?php echo $row_1["name"]; ?></td>
			<td><?php echo $row_1["category"]; ?></td>
			<?php if($table == "ew_part"){echo "<td>".$row_1["sub_category"]."</td>";} ?>
			<?php if($table == "ew_car"){echo "<td>".$row_1["model"]."</td>";} ?>
			<td><?php echo $row_1["color"]; ?></td>
			<?php if($table == "ew_car"){echo "<td>".$row_1["condition"]."</td>";} ?>
            <td><?php echo $row_1["quantity"]; ?></td>
            <td><?php if($row_1["w_quantity"] =='0'){ echo "n/a";}else{ echo $row_1["w_quantity"];}; ?></td>
            <td><?php echo $row_1["l_zone"]."_".$row_1["l_column"]."_".$row_1["l_level"]; ?></td>
			<td><a href="edit_<?php if($table == "ew_car"){echo "car";}else{echo "part";} ?>.php?barcode=<?php echo $row_1["barcode"]; ?>">Edit</a></td>
            </tr>
<?php 
}; 
?> 
</table>


<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>
<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: admin/log.php
* This file displays a list of system logs with page spliter.
*/
error_reporting(E_ALL ^ E_NOTICE);

//echo($_COOKIE['ew_admin_verified']);
if(!$_COOKIE['ew_admin_name'] || !$_COOKIE['ew_admin_verified']){
	die('<meta http-equiv="refresh" content="0;URL=login.php">');
}

include('..\lib\sql.php');


//page spliter
$split_by = '30';

if (isset($_GET["page"])) { 
	$page  = $_GET["page"]; 
} else { 
	$page=1; 
}
$start_from = ($page-1) * $split_by;
$sql_code_1 = "SELECT * FROM `ew_log` ORDER BY `time` DESC LIMIT ".$start_from.",".$split_by;
$result_info_1 = mysql_query($sql_code_1);
$sql_code_2 = "SELECT COUNT(log_id) FROM `ew_log"; 
$result_info_2 = mysql_query($sql_code_2);
$row_2 = mysql_fetch_row($result_info_2); 
$total_records = $row_2[0]; 
$total_pages = ceil($total_records / $split_by); 


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Admin Panel</title>
</head>
<body>
<style>
table,th,td
{
border:1px solid black;
}
</style>
<p>Page:
<?php 
for ($i=1; $i<=$total_pages; $i++) { 
            echo "<a href='log.php?page=".$i."'>".$i."</a> "; 
}; 
?>
</p>

<table>
<tr>

<td>User</td>
<td>TIME</td>
<td>MESSAGE</td>
</tr>
<?php 
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
?> 
            <tr>
            
            <td><?php echo $row_1["user"]; ?></td>
            <td><?php echo $row_1["time"]; ?></td>
            <td><?php echo $row_1["msg"]; ?></td>
            </tr>
<?php 
}; 
?> 
</table>


</body>
</html>
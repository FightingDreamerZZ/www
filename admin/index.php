<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: admin/index.php
* This file displays an admin panel for admin users to choose which admin function they wish to perform.
*/

error_reporting(E_ALL ^ E_NOTICE);

//echo($_COOKIE['ew_admin_verified']);
if(!$_COOKIE['ew_admin_name'] || !$_COOKIE['ew_admin_verified']){
	die('<meta http-equiv="refresh" content="0;URL=login.php">');
}


//handle logout request
if($_GET['do']=='logout'){
	setcookie('ew_admin_name',null,time()-3600);
	setcookie('ew_admin_verified',null,time()-3600);
	die('<meta http-equiv="refresh" content="0;URL=login.php">');
}

//get user type name
function get_type($typecode){
	if($typecode =='1'){
		return "Super User";
	}else if($typecode =='2'){
		return "Warehouse User";
	}else if($typecode =='3'){
		return "Account User";
	}else if($typecode =='4'){
		return "CRM User";
	}else{
		return "UNKNOWN";
	}
}

include('..\lib\sql.php');


//page spliter
$split_by = '20';
if (isset($_GET["page"])) { 
	$page  = $_GET["page"]; 
} else { 
	$page=1; 
}
$start_from = ($page-1) * $split_by;
$sql_code_1 = "SELECT * FROM `ew_user` ORDER BY `user` ASC LIMIT ".$start_from.",".$split_by;
$result_info_1 = mysql_query($sql_code_1);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Admin Panel</title>
</head>
<body>
<?php echo("Hi, ".$_COOKIE['ew_admin_name']." "); ?>
<a href="?do=logout">Logout</a>
<ol>
	
	<li><a href="log.php">View Log</a></li>
	<li><a href="backup/index.php">Backup DB</a></li>
	<li><a href="cpass.php">Change Admin Password</a></li>
	<li><a href="new.php">Create New User</a></li>
	<li>Manage Existing User</li>
	
</ol>
<p>Page:
<?php 
$sql_code_2 = "SELECT COUNT(user) FROM `ew_user`"; 
$result_info_2 = mysql_query($sql_code_2);
$row_2 = mysql_fetch_row($result_info_2); 
$total_records = $row_2[0]; 
$total_pages = ceil($total_records / $split_by); 

for ($i=1; $i<=$total_pages; $i++) { 
            echo "<a href='index.php?page=".$i."'>".$i."</a> "; 
}; 
?>
</p>

<table>
<tr>

<td>USERNAME</td>
<td>PASSWORD</td>
<td>TYPE</td>
<td>ACTION</td>
</tr>
<?php 
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
?> 
            <tr>
            
            <td><?php echo $row_1["user"]; ?></td>
            <td><?php echo $row_1["pass"]; ?></td>
            <td><?php echo get_type($row_1["type"]); ?></td>
			<td><a href="manage.php?do=edit&user=<?php echo $row_1["user"]; ?>">Edit</a> <a href="manage.php?do=del&user=<?php echo $row_1["user"]; ?>">Delete</a></td>
            </tr>
<?php 
}; 
?> 
</table>


</body>
</html>
<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: admin/manage.php
* This file offers a panel to allow admin users to edit other users(change password/usertype, delete user).
*/
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
//echo($_COOKIE['ew_verified']);
if(!$_COOKIE['ew_admin_name'] || !$_COOKIE['ew_admin_verified']){
	die('<meta http-equiv="refresh" content="0;URL=login.php">');
}

include('..\lib\sql.php');
include('..\lib\admin_lib.php');

//handle delete user request
if($_GET['do']=='del'){
	$user = $_GET['user'];
	if($user =='otto'){
		stop('you dont have privilege to delete such a great user!');
	}
	if(!check_data($table_name='ew_user',$key_name='user',$user)){	
		echo("<script>window.alert('This user is not existed!');</script>");
		die('<meta http-equiv="refresh" content="0;URL=index.php">');
	}
	$sql_code = "DELETE FROM `ew_user` WHERE `user` = '".$user."';";
	if (!($result=mysql_query($sql_code))) { 
			
			mysql_close($link); 
			stop('DB Error!');
		}
		else{
			mysql_close($link);
			echo("<script>window.alert('User has been removed!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}
}

//handle edit user request
if($_GET['do']=='edit'){
	$user = $_GET['user'];
	$sql_code = "select * from ew_user where user = '".$user."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	$pass = $a_check[pass];
	$type = $a_check[type];
	
}

if($_POST['submit']){
	$pass = trim($_POST["pass"]);
	$type = $_POST["type"];
	check($pass,40,"New Password");
	if($pass_new != $pass_con){
		$msg = 'Two passwords you entered do not match!';
		stop($msg);
	}
	$sql_update = "UPDATE `ew_user` SET `pass` = '".$pass."', `type` = '".$type."' WHERE `user` = '".$user."';";
	if (!($result=mysql_query($sql_update))) { 
			
			mysql_close($link); 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=.php">');
		}
		else{
			mysql_close($link);
			echo("<script>window.alert('User Profile Edit Success!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Change Admin Password</title>
</head>
<body>

<form name="form" method="post">
<label>Type: </label>
<select name="type">
  <option value="1" <?php if($type == 1){ echo("selected=\"selected\"");} ?>)>Super User</option>
  <option value="2" <?php if($type == 2){ echo("selected=\"selected\"");} ?>)>Warehouse User</option>
  <option value="3" <?php if($type == 3){ echo("selected=\"selected\"");} ?>)>Account User</option>
  <option value="4" <?php if($type == 4){ echo("selected=\"selected\"");} ?>)>CRM User</option>
</select><br />
<label>Username: <?php echo($user); ?></label>
<br />
<label>Password:</label>
<input type="text" name="pass" value="<?php echo($pass); ?>"/><br />

<input type="submit" name="submit" value="Edit"/>
<a href="index.php">Go Back!</a>
</form>



</body>
</html>
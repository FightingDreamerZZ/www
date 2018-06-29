<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: admin/cpass.php
* This file handles change administrator password request.
*/

error_reporting(E_ALL ^ E_NOTICE);

//echo($_COOKIE['ew_verified']);
if(!$_COOKIE['ew_admin_name'] || !$_COOKIE['ew_admin_verified']){
	die('<meta http-equiv="refresh" content="0;URL=login.php">');
}

include('..\lib\sql.php');
include('..\lib\admin_lib.php');


if($_POST['submit']){
	$user = $_COOKIE['ew_admin_name'];
	$pass_old = trim($_POST["pass1"]);
	$pass_new = trim($_POST["pass2"]);
	$pass_con = trim($_POST["pass3"]);
	check($pass_old,40,"Old Password");
	check($pass_new,40,"New Password");
	check($pass_con,40,"Confirmed Password");
	if($pass_new != $pass_con){
		$msg = 'Two passwords you entered do not match!';
		stop($msg);
	}
	
	$sql_code = "select * from ew_admin where user = '".$user."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	$ew_verified = $a_check['pass'];
	if($ew_verified == $pass_old){
		$sql_update = "UPDATE `ew_admin` SET `pass` =  '".$pass_new."' WHERE `user` =  '".$user."';";
		if (!($result=mysql_query($sql_update))) { 
			
			mysql_close($link); 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=cpass.php">');
		}
		else{
			mysql_close($link);
			echo("<script>window.alert('Password changed, please login with new password!');</script>");
			setcookie('ew_admin_name',null,time()-3600);
			setcookie('ew_admin_verified',null,time()-3600);
			die('<meta http-equiv="refresh" content="0;URL=login.php">');
		}	
		
		
	}else{
	echo("<script>window.alert('Verification Failed!');</script>");
	die('<meta http-equiv="refresh" content="0;URL=cpass.php">');
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
<label>Username: <?php echo($_COOKIE['ew_admin_name']); ?></label>
<br />
<label>Old Password:</label>
<input type="password" name="pass1"/><br />
<label>New Password:</label>
<input type="password" name="pass2"/><br />
<label>Confirm Pass:</label>
<input type="password" name="pass3"/><br />
<input type="submit" name="submit" value="change"/>
</form>



</body>
</html>
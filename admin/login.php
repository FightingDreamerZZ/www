<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: admin/login.php
* This file provides a login portal for administrator.The input username and password will be compared to database(table ew_admin) records.
*/
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('../lib/sql.php');//zz path forwardSlash tempForMac

	
//handle admin login request
if($_GET['do']=='login'){
	$user = $_POST["user"];
	$pass = $_POST["pass"];
	$sql_code = "select * from ew_admin where user = '".$user."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	$ew_verified = $a_check['pass'];
	if($ew_verified == $pass){
		setcookie('ew_admin_name',$user,time()+3600);
		setcookie('ew_admin_verified',$ew_verified,time()+3600);
		die('<meta http-equiv="refresh" content="0;URL=index.php">');
		
	}else{
	echo("<script>window.alert('Verification Failed!');</script>");
	die('<meta http-equiv="refresh" content="0;URL=login.php">');
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Admin Panel Login</title>


</head>
<body>

<p>Login as Administrator:</p>
<form name="form" method="post" action="login.php?do=login">
<label>Username:</label>
<input type="text" name="user"/>
<br />
<label>Password:</label>
<input type="password" name="pass"/>
<input class="buttonbox" type="submit" name="submit" value="Login"/>
</form>


</body>
</html>
<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: admin/new.php
* This file offers a panel to allow admin users to add new users.
*/

////error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);


//echo($_COOKIE['ew_admin_verified']);
if(!$_COOKIE['ew_admin_name'] || !$_COOKIE['ew_admin_verified']){
	die('<meta http-equiv="refresh" content="0;URL=login.php">');
}

include('..\lib\sql.php');
include('..\lib\admin_lib.php');

//handle add new user request
if($_POST['submit']){
	$user = $_POST["user"];
	$type = $_POST["type"];
	$pass_new = trim($_POST["pass2"]);
	$pass_con = trim($_POST["pass3"]);
	check($user,40,"Username");
	check($pass_new,40,"New Password");
	check($pass_con,40,"Confirmed Password");
	
	if($pass_new != $pass_con){
		$msg = 'Two passwords you entered do not match!';
		stop($msg);
	}
	
	if(check_data($table_name='ew_user',$key_name='user',$user)){	
		echo("<script>window.alert('This user is existed!');</script>");
		die('<meta http-equiv="refresh" content="0;URL=new.php">');
	}
	
	$sql_code = "INSERT INTO `ew_user` (`user`, `pass`, `type`) VALUES ('".$user."', '".$pass_new."', '".$type."');";
	if (!($result=mysql_query($sql_code))) { 
			
			mysql_close($link); 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=new.php">');
		}
		else{
			mysql_close($link);
			echo("<script>window.alert('New User has been created!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}
	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Create New User</title>
</head>
<body>

<form name="form" method="post">
<label>Type: </label>
<select name="type">
  <option value="1" selected="selected")>Super User</option>
  <option value="2")>Warehouse User</option>
  <option value="3")>Account User</option>
  <option value="4")>CRM User</option>
</select><br />
<label>Username:</label>
<input type="text" name="user"/><br />
<label>Password:</label>
<input type="password" name="pass2"/><br />
<label>Confirm:</label>
<input type="password" name="pass3"/><br />
<input type="submit" name="submit" value="Create"/>
</form>




</body>
</html>
<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: cart.php
* This file handles clear() and proceed() request.
*/

error_reporting(E_ALL ^ E_NOTICE);
include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

if(isset($_POST['link'])){	
	echo "<iframe scrolling=\"yes\" height=\"550px\" width=\"430px\" src=\"".$_POST['link']."\"></iframe>";
	}


?>






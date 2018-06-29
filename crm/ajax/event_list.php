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

if(isset($_GET['date'])){
	$this_date = $_GET['date'];
	$sql_code = "SELECT * FROM `ec_commlog` WHERE `event_time` BETWEEN '".$this_date." 00:00:00' AND '".$this_date." 23:59:59';";
	//$sql_code = "SELECT * FROM `ec_commlog` WHERE `event_time` BETWEEN '".$this_date." 00:00:00' AND '".$this_date." 23:59:59' AND `user`='".$_COOKIE['ec_user_name']."';";
	$result_info = mysql_query($sql_code);
		
	}

?>

<ol class="sidebar_link_list">

<?php 
while ($row_1 = mysql_fetch_assoc($result_info)) { 
	echo "<li onclick=\"load_event(".$row_1["lid"].")\">".$row_1["title"]."</li>";
}
?> 
</ol>


<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: cart.php
* This file handles clear() and proceed() request.
*/

error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

if(isset($_GET['lid'])){
	$lid = $_GET['lid'];
	$sql_code = "SELECT * FROM `ec_commlog` WHERE `lid` = '$lid';";
	$result_info = mysql_query($sql_code);
		
	}

?>

<ol class="sidebar_link_list">

<?php 
while ($row_1 = mysql_fetch_assoc($result_info)) { 
?>
<li><a href="view.php?cid=<?php echo $row_1["client"]."#".$row_1["lid"]; ?>" ><?php echo $row_1["title"]; ?></a></li>
<li>User: <?php echo $row_1["user"]; ?></li>
<li>Client: <?php echo get_cname($row_1["client"]); ?></li>
<li>Location: <?php echo $row_1["location"]; ?></li>
<li>Time: <?php echo substr($row_1["event_time"],0,-3); ?></li>
<li>Content: <br /><?php echo $row_1["content"]; ?></li>

<?php
}
?> 
</ol>


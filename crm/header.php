<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: header.php
* page header
*/

$sql_code_hevent = "SELECT * FROM `ec_commlog` WHERE `user` = '".$_COOKIE['ec_user_name']."' AND `event_time` != '0000-00-00 00:00:00' ORDER BY `event_time` DESC LIMIT 0,10;";
//echo $sql_code_1;
$result_info_hevent = mysql_query($sql_code_hevent);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Elaine Relations Lite</title>
<meta name="keywords" content="Elaine Relations, Elaine CRM" />
<meta name="description" content="Elaine Relations - Customer Relationship Management for Enterprise." />
<?php if(isset($loader1)){ echo($loader1);} ?>

<link href="css/style.css" rel="stylesheet" type="text/css" />
<?php if(isset($loader2)){ echo($loader2);} ?>

</head>
<body<?php echo($load); ?>>

<div id="wrapper">
	<div id="header">
    	<div class="header_box left">
        	<div id="site_title"><a href="index.php"></a></div>
        </div> <!-- END of headar box -->
        <div class="event_box left">
		<p class="right">Today's Date: <?php echo date("Y-m-d"); ?></p>
			<h3><a href="#">My Events</a></h3>
			
<ol>
<?php 
while ($hevent = mysql_fetch_assoc($result_info_hevent)) { 
?> 
<?php 
if($hevent["event_time"] > date("Y-m-d H:i:s")){
	echo "<li>[".substr($hevent["event_time"],0,-3)."] ".$hevent["title"]." ... <a href=\"view.php?cid=".$hevent["client"]."#".$hevent["lid"]."\">[more]</a></li>";
}else{
	echo "<li style=\"text-decoration: line-through;\">[".substr($hevent["event_time"],0,-3)."] ".substr($hevent["title"],0,30)." ... <a href=\"view.php?cid=".$hevent["client"]."#".$hevent["lid"]."\">[more]</a></li>";
}
?>
<?php 
}; 
?>
</ol>
        </div><!-- END of event -->
        <div class="header_box right">
            <ul id="menu">
                <li><a href="index.php" class="home">Main</a></li>
                <li><a href="search.php" class="about">Search</a></li>
                <li><a href="calendar.php" class="gallery">Calendar</a></li>
                <li><a href="customer.php" class="blog">Customer</a></li>
                <li><a href="commlog.php" class="contact">CommLog</a></li>
            </ul>
			<p class = "right"><a href="../index.php?do=logout">[LOGOUT]</a></p>
        </div> <!-- END of headar box -->
        <div class="clear"></div>
    </div> <!-- END of header -->
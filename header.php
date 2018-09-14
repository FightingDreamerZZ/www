<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: header.php
* page header
*/
if(!$_COOKIE['ew_user_name']){
	$logout ="&nbsp;";
}else{
	$logout ="Hi, ".$_COOKIE['ew_user_name']."~ Getting Tired? >> <a href=\"index.php?do=logout\">Logout</a>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php //$title_by_page = "Default Page"?>
<title><?php if(isset($title_by_page)){echo $title_by_page." - ";}?>AGT Warehouse Management System</title>
<meta name="keywords" content="Elaine Warehouse" />
<meta name="description" content="Elaine Warehouse - inventory control with barcode scanner embedded." />
<!--    //zz 还预留了子页可能需要的css或js文件的ref-->
<?php if(isset($loader1)){ echo($loader1);} ?>

<link href="css/style.css" rel="stylesheet" type="text/css" />
<?php if(isset($loader2)){ echo($loader2);} ?>

<!--zz including jQuery library-->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"
            integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body<?php echo($load); ?>>
<span id="top"></span>
<div id="wrapper">
	<div id="header">
        <div id="site_title">
            <h1><a href="index.php">Elaine Warehouse</a></h1>
        </div>	
		<div id="logout"><?php echo($logout); ?></div>	
        <div id="menu">
            <ul>
				<li><a href="index.php">Panel</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="enter.php">Arrive</a></li>
                <li><a href="depart.php">Depart</a></li>
                <li class="last"><a href="stats.php">Stats</a></li>
            </ul>    	
        </div> <!-- end of menu -->
	</div> <!-- end of header -->
	
	<div id ="demo"></div>
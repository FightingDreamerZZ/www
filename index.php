<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: index.php
* This file display a user panel to access all frequently used functions.
*/
error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');




//handle logout request
if($_GET['do']=='logout'){
	sys_log($_COOKIE['ew_user_name'],"logout system.");
	setcookie('ew_user_name',null,time()-3600);
	setcookie('ea_user_name',null,time()-3600);
	setcookie('ec_user_name',null,time()-3600);
	setcookie('ew_user_verified',null,time()-3600);
	setcookie('ea_user_verified',null,time()-3600);
	setcookie('ec_user_verified',null,time()-3600);
    setcookie('is_to_omit_cart',null,time()-3600);
    setcookie('is_to_omit_cart_enter_page',null,time()-3600);
    setcookie('is_warehouse_admin',null,time()-3600);
    die('<meta http-equiv="refresh" content="0;URL=login.php">');
}

$display_plugin = check_user_cookie();

//load some stats information
function stats($sql_staus){
	if($result_info_s = mysql_query($sql_staus)){
		$row_s = mysql_fetch_row($result_info_s); 
	}else{
		return "ERROR";
	}
	if($row_s[0]==""){
		return '0';
	}else{
		return $row_s[0]; 
	}
}

$stats[total_short] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE `ew_part`.w_quantity > `ew_part`.quantity;");
$stats[total_out] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`quantity` = '0' AND `w_quantity` != '-1');");
$stats[total_cart] = stats("SELECT COUNT(barcode) FROM `ew_cart` WHERE (`user` = '".$_COOKIE['ew_user_name']."');");
$stats[total_bin] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`w_quantity` = '-1');");

$title_by_page = "Index";
include('header.php');
//print_r($_COOKIE);
//echo date("Ymd") . sprintf("%04s", 1);
?>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">



<h2>WAREHOUSE SYSTEM PANEL</h2>

<form name="form2" method="get" action="search.php">
	<select name="table" class="select_field">
        <option value="ew_part">Part</option>
	    <option value="ew_car">Car</option>
	</select>
    <input type="text" name="keyword" class="input_field" value="<?php echo $temp_key; ?>" autocomplete="off"/>
	<input type="submit" class="submit_btn" value="Search"/>
	</form>

<div class="cleaner h20"></div>
	
<div class="edit_warehouse">
<ul>
	<a href="pending.php"><li><img src="images/icon/pending.png">Pending Pool</li></a>
	<a href="tran_list.php"><li><img src="images/icon/trans.png">Transactions</li></a>
	<a href="msg.php"><li><img src="images/icon/msg.png">Message Center</li></a>
	<a href="cart.php"><li><img src="images/icon/cart.png">Otto Cart[<?php echo $stats[total_cart]; ?>]</li></a>
	<a href="serial.php"><li><img src="images/icon/serial.png">Serial Input</li></a>
	<a href="images/map.gif" target="_blank"><li><img src="images/icon/map.png">Warehouse Map</li></a>
</ul>
</div>

<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>


<div class="content_box_top"></div>
<div class="content_box">

<h2>CAR CONTROL PANEL</h2>


<div class="edit_warehouse">
<ul>
	<a href="new_car.php"><li><img src="images/icon/car.png">Add New Car</li></a>
	<a href="view_car.php"><li><img src="images/icon/barcode.png">Barcode Search</li></a>
	<a href="list.php?check=inventory&table=ew_car"><li><img src="images/icon/carinven.png">Car Inventory</li></a>
	<a href="search.php?table=ew_car&keyword=semi"><li><img src="images/icon/ass.png">Assembling Cars</li></a>
	<a href="search.php?table=ew_car&keyword=damaged"><li><img src="images/icon/fix.png">Fix Damaged</li></a>
	<a href="vin.php"><li><img src="images/icon/vin.png">VIN Trace</li></a>
</ul>
</div>


<div class="cleaner h10"></div>

<h2>PART CONTROL PANEL</h2>


<div class="edit_warehouse">
<ul>
	<a href="new_part.php"><li><img src="images/icon/part.png">Add New Part</li></a>
	<a href="view_part.php"><li><img src="images/icon/barcode.png">Barcode Search</li></a>
	<a href="list.php?check=inventory&table=ew_part"><li><img src="images/icon/partinven.png">Part Inventory</li></a>
	<a href="list.php?check=short&table=ew_part"><li><img src="images/icon/short.png">Shortage[<?php echo $stats[total_short]; ?>]</li></a>
	<a href="list.php?check=out&table=ew_part"><li><img src="images/icon/out.png">Empty[<?php echo $stats[total_out]; ?>]</li></a>
	<a href="list.php?check=bin&table=ew_part"><li><img src="images/icon/bin.png">Disabled Part[<?php echo $stats[total_bin]; ?>]</li></a>
</ul>
</div>



<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>


<div class="content_box_top" <?php echo $display_plugin; ?>></div>
<div class="content_box" <?php echo $display_plugin; ?>>



<h2>Elaine Enterprise Plugins</h2>

<div class="edit_warehouse">
<ul>
	<a href="account/index.php"><li><img src="images/icon/account.png">Elaine Account</li></a>
	<a href="crm/index.php"><li><img src="images/icon/crm.png">Elaine Relations</li></a>

</ul>
</div>

<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom" <?php echo $display_plugin; ?>></div>

</div> <!-- end of main -->
<?PHP
include('footer.php');
?>
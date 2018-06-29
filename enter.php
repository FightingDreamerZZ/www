<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: enter.php
* This file performs enter related functions
*/
error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//load profile if barcode is set
if (isset($_GET['barcode'])) { 
	$barcode = $_GET['barcode'];
	$table = get_table($barcode);
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	$cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);
}

//handle barcode scanner inputs
if($_POST['submitbarcode']){
	$barcode = $_POST['focus_on'];
	$table = get_table($barcode);
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	cart($_COOKIE['ew_user_name'],$barcode,1,$table);
	$cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);
	
}

//handle manual enter request
if($_POST['increase']){

	if($_POST['amount'] <= 0){
		stop("Increase amount must be greater than 0!");
	}
	$barcode = $_POST['barcode'];
	$increase = $_POST['amount'];
	$table = get_table($barcode);
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	cart($_COOKIE['ew_user_name'],$barcode,$increase,$table);
	$cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);
	
}

$load = " onload=\"load()\"";
$title_by_page = "Arrive";
include('header.php');

?>
<script type="text/javascript">
	function loadXMLDoc()
	{
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("mycart").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/cart.php",true);
	xmlhttp.send();
	}
	
	function clearcart()
	{
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("mycart").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/cart.php?do=clear",true);
	xmlhttp.send();
	}
	
	function proceed_cart()
	{
	var xmlhttp;
	var r=confirm("Are you willing to proceed you list?");
	if (r==true){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("mycart").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/cart.php?do=proceed",true);
	xmlhttp.send();
	}
	}
	

   function load()
   {
      document.form1.focus_on.focus();
	  loadXMLDoc();
   }
</script>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">

<h2>Barcode Quick Enter</h2>
<div class="cleaner"></div>
<div class="col_w320 float_l">
<form name="form1" method="post">
	<label>Scan Barcode:</label>
	<input type="text" name="focus_on" class="input_field_w w180" autocomplete="off"/>
	<input type="submit" class="submit_btn" name="submitbarcode" value="Scan"/>
</form>

<ul class = "list">
	<li>Barcode: <?php echo $barcode;?></li>
	<li>Name: <?php echo $a_check[name];?></li>
	<li>Current Stock: <?php echo $a_check[quantity];?></li>
	<li>Stock Change: <?php echo $cart_amount;?></li>
	<li>Expect Stock: <?php echo $a_check[quantity]+$cart_amount;?></li>
<form name="form2" method="post">
	<input type="text" style="display:none;" name="barcode" value = "<?php echo $barcode;?>" autocomplete="off"/>
	ENTER:<input type="text" name="amount" value = "0" class="input_field_w w50" autocomplete="off"/>
	<input type="submit" class="submit_btn" name="increase" value="More"/>
</form>
</ul>

<p>Detail Information: <a href="<?php if($table == "ew_part"){echo "view_part.php";}else{echo "view_car.php";}?>?barcode=<?php echo $barcode;?>" target="_blank"><?php echo $barcode;?></a><br /><?php echo $a_check[xsearch];?></p>
<a href="<?php echo($a_check['photo_url']); ?>" target="_blank"><img width="300" height="300" class ="withborder" src="<?php echo($a_check['photo_url']); ?>" class="image_wrapper" /></a>
</div>

<div class="col_w320 float_r">
	<h4>Otto's Cart</h4>
	<button type="button" class="submit_btn" onclick="clearcart()">Clear List</button>
	<button type="button" class="submit_btn" onclick="proceed_cart()">Proceed List</button>
	<div id="mycart"></div>

</div>

<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>
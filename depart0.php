<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: depart.php
* This file performs depart related functions
*/

error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//load associate part info
$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
$result_info_a = mysql_query($sql_code_a);

//load part info
if (isset($_GET['barcode'])) { 
	$barcode = $_GET['barcode'];
	$table = get_table($barcode);
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	$cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);//zz cart_amount 即是本次提件的所提走的零件总数（对应于这个item、这个user的）
	$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
	$result_info_a = mysql_query($sql_code_a);//zz ??用途
}

//handle barcode scaner input
if($_POST['submitbarcode']){
	$barcode = $_POST['focus_on'];
	$table = get_table($barcode);
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	if(($a_check[quantity]+cart_amount($_COOKIE['ew_user_name'],$barcode)) < 1){
		stop('Not enough stock!');
	}else{
		cart($_COOKIE['ew_user_name'],$barcode,-1,$table);
	}
	$cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);
	$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
	$result_info_a = mysql_query($sql_code_a);

}

// handle request: add associate parts to cart
if($_POST['add_assoc_part']){
	$barcode = $_POST['barcode'];
	$table = get_table($barcode);
	$set = $_POST['set_amount'];
	
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	
	$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
	$result_info_a = mysql_query($sql_code_a);
	while ($row_a = mysql_fetch_assoc($result_info_a)){
		if($_POST[$row_a["attach_part"]] == "1"){//zz post的这个	"attach_part"好像是个flag、为1时说明正在做assoc的拿取
			if((get_anything($row_a["attach_part"],"quantity") - ($row_a["amount"]*$set) + cart_amount($_COOKIE['ew_user_name'],$row_a["attach_part"])) < 0){
				stop('Not enough stock!');
			}
		}
	}
	
	$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
	$result_info_a = mysql_query($sql_code_a);
	while ($row_a = mysql_fetch_assoc($result_info_a)){
		if($_POST[$row_a["attach_part"]] == "1"){		
			cart($_COOKIE['ew_user_name'],$row_a["attach_part"],-abs($row_a["amount"]*$set),$table);
		}
	}
	
	$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
	$result_info_a = mysql_query($sql_code_a);

}

//handle manual input depart request
if($_POST['decrease']){ //zz post的'decrease'是个flag吗？
	if($_POST['amount'] <= 0){
		stop("Decrease amount must be greater than 0!");
	}
	$barcode = $_POST['barcode'];
	$decrease = -abs($_POST['amount']);
	$table = get_table($barcode);
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	if(($a_check[quantity]+cart_amount($_COOKIE['ew_user_name'],$barcode)) < 1){
		stop('Not enough stock!');
	}else{
		cart($_COOKIE['ew_user_name'],$barcode,$decrease,$table);
	}
	$cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);
	$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
	$result_info_a = mysql_query($sql_code_a);
}


$load = " onload=\"load()\"";
$title_by_page = "Depart";
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
	
	function pending()
	{
	var xmlhttp;
	var client = document.getElementById("client").value;
	if (client.length==0){client = "default client";}
	var r=confirm("Are you willing to pend you list to "+client+"?");
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
	xmlhttp.open("GET","ajax/pending.php?pendto="+client,true);
	xmlhttp.send();
	}
	}
	
	checked = false;
	function checkedAll() 
	{
		if (checked == false){
			checked = true;
		}else{
			checked = false;
		}
		for (var i = 0; i < document.getElementById('assoc_part').elements.length; i++) {
			document.getElementById('assoc_part').elements[i].checked = checked;
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

<h2>Barcode Quick Depart</h2>
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
	<li>Previous Stock: <?php echo $a_check[quantity];?></li>
	<li>Stock Change: <?php echo $cart_amount;?></li>
	<li>Expect Stock: <?php echo $a_check[quantity]+$cart_amount;?></li>
	<form name="form2" method="post">
	<input type="text" style="display:none;" name="barcode" value = "<?php echo $barcode;?>" autocomplete="off"/>
	DEPART:<input type="text" name="amount" value = "0" class="input_field_w w50" autocomplete="off"/>
	<input type="submit" class="submit_btn" name="decrease" value="More"/>
</form>		
</ul>


<p>Detail Information: <a href="<?php if($table == "ew_part"){echo "view_part.php";}else{echo "view_car.php";}?>?barcode=<?php echo $barcode;?>" target="_blank"><?php echo $barcode;?></a><br /><?php echo $a_check[xsearch];?></p>
<a href="<?php echo($a_check['photo_url']); ?>" target="_blank"><img width="300" height="300" class ="withborder" src="<?php echo($a_check['photo_url']); ?>" class="image_wrapper" /></a>
</div>



<div class="col_w320 float_r">
	<h4>Associate Parts</h4>
	<form id="assoc_part" name="assoc_part" method="post" onsubmit="return confirm('Do you really want to process selected items?');" >
	<input type="text" style="display:none;" name="barcode" value = "<?php echo $barcode;?>" autocomplete="off"/>
	Check all: <input type='checkbox' name='checkall' onclick='checkedAll();'>
	ADD:<input type="text" name="set_amount" class="input_field_w w50" value="0" autocomplete="off"/>SET(s)
	<input type="submit" name="add_assoc_part" class="submit_btn" value="to Cart"/>
		<table>
		<tr>

		<td>CK</td>
		<td>Barcode</td>
		<td>Name</td>
		<td>Amount</td>
		<td>Stock</td>
		<td>MAX SET</td>
		</tr>

		<?php 
		while ($row_a = mysql_fetch_assoc($result_info_a)) { 
		?> 

		<tr>
		<td><input type="checkbox" name="<?php echo $row_a['attach_part']; ?>" value="1"></td>
		<td><?php echo $row_a['attach_part']; ?></td>
		<td><?php echo get_name($row_a['attach_part']); ?></td>
		<td><?php echo $row_a['amount']; ?></td>
		<td><?php echo get_anything($row_a['attach_part'],'quantity') ?></td>
		<td><?php echo floor(get_anything($row_a['attach_part'],'quantity')/$row_a[amount]) ?></td> 
		</tr>

		<?php 
		}; 
		?> 
		</table>
	</form>

	<div class="cleaner h20"></div>
	
	<h4>Otto's Cart</h4>
	<button type="button" class="submit_btn" onclick="clearcart()">Clear</button>
	<button type="button" class="submit_btn" onclick="proceed_cart()">Proceed</button>
	<button type="button" class="submit_btn" onclick="pending()">Pend to</button>
	<input type="text" id="client" class="input_field_w w60" value="" autocomplete="off"/>
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
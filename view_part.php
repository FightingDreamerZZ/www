<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: view_part.php
* This file displays part profile based on input barcode.
*/

error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');//zz path forwardSlash tempForMac
include('lib/user_lib.php');

check_user_cookie();

//load profile if barcode is given
if (isset($_GET['barcode'])) { 
	$barcode = $_GET['barcode'];
	if(check_data('ew_part','barcode',$barcode)){
		$sql_code = "select * from ew_part where barcode = '".$barcode."';";
		$result_info = mysql_query($sql_code);
		$a_check = mysql_fetch_array($result_info);
	}else{
		stop("Barcode not found!");
	}
	
	
}

$load = " onload=\"load()\"";
$title_by_page = "View Part";
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
		document.getElementById("attach_part").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/attach_part.php?option=view&main=<?php echo($a_check['barcode']); ?>",true);
	xmlhttp.send();
	}
	
   function load()
   {
      document.form1.keyword.focus();
	  loadXMLDoc();
   }
</script>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">

<h2>View Parts</h2>
<div class="cleaner"></div>
			
<p><form name="form1" method="post" action="search.php?do=barcode">
Barcode Search: <input type="text" name="keyword" autocomplete="off"/>
<input type="submit" name="submit" value="Go"/>
</form></p>
<div class="cleaner h30"></div>
<div class="col_w320 float_r">
<ul class = "list">
	<li>Name: <?php echo($a_check['name']); ?></li>
	<li>Barcode: <?php echo($a_check['barcode']); ?></li>
	<li>Part Number: <?php echo($a_check['part_num']); ?></li>
	<li>Category: <a href="search.php?table=ew_part&keyword=<?php echo($a_check['category']); ?>"><?php echo($a_check['category']); ?></a></li>
	<li>For: <a href="search.php?table=ew_part&keyword=<?php echo($a_check['sub_category']); ?>"><?php echo($a_check['sub_category']); ?></a></li>
	<li>Color: <a href="search.php?table=ew_part&keyword=<?php echo($a_check['color']); ?>"><?php echo($a_check['color']); ?></a></li>
	<li>Purchase Price: <?php echo($a_check['p_price']); ?></li>
	<li>Wholesale Price: <?php echo($a_check['w_price']); ?></li>
	<li>Retail Price: <?php echo($a_check['r_price']); ?></li>
	<li>Quantity: <?php echo($a_check['quantity']); ?></li>
	<li>Stock Warning: <?php echo($a_check['w_quantity']); ?></li>
	<li>Location: <a href="search.php?table=ew_part&keyword=<?php echo($a_check['l_zone']."_".$a_check['l_column']."_".$a_check['l_level']); ?>"><?php echo($a_check['l_zone']."_".$a_check['l_column']."_".$a_check['l_level']); ?></a></li>
	<li>Latest Update: <?php echo($a_check['date']); ?></li>
	<li>Description: <?php echo($a_check['des']); ?></li>
	
</ul>
</div>

<div class="col_w320 float_l">
<h4>Photo Preview</h4>              
<a href="<?php echo($a_check['photo_url']); ?>" target="_blank"><img width="300" height="300" class ="withborder" src="<?php echo get_thumb($a_check['photo_url']); ?>" class="image_wrapper" /></a>
<p>
<a href="edit_part.php?barcode=<?php echo($a_check['barcode']); ?>">[Edit Profile]</a>
<a href="enter.php?barcode=<?php echo($a_check['barcode']); ?>">[Quick Enter]</a>
<a href="depart.php?barcode=<?php echo($a_check['barcode']); ?>">[Quick Depart]</a>
</p>
                
</div>  

<div class="cleaner h20"></div>

<h4>Associated Part</h4>  
<div id="attach_part"></div>


<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>
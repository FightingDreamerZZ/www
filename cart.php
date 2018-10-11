<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: car.php
* This file offers cart operations such as edit, clear, del etc.
*/
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//zz handler for check if is warehouse admin
$is_warehouse_admin = false;
if($_COOKIE['is_warehouse_admin'] && $_COOKIE['is_warehouse_admin'] == 'true'){
    $is_warehouse_admin = true;
}

//handle change amount request
if(isset($_GET['barcode']) && isset($_GET['new_value']) && $_GET['new_value'] != "" && $_GET['barcode'] != ""){
	$barcode = $_GET['barcode'];
	$new_value = $_GET['new_value'];
	$stock = get_anything($barcode,"quantity");
	//echo $stock;
	if(($stock + $new_value) < 0){
		stop('Not enough stock! Stock Available:'.$stock);
	}else{
		$sql_code = "UPDATE `ew_cart` SET `quantity` = '".$new_value."' WHERE `barcode` = '".$barcode."' AND `user` = '".$_COOKIE['ew_user_name']."';";
		$result_update = mysql_query($sql_code);
	}
}

//load cart--unsubmitted
$sql_get_cart = "SELECT * FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."' AND `pending` = 'false';";
$result_cart = mysql_query($sql_get_cart);

//load cart--submitted
$sql_get_cart_submitted = "SELECT * FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."' AND `pending` = 'true';";
$result_cart_submitted = mysql_query($sql_get_cart_submitted);

include('header.php');

?>

<script type="text/javascript">
	function change(id_num)
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
		document.getElementById(id_num).innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/cart_edit.php?barcode="+id_num,true);
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
	
	function submit_or_proceed_cart()
	{
	var xmlhttp;
	var r=confirm("Are you willing to <?php echo ($is_warehouse_admin)?"proceed all of the cart to database? Please note: this process is irreversible and has to be taken seriously..":
        "submit all of the cart to get approved for proceeding?";?>");
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
            window.location.reload(true);
		    // document.getElementById("mycart").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/cart.php?do=<?php echo ($is_warehouse_admin)?'proceed':'submit';?>",true);
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
	

</script>
<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">
<div id ="mycart" >
<h2>MY CART</h2>
<p>Hint: double click on the target amount would activate edit mode.</p>

<button type="button" class="submit_btn" onclick="clearcart()">Clear</button>
	<button type="button" class="submit_btn" onclick="submit_or_proceed_cart()"><?php echo ($is_warehouse_admin)?"Proceed":"Submit";?></button>
	<button type="button" class="submit_btn" onclick="pending()">Pend to</button>
	<input type="text" id="client" class="input_field_w w180" value="" autocomplete="off"/>

<table>
<tr>

<td>Barcode</td>
<td>Name</td>
<td>Application</td>
<td>Amount</td>

</tr>
<?php 
while ($row_1 = mysql_fetch_assoc($result_cart)) { 
?> 
            <tr>
            <td><?php echo $row_1["barcode"]; ?></td>
            <td><?php echo get_name($row_1["barcode"]); ?></td>
            <td><?php echo $row_1["application"]; ?></td>
            <td id="<?php echo $row_1["barcode"]; ?>" ondblclick="change('<?php echo $row_1["barcode"]; ?>')" onblur="changed()"><?php echo $row_1["quantity"]; ?></td>
            </tr>
<?php 
}; 
?> 
</table>
</div>

    <div class="with_hr">&nbsp;</div>
    <h6>All submitted entities:</h6>
    <table>
        <tr>

            <td>Barcode</td>
            <td>Name</td>
            <td>Application</td>
            <td>Amount</td>

        </tr>
        <?php
        while ($row_1 = mysql_fetch_assoc($result_cart_submitted)) {
            ?>
            <tr>
                <td><?php echo $row_1["barcode"]; ?></td>
                <td><?php echo get_name($row_1["barcode"]); ?></td>
                <td><?php echo $row_1["application"]; ?></td>
                <td><?php echo $row_1["quantity"]; ?></td>
            </tr>
            <?php
        };
        ?>
    </table>

<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>
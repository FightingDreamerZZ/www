<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: car.php
* This file offers cart operations such as edit, clear, del etc.
*/
error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

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

//load cart
$sql_get_cart = "SELECT * FROM `ew_cart` WHERE `user` = '".$_COOKIE['ew_user_name']."';";
$result_cart = mysql_query($sql_get_cart);

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

    $(document).ready(function() {
        // put your jQuery code here.
    });
    function showHideChangePopUp(m) {
        var disp = m === 'hide' ? 'none' : 'block';
        document.getElementById('div_change_qty').style.display = disp;
    }
    function onclick_cb_deny(thisEle){
        if(thisEle.checked){
            thisEle.nextElementSibling.style.display = "block";
            // var c = confirm("To check this option means you also omit the buffer and comparison function provided by the cart. Proceed with caution cause the changes made will be harder to reverse so will be the mistakes.");
            // if(c == true)
            //     sendHttpRequest("depart.php",{"is_to_omit_cart":true},"post");
            // else
            //     document.getElementById("cb_ooc").checked = false;
        }
        else if (!thisEle.checked){
            thisEle.nextElementSibling.style.display = "none";
            // sendHttpRequest("depart.php",{"is_to_omit_cart":false},"post");

        }
    }
</script>
<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">
<div id ="mycart" >
<h2>OTTO'S CART</h2>
<p>Hint: double click on the target amount would activate edit mode.</p>

<button type="button" class="submit_btn" onclick="clearcart()">Clear</button>
	<button type="button" class="submit_btn" onclick="proceed_cart()">Proceed</button>
	<button type="button" class="submit_btn" onclick="pending()">Pend to</button>
	<input type="text" id="client" class="input_field_w w180" value="" autocomplete="off"/>

<table>
    <tr>

        <td>Barcode</td>
        <td>Name</td>
        <td>Category</td>
        <td>Amount</td>
        <td>Deny<br/>(with comments)</td>

    </tr>
<?php 
while ($row_1 = mysql_fetch_assoc($result_cart)) { 
?> 
            <tr>
                <td><?php echo $row_1["barcode"]; ?></td>
                <td><?php echo get_name($row_1["barcode"]); ?></td>
                <td><?php echo trim($row_1["table"], "ew_"); ?></td>
                <td id="<?php echo $row_1["barcode"]; ?>" ondblclick="change('<?php echo $row_1["barcode"]; ?>')" onblur="changed()"><?php echo $row_1["quantity"]; ?></td>
                <td>
                    <input type="checkbox" name="cb_<?php echo $row_1["barcode"]; ?>" value="1" onclick="onclick_cb_deny(this)">
                    <form name="form_comment_<?php echo $row_1["barcode"]; ?>" id="form_comment_<?php echo $row_1["barcode"]; ?>" style="display: none;">
                        <label class="w200">Pls leave your comment here:</label>
                        <input type="text" name="text_comment" class="input_field_w w180" autocomplete="off"/>
                        <input type="submit" class="submit_btn" name="submit_comment" value="Save"/>
                    </form>
                </td>
            </tr>
<?php 
}; 
?> 
</table>
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
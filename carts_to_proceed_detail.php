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

if(!isset($_GET['user'])){
    echo("<script>window.alert('Page Loading Error!');</script>");
    die('<meta http-equiv="refresh" content="0;URL=index.php">');
}

$user_of_cart = $_GET['user'];

//zz handler double-click to edit qty or appli -- main submit
if(isset($_GET['new_value']) && $_GET['new_value'] != ""){
    $barcode = $_GET['barcode'];
    $appli = $_GET['appli'];
	$new_value = $_GET['new_value'];
    $field = $_GET['field'];
	$stock = get_anything($barcode,"quantity");

	switch($field){
        case "appli":
            cart_edit($user_of_cart, $barcode, "", $new_value, $appli);
            break;
        default:
            if(($stock + $new_value) < 0){
                stop('Not enough stock! Stock Available:'.$stock);
            }else{
                cart_edit($user_of_cart, $barcode,$new_value,"",$appli);
            }
            break;
    }
}

//load cart
$sql_get_cart = "SELECT * FROM `ew_cart` WHERE `user` = '".$user_of_cart."';";
$result_cart = mysql_query($sql_get_cart);

include('header.php');

?>

<script type="text/javascript">
    //zz listener -- doubleClick to edit quantity
	function edit_qty(id_num,appli)
	{
	var xmlhttp;
	var field = "qty";
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
		document.getElementById(field+id_num+appli).innerHTML=xmlhttp.responseText;
		}
	  };
	xmlhttp.open("GET","ajax/cart_edit.php?barcode="+id_num
	    +"&appli="+appli
	    +"&field="+field
	    +"&user=<?php echo $user_of_cart;?>",true);
	xmlhttp.send();
	}

    function edit_appli(id_num,appli)
    {
        let xmlhttp;
        let field = "appli";
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
                document.getElementById(field+id_num+appli).innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","ajax/cart_edit.php?barcode="+id_num
            +"&appli="+appli
            +"&field="+field
            +"&user=<?php echo $user_of_cart;?>",true);
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
	xmlhttp.open("GET","ajax/cart.php?do=clear" +
        "&user_of_cart=<?php echo $user_of_cart;?>",true);
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
	xmlhttp.open("GET","ajax/cart.php?do=proceed" +
        "&user_of_cart=<?php echo $user_of_cart;?>",true);
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
	xmlhttp.open("GET","ajax/pending.php?pendto="+client +
        "&user_of_cart=<?php echo $user_of_cart;?>",true);
	xmlhttp.send();
	}
	}

	//zz

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
        <td>Application</td>
        <td>Amount</td>
        <td>Deny<br/>(with comments)</td>

    </tr>
<?php 
while ($row_1 = mysql_fetch_assoc($result_cart)) { 
?> 
            <tr>
                <td><?php echo $row_1["barcode"]; ?></td>
                <td><?php echo get_name($row_1["barcode"]); ?></td>
                <td id="<?php echo 'appli'.$row_1['barcode'].$row_1['application']; ?>"
                    ondblclick="edit_appli('<?php echo $row_1["barcode"]; ?>','<?php echo $row_1["application"]; ?>')"
                    onblur="changed()"><?php echo $row_1["application"]; ?></td>
                <td id="<?php echo 'qty'.$row_1['barcode'].$row_1['application']; ?>"
                    ondblclick="edit_qty('<?php echo $row_1["barcode"]; ?>','<?php echo $row_1["application"]; ?>')"
                    onblur="changed()"><?php echo $row_1["quantity"]; ?></td>
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
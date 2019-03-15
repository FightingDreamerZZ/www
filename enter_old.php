<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: enter.php
* This file performs enter related functions
*/
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');//zz path forwardSlash tempForMac
include('lib/user_lib.php');

check_user_cookie();

//zz handler for check if is warehouse admin (able to omitOttosCart)
$is_warehouse_admin = false;
if($_COOKIE['is_warehouse_admin'] && $_COOKIE['is_warehouse_admin'] == 'true'){
    $is_warehouse_admin = true;
}

//zz handler for get cookie for storing checkbox (omit ottos cart)'s check status, toggle style
if($_COOKIE['is_to_omit_cart_enter_page']){
    //echo "cookie!cookie=".$_COOKIE['is_to_omit_cart'];
    $cookie_is_to_omit_cart = $_COOKIE['is_to_omit_cart_enter_page'];
}

//zz handler for set cookie for storing checkbox (omit ottos cart)'s check status
if($_POST['is_to_omit_cart']){
    $cookie_is_to_omit_cart = $_POST['is_to_omit_cart'];
    setcookie("is_to_omit_cart_enter_page",$_POST['is_to_omit_cart'],time() + 60*60*2);
    //echo "cookie!post=".$_POST['is_to_omit_cart'];
}

//load profile if barcode is set
if (isset($_GET['barcode']) && !isset($_GET['is_edit_cart'])) {
	$barcode = $_GET['barcode'];
	$table = get_table($barcode);
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	$cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);
}

//zz --handler for cart edit (cart entity edit request) - data filling only:
if($_GET['is_edit_cart'] == 'true'){
    $barcode = $_GET['barcode'];
    $table = get_table($barcode);
    $appli_old = $_GET['appli']; //zz this is from ajax's cart.php, $_GET['appli'] is on the href url for edit..
    $is_edit_cart = "true";

    $sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
    $result_info = mysql_query($sql_code);
    $a_check = mysql_fetch_array($result_info);
    $cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);
    $sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
    $result_info_a = mysql_query($sql_code_a);
}

//handle barcode scanner inputs
if($_POST['submitbarcode']){
	$barcode = $_POST['focus_on'];
	$table = get_table($barcode);
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	//zz resumed auto-enter or auto-increase...
	cart($_COOKIE['ew_user_name'],$barcode,1,$table,"N/A");//zz cart($user,$barcode,$quantity,$table,$appli)
	$cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);
	
}

//handle manual enter request, also "edit cart" submit
if($_POST['submit_confirm_increase']){

	if($_POST['amount'] <= 0){
		stop("Increase amount must be greater than 0!");
	}
	$barcode = $_POST['barcode'];
	$increase = abs($_POST['amount']);
	$table = get_table($barcode);
    $is_edit_cart_final_submit = ($_POST['is_edit_cart'] == 'true')?true:false;

    $sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);

    //zz codes below to be improved...
    if ($cookie_is_to_omit_cart && ($cookie_is_to_omit_cart == "true") && (!$is_edit_cart_final_submit)){
        $msg_direct_depart = direct_depart_or_enter($_COOKIE['ew_user_name'], $barcode, $increase, $table, "N/A");
    }
    elseif(!$is_edit_cart_final_submit) {
        cart($_COOKIE['ew_user_name'], $barcode, $increase, $table, "N/A");//zz 注意cart()除了create同时还有edit的功能
    }
    else {//case for cart edit
        cart_edit($_COOKIE['ew_user_name'], $barcode, $increase, "N/A", "N/A");
    }

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
		document.getElementById("mycart").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/cart.php?do=<?php echo ($is_warehouse_admin)?'proceed':'submit';?>",true);
	xmlhttp.send();
	}
	}
	

   function load()
   {
      document.form1.focus_on.focus();
	  loadXMLDoc();
   }

    //zz javascript mocked html form submition http req (post/get)
    function sendHttpRequest(path, params, method) {
        let formForPosting = document.createElement("form");
        formForPosting.setAttribute("method", method);
        formForPosting.setAttribute("action", path);

        for (var key in params) {
            if (params.hasOwnProperty(key)){
                var hiddenInputTag = document.createElement("input");
                hiddenInputTag.setAttribute("type","hidden");
                hiddenInputTag.setAttribute("name", key);
                hiddenInputTag.setAttribute("value",params[key]);

                formForPosting.appendChild(hiddenInputTag);
            }
        }

        document.body.appendChild(formForPosting);
        formForPosting.submit();
    }

    //zz catching checkbox(omitOttosCart) click event
    function onclick_cb_ooc(){
        if(document.getElementById("cb_ooc").checked){
            var c = confirm("To check this option means you also omit the buffer and comparison function provided by the cart. Proceed with caution cause the changes made will be harder to reverse so will be the mistakes.");
            if(c == true)
                sendHttpRequest("enter.php",{"is_to_omit_cart":true},"post");
            else
                document.getElementById("cb_ooc").checked = false;
        }
        else{
            sendHttpRequest("enter.php",{"is_to_omit_cart":false},"post");

        }
    }

    //zz catch submit btn last double check last defense
    function directWriteLastCheck() {
        var c = confirm("msg double check present");
        if(c == true){
            //do nothing
        }
        else{
            //JS的stop()或后退
        }
    }

    //zz JS Handler for smartSearch's keyUp event
    function suggest(key)
    {
        document.getElementById("suggestion").style.display = "block";
        var xmlhttp;
        //var table = document.getElementById("db_table").value;
        var table = "ew_part";
        var postdata = "keyword="+encodeURIComponent(key)+"&table="+table;
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
                document.getElementById("suggestion").innerHTML=xmlhttp.responseText;
            }
        }

        xmlhttp.open("POST","ajax/search_suggestion.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.setRequestHeader("Content-length", postdata.length);
        xmlhttp.send(postdata);

    }
</script>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">

<h2>Barcode Quick Enter</h2>

    <form name="form_smart_search" method="get" action="search.php" >
        <span>Smart Search for Parts:&nbsp;</span>
        <input type="hidden" name="table" value="ew_part"/>
        <input type="text" id="keyword" name="keyword" class="input_field" value="<?php //echo $temp_key; ?>" autocomplete="off" onkeyup="suggest(this.value)"/>
        <input type="submit" class="submit_btn" value="Search"/>
    </form>

    <div id="suggestion" style="display: none"></div>

<div class="cleaner"></div>
<div class="col_w320 float_l">
<form name="form1" method="post" action="enter.php">
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
<form name="form2" method="post" action="enter.php">
    <!--zz pre-set values:-->
	<input type="text" style="display:none;" name="barcode" value = "<?php echo $barcode;?>" autocomplete="off"/>
    <input type="text" style="display:none;" name="is_edit_cart" value = "<?php echo ($is_edit_cart)?$is_edit_cart:"";?>" autocomplete="off"/>
    <input type="text" style="display:none;" name="appli_old" value = "<?php echo ($appli_old)?$appli_old:"";?>" autocomplete="off"/>

    <li>
        Received Quantity:&nbsp;
        <input type="text" name="amount" value = "1" class="input_field_w w50" autocomplete="off"/>
    </li>
    <li style="
            <?php
                if(!$is_warehouse_admin){
                    echo "display:none;";
                }
            ?>">
        Omit Otto's Cart (Admin Required):
        <input type="checkbox" onclick="onclick_cb_ooc()" id="cb_ooc" name="cb_ooc" value=""
            <?php if($cookie_is_to_omit_cart == "true")echo "checked";?>
        />
    </li>
	<input type="submit" class="submit_btn" name="submit_confirm_increase" style="float: right;margin-right: 20px"
           value="<?php
                    if ($cookie_is_to_omit_cart == "true")
                        echo "Confirm & Directly Write to DB";
                    elseif ($is_edit_cart == "true")
                        echo "Edit Record";
                    else
                        echo "Confirm";
                  ?>" onclick="
	    <?php
            if($cookie_is_to_omit_cart == 'true')
                echo 'directWriteLastCheck()';
        ?>"/><br/>
</form>
</ul>

<p>Detail Information: <a href="<?php if($table == "ew_part"){echo "view_part.php";}else{echo "view_car.php";}?>?barcode=<?php echo $barcode;?>" target="_blank"><?php echo $barcode;?></a><br /><?php echo $a_check[xsearch];?></p>
<a href="<?php echo($a_check['photo_url']); ?>" target="_blank"><img width="300" height="300" class ="withborder" src="<?php echo($a_check['photo_url']); ?>" class="image_wrapper" /></a>
</div>

<div class="col_w320 float_r">
    <div style="
            <?php
            if ($cookie_is_to_omit_cart == "true")
                echo "display: none";
            ?>">
        <h4>My Cart</h4>
        <button type="button" class="submit_btn" onclick="clearcart()">Clear List</button>
        <button type="button" class="submit_btn" onclick="submit_or_proceed_cart()"><?php echo ($is_warehouse_admin)?"Proceed List":"Submit List";?></button>
        <div id="mycart"></div>
    </div>
    <div id="msg_direct_depart">
        <?php
        if($msg_direct_depart){
            echo $msg_direct_depart;
        }
        ?>
    </div>
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
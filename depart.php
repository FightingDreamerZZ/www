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
	$barcode = $_POST['focus_on'];//zz ?? 谁在focus_on里面放得barcode？《--原来是form当中的名字。。form用法：submitBtn的name可用来判断是否有submit（post参数里是否有）、具体的textbox的name可以用来取出所传的post参数. 别的就是form的action啊method啊
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
	$result_info_a = mysql_query($sql_code_a);//zz ??用途

}

// handle request: add associate parts to cart
if($_POST['add_assoc_part']){
	$barcode = $_POST['barcode'];
	$table = get_table($barcode);
	$set = $_POST['set_amount']; //zz set就是几组assocParts、可随便定义不见得非得与main同
	
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	
	$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
	$result_info_a = mysql_query($sql_code_a);
	while ($row_a = mysql_fetch_assoc($result_info_a)){
		if($_POST[$row_a["attach_part"]] == "1"){	//zz post的这个	"attach_part"好像是个flag、为1时说明正在做assoc的拿取
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
if($_POST['decrease']){  //zz decrease是随便起的名字、其实是那个给用户自定义取多少件的小form、这是其handler
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
	function loadXMLDoc()//zz 怎么看都像是用来处理购物车cart的、由于没有任何post参数、应该就只是load购物车当前的信息的
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
	
	function clearcart()//zz清空购物车
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
	
	function proceed_cart() //zz 购物车结账也是直接来、啥参数都不用传（get、post）、用的是cookie里的参数早都有了（user），具体逻辑在ajax/cart.php
    //注意transaction的记录是发生在这步之后的、也就是说只放在购物车里相当于进了缓存还是不会买、只有proceed了这台购物车才算是flush/commit了、也会留下transaction（）
    //详见ajax/cart.php
	{
	var xmlhttp;
	var r=confirm("Are you willing to proceed you list?");//zz php的confirm用法、学习
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
	
	function pending() //zz 待查：pending以及下面的pending.php到底是干啥的？
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
	function checkedAll() //zz 重要、学起来、“check all” checkbox的js写法。。
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

   document.addEventListener('DOMContentLoaded', function () {
       document.form_application.radio_application.onchange=changeEventHandler;
   },false);
	function changeEventHandler(event) {
        if(!event.target.value)
            alert("haha");
        else
            alert("heihei"+event.target.value);
    }
</script>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">

<h2>Barcode Quick Depart</h2>
<div class="cleaner"></div>
<div class="col_w320 float_l">

<!--zz 这里不太懂、就算点击了sumit按钮可以让界面刷新并进行ajex读取cart等、仍不知道究竟这个input哪里refer了post的“barcode”参数、《--啊原来是有这两个关键字的都是用的nameAttribute还是form不熟啊。。 -->
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
    <li>Application of the Depart:
        <span id="label_application_selected"></span>
        <form name="form_application" id="form_application" method="post">
            <input type="radio" name="radio_application" value="unknown" id="form_application_radio_unknown" checked />
            <label for="form_application_radio_unknown"  >Unknown</label> <br/>
            <input type="radio" name="radio_application" value="sold_retail" id="form_application_radio_sold_retail"/>
            <label for="form_application_radio_sold_retail"  >Sold as retail</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio_application" value="sold_wholesale" id="form_application_radio_sold_wholesale"/>
            <label for="form_application_radio_sold_wholesale"  >Sold as wholesale</label> <br/>
            <input type="radio" name="radio_application" value="consumed_repair" id="form_application_radio_consumed_repair"/>
            <label for="form_application_radio_consumed_repair"  >Consumed in repair</label>
            <input type="radio" name="radio_application" value="consumed_assembly" id="form_application_radio_consumed_assembly"/>
            <label for="form_application_radio_consumed_assembly"  >Consumed in assembly</label>
        </form>
        <style>
            #form_application label{
                width: 240px;
                display: inline;
                font-size: 12px;
                margin-right: 0px;
            }
        </style>
    </li>
	<form name="form2" method="post">
<!--        zz 传递已有参数用form时，用displayNone的textBox-->
	    <input type="text" style="display:none;" name="barcode" value = "<?php echo $barcode;?>" autocomplete="off"/>
	    Or customize a number:&nbsp;<input type="text" name="amount" value = "0" class="input_field_w w50" autocomplete="off"/>
	    <input type="submit" class="submit_btn" name="decrease" value="to depart"/>
    </form>
</ul>


<p>Detail Information:
    <a href="
        <?php
            if($table == "ew_part"){
                echo "view_part.php";
            }
            else{echo "view_car.php";}
        ?>?barcode=<?php echo $barcode;?>"
       target="_blank">
        <?php echo $barcode;?>
    </a>
    <br />
<!--    //zz xsearch应该是专为searchSuggestion功能弄的一个column，有全部各个域的value串成一长string，也被用于display part details-->
    <?php echo $a_check[xsearch];?>
</p>
<a href="<?php echo($a_check['photo_url']); ?>"
   target="_blank">
    <img width="300" height="300" class ="withborder" src="<?php echo($a_check['photo_url']); ?>" class="image_wrapper" />
</a>
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
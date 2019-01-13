<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: depart.php
* This file performs depart related functions
*/

//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');//zz path forwardSlash tempForMac
include('lib/user_lib.php');

check_user_cookie();

//zz handler for check if is warehouse admin (able to omitOttosCart)
$is_warehouse_admin = false;
if($_COOKIE['is_warehouse_admin'] && $_COOKIE['is_warehouse_admin'] == 'true'){
    $is_warehouse_admin = true;
}

//zz handler for get cookie for saving checkbox (omit ottos cart) check status
if($_COOKIE['is_to_omit_cart']){
    //echo "cookie!cookie=".$_COOKIE['is_to_omit_cart'];
    $cookie_is_to_omit_cart = $_COOKIE['is_to_omit_cart'];
}

//zz handler for set cookie for saving checkbox (omit ottos cart) check status
if($_POST['is_to_omit_cart']){
    $cookie_is_to_omit_cart = $_POST['is_to_omit_cart'];
    setcookie("is_to_omit_cart",$_POST['is_to_omit_cart'],time() + 60*60*2);
    //echo "cookie!post=".$_POST['is_to_omit_cart'];
}

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

//zz filling data into input fields, and auto -1 (auto add a -1 record with unknown appli)
if($_POST['submitbarcode']){
	$barcode = $_POST['focus_on'];//zz ?? 谁在focus_on里面放得barcode？《--原来是form当中的名字。。form用法：submitBtn的name可用来判断是否有submit（post参数里是否有）、具体的textbox的name可以用来取出所传的post参数. 别的就是form的action啊method啊
	$table = get_table($barcode);
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);

    if (!$cookie_is_to_omit_cart || !($cookie_is_to_omit_cart == "true")){
        $decrease_this_time = -1;
        if(($a_check[quantity]+cart_amount($_COOKIE['ew_user_name'],$barcode)) < 1){
            stop('Not enough stock!');
        }else{
//            if($_GET['application']){
//                cart($_COOKIE['ew_user_name'],$barcode,$decrease_this_time,$table,$_GET['application']);//zz 一提交barcode或一扫描就自动添加一个购物车记录（减1的）
//            }
//            else{
//                cart($_COOKIE['ew_user_name'],$barcode,$decrease_this_time,$table,"unknown");
//            }
//            cart_just_scanned($_COOKIE['ew_user_name'],$barcode,$table);//zz 自动-1且always添加新纪录
            cart($_COOKIE['ew_user_name'],$barcode,-1,$table,"unknown");//zz 自动-1、且会合并3相等的。。
        }
    }

    $suggested_decrease = -1;

	$cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);//zz 同时返回这条购物车记录的变化数量一栏（也就是"-1"）
	$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
	$result_info_a = mysql_query($sql_code_a);
}

//zz --handler for cart entity editing request - data filling only:
if($_GET['is_edit_cart'] == 'true'){
    $barcode = $_GET['barcode'];
    $table = get_table($barcode);
    $appli_old = $_GET['appli']; //zz this is from ajax's cart.php, $_GET['application'] is on the href url for edit..
    $is_edit_cart = "true";

    $sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
    $result_info = mysql_query($sql_code);
    $a_check = mysql_fetch_array($result_info);
    $suggested_decrease = -1;
    $cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);
    $sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
    $result_info_a = mysql_query($sql_code_a);
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
		if($_POST[$row_a["attach_part"]] == "1"){	//zz 查看post里是否有这barcode，其是个flag（其实就是那个table里的checkbox）、为1时说明正在做assoc的拿取
			if((get_anything($row_a["attach_part"],"quantity") - ($row_a["amount"]*$set) + cart_amount($_COOKIE['ew_user_name'],$row_a["attach_part"])) < 0){//zz 这边没太看懂、为何还要再加个cart_amount()？？原有的数量、减去拿了多少套*每套的数量就得了呗？
				stop('Not enough stock!');
			}
		}
	}


	$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
	$result_info_a = mysql_query($sql_code_a);
	while ($row_a = mysql_fetch_assoc($result_info_a)){
		if($_POST[$row_a["attach_part"]] == "1"){
            if($_GET['application']) {
                cart($_COOKIE['ew_user_name'], $row_a["attach_part"], -abs($row_a["amount"] * $set), $table, $_GET['application']);
            }
            else {
                cart($_COOKIE['ew_user_name'], $row_a["attach_part"], -abs($row_a["amount"] * $set), $table, "unknown");
            }
		}
	}
	
	$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
	$result_info_a = mysql_query($sql_code_a);

}

//zz now is main handler for final submit and write to DB (or write to cart if not ooc), also handles cart entity editing to write to cart
if($_POST['submit_confirm_decrease']){  //zz decrease是随便起的名字、其实是那个给用户自定义取多少件的小form、这是其handler
	if($_POST['amount'] >= 0){
		stop("Decrease amount must be smaller than 0!");
	}
	$barcode = $_POST['barcode'];//zz ?之前post的数据这次post也能也还在吗？--其使用了hidden的input，form常用手法，以及连续的保存post/get scope数据，学起来
	$decrease = -abs($_POST['amount']);
    $decrease_this_time = $decrease;
    $appli = $_POST['radio_application'];
    $is_edit_cart = ($_POST['is_edit_cart'] == 'true')?true:false;
    $appli_old = $_POST['appli_old'];

	$table = get_table($barcode);
	$sql_code = "select * from `".$table."` where barcode = '".$barcode."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);

	//zz codes below to be improved...
	if ($cookie_is_to_omit_cart && ($cookie_is_to_omit_cart == "true") && (!$is_edit_cart)){
        if($a_check[quantity] < -$decrease){
            stop('Not enough stock!');
        }else{
            $msg_direct_depart = direct_depart_or_enter($_COOKIE['ew_user_name'], $barcode, $decrease, $table, $appli);
            ////zz 待总结：ctrl flow与msg的写法
//            echo "
//                <script>
//                    var msg_direct_depart = ".$msg.";
//                </script>";
        }
    }
    elseif(!$is_edit_cart) {
        if(($a_check[quantity]+cart_amount($_COOKIE['ew_user_name'],$barcode)) < -$decrease){//zz 关于这个逻辑待研究 - cart_amount这边可能有些问题
            stop('Not enough stock!');
        }else{
            cart($_COOKIE['ew_user_name'], $barcode, $decrease, $table, $appli);//zz 注意cart()除了create同时还有edit的功能

        }
    }
    else{//the case for cart editing
        if(($a_check[quantity]) < -$decrease){ //zz 关于这个逻辑待研究 - cart_amount这边可能有些问题（加不加cart_amount那一长串？）
            stop('Not enough stock!');
        }else{
            cart_edit($_COOKIE['ew_user_name'], $barcode, $decrease, $appli, $appli_old);
        }
    }

	$cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);
	$sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
	$result_info_a = mysql_query($sql_code_a);
}

//zz 现在用不到了
//zz handler for radioBtnGrp - field "application" selecting: reload the page to update field "application"
if($_GET['application']){
    $barcode = $_GET['barcode'];
    $cart_amount = cart_amount($_COOKIE['ew_user_name'],$barcode);
    $table = get_table($barcode);
    $appli = $_GET['application'];

    //$decrease_this_time = $_GET['decrease_this_time'];
    ////zz uncomment:
    //cart($_COOKIE['ew_user_name'],$barcode,$cart_amount,$table,$appli);

    $sql_code_a = "SELECT * FROM `ew_relation` WHERE `main_part` = '".$barcode."' ORDER BY `rid` ASC;";
    $result_info_a = mysql_query($sql_code_a);
}

//$load = " onload=\"load()\"";
//$title_by_page = "Depart";
//include('header.php');
include_template_header_css_sidebar_topbar(" onload=\"load()\"","Shipping","depart");

?>
<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(
            {container:'body', trigger: 'hover', placement:"bottom"}
        );
    });
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
	
	function submit_or_proceed_cart() //zz 购物车结账也是直接来、啥参数都不用传（get、post）、用的是cookie里的参数早都有了（user），具体逻辑在ajax/cart.php
    //注意transaction的记录是发生在这步之后的、也就是说只放在购物车里相当于进了缓存还是不会买、只有proceed了这台购物车才算是flush/commit了、也会留下transaction（）
    //详见ajax/cart.php
	{
	var xmlhttp;
	var r=confirm("Are you willing to <?php echo ($is_warehouse_admin)?"proceed all of the cart to database? Please note: this process is irreversible and has to be taken seriously..":
        "submit all of the cart to get approved for proceeding?";?>");//zz php的confirm用法、学习
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
	
	function pending() //zz 现有的pending系统：在depart等页的cart部分点击pend按钮，可实质修改db、并添加一条pending表的记录，在pending页有所有的pending表的记录，flush按钮只是用来添加trans记录的因为实质db已改、不过倒是有个restore按钮可以rollback对实质表的那个修改。。
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
      // document.form1.focus_on.focus();//focus on 这个form的这个txtbox
	  loadXMLDoc();//读取ajax返回的cart table

	  //zz if(post里有focus_on等刚submit的痕迹){document.getElementById("redAlertTxtLblForRdBtnAppli").innerHTML = "Pls select where the part goes.."}
   }

   // //zz add listener for radioBtnGroup ("application"field) 's selecting(changing selected option) event, add action function
   // document.addEventListener('DOMContentLoaded', function () {
   //     var arrayRadio = document.form_application.radio_application;
   //
   //     for(var i = 0; i < arrayRadio.length; i++) {
   //          arrayRadio[i].onchange = changeEventHandler;
   //     }
   // },false);

    ////zz add listener for radioBtnGroup ("application"field) 's selecting(changing selected option) event, define action
	//let valueSelectedInRadioGroup = "unknown";
	//function changeEventHandler(event) {
     //   if(!event.target.value) {
     //       alert("Error on getting value of the radio button..");
     //       return;
     //   }
    //
     //   valueSelectedInRadioGroup = event.target.value;
     //   document.getElementById("hidden_input_appli").value = valueSelectedInRadioGroup;
     //   document.getElementById("lbl_appli").innerHTML = valueSelectedInRadioGroup;
     //   //sendHttpRequest("depart.php",{"application":valueSelectedInRadioGroup,
     //   //                                "barcode":document.getElementById("barcode_main").value,
     //   //                                "decrease_this_time":<?php ////echo $decrease_this_time;?>////},"GET");
	//}

	//zz function(s) to remind user if they didnt select field"application" accidentally..

	//zz toBeContinued。。
	// function ajaxUpdateFieldAppli(selectedAppli) {
     //    let xmlHttpReq;
     //    if(window.XMLHttpRequest){
     //        //ie7+, firefox, safari, chrome, opera
     //        xmlHttpReq = new XMLHttpRequest();
     //    }
     //    else {
     //        //ie5,6
     //        xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
     //    }
     //    xmlHttpReq.onreadystatechange = function () {
     //        if (xmlHttpReq.readyState == 4 && xmlHttpReq.status == 200) {
     //            //update innerHTML
     //            //document.createElement("div")
     //            document.getElementById("mycart").innerHTML = xmlHttpReq.responseText;
     //        }
     //    }
     //    xmlHttpReq.open("GET","www.google.ca/search?q="+selectedAppli,true);
     //    xmlHttpReq.send();
    // }

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
        function onclick_cb_ooc(){
            if(document.getElementById("cb_ooc").checked){
                var c = confirm("To check this option means you also omit the buffer and comparison function provided by the cart. Proceed with caution cause the changes made will be harder to reverse so will be the mistakes.");
                if(c == true)
                    sendHttpRequest("depart.php",{"is_to_omit_cart":true},"post");
                else
                    document.getElementById("cb_ooc").checked = false;
            }
            else{
                sendHttpRequest("depart.php",{"is_to_omit_cart":false},"post");

            }
        }
	//zz last double check last defense
	function directWriteLastCheck() {
        var c = confirm("msg double check present");
        if(c == true){
            //do nothing
        }
        else{
            //JS的stop()或后退
        }
    }

    //zz toBeContinueToResearch...
    document.getElementById("btn_edit").addEventListener("click",function(e) {
        // if(e.target && e.target.nodeName == "button") {
            alert("haha");
        // }
    });

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
<style>
    .a-underline-zz {

        text-decoration: underline;

    }
</style>

    <!-- page content -->
    <div class="right_col" role="main">

        <!--page-title-->
        <div class="page-title">
            <div class="title_left">
                <h2>Shipping</h2>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">
            <!--zz x_panel left-->
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
<!--                    <div class="x_title">-->
<!--                        <h4>Parts to be shipped<small></small></h4>-->
<!--                        <div class="clearfix"></div>-->
<!--                    </div>-->
                    <div class="x_content">
                        <h3>Parts to be shipped<small></small></h3>
                        <br />
                        <form class="form-horizontal form-label-left" name="form_barcode_scan" method="post" action="depart.php">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="font-size: medium">Scan Barcode:</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" name="focus_on" class="form-control">
                                        <span class="input-group-btn">
                                            <input type="submit" name="submitbarcode" class="btn btn-primary" value="Go!" />
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr/>

                        <form name="form_confirm_decrease" method="post" id="form_confirm_decrease" action="depart.php" class="form-horizontal form-label-left">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Barcode</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="barcode" disabled
                                           value="<?php echo $barcode; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="name" disabled
                                           value="<?php echo($a_check['name']); ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Original Stock</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="name" disabled
                                           value="<?php echo($a_check['quantity']); ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Shipping Quantity</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="amount"
                                           value="<?php if($suggested_decrease){echo $suggested_decrease;}else{echo "1";}?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-sm-3 col-xs-12 control-label">Application of the Depart</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <div class="radio">
                                        <label title="The purpose of this departing is unknown..">
                                            <input type="radio" class="flat" checked name="radio_application" value="unknown" id="form_application_radio_unknown"/> Unknown
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label title="This part is sold to an individual customer..">
                                            <input type="radio" class="flat" name="radio_application" value="sold_retail" id="form_application_radio_sold_retail"/> Sold as retail
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label title="This part is sold to a dealer..">
                                            <input type="radio" class="flat" name="radio_application" value="sold_wholesale" id="form_application_radio_sold_wholesale"/> Sold as wholesale
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label title="Consumed when repairing a vehicle in warranty..">
                                            <input type="radio" class="flat" name="radio_application" value="consumed_repair" id="form_application_radio_consumed_repair"/> Warranty
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label title="The part was used when assembling cars..">
                                            <input type="radio" class="flat" name="radio_application" value="consumed_assembly" id="form_application_radio_consumed_assembly"/> Consumed in assembly
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-sm-3 col-xs-12 control-label">Omit Otto's Cart</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" onclick="onclick_cb_ooc()" id="cb_ooc" name="cb_ooc" value=""
                                                <?php if($cookie_is_to_omit_cart == "true")echo "checked";?>
                                                   class="flat"> Write to database directly without cart? (Admin Required)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <input type="text" style="display:none;" name="barcode" value = "<?php echo $barcode;?>" autocomplete="off"/>
                            <input type="text" style="display:none;" name="is_edit_cart" value = "<?php echo ($is_edit_cart)?$is_edit_cart:"";?>" autocomplete="off"/>
                            <input type="text" style="display:none;" name="appli_old" value = "<?php echo ($appli_old)?$appli_old:"";?>" autocomplete="off"/>
                            <div class="form-group">
                                <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                    <input type="submit" name="submit_confirm_decrease" id="submit_confirm_decrease" class="btn btn-success"
                                           value="<?php
                                                       if ($cookie_is_to_omit_cart == "true")
                                                           echo "Confirm & Directly Write to DB";
                                                       elseif ($is_edit_cart == "true")
                                                           echo "Edit Record";
                                                       else
                                                           echo "Confirm";
                                                   ?>"
                                           onclick="<?php
                                                        if($cookie_is_to_omit_cart == 'true')
                                                            echo 'directWriteLastCheck()';
                                                   ?>"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!--zz x_panel left-->
            <!--zz x_panel upper right-->
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
<!--                    <div class="x_title">-->
<!--                        <h4>Add Associate Parts<small></small></h4>-->
<!--                        <div class="clearfix"></div>-->
<!--                    </div>-->
                    <div class="x_content">
                        <h3>Add Associate Parts<small></small></h3>
                        <br />
                        <form class="form-inline" name="assoc_part" id="assoc_part" method="post" onsubmit="return confirm('Do you really want to append selected associate parts?');">
                            <input type="text" style="display:none;" name="barcode" value = "<?php echo $barcode;?>"/>
                            <div class="form-group">
                                <label class="control-label" style="font-size: medium">Check all:</label>
                                <input type="checkbox" name='checkall' onclick='checkedAll()'/>
                            </div>
                            &nbsp;&nbsp;
                            <div class="form-group">
                                <label class="control-label" style="font-size: medium">Add: </label>
                                <input type="text" name="set_amount" value="0" autocomplete="off" class="form-control"/>
                                <label class="control-label" style="font-size: medium">set(s). </label>
                            </div>
                            &nbsp;&nbsp;
                            <input type="submit" name="add_assoc_part" value="Confirm" class="btn btn-primary"/>
                            <br />
                            <br />
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Barcode</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Stock</th>
                                        <th>MAX SET</th>
                                    </tr>
                                </thead>

                                <tbody>
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
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div><!--zz x_panel upper right-->
            <div class="col-md-6 col-sm-6 col-xs-12"  style="
                <?php
                if ($cookie_is_to_omit_cart == "true")
                    echo "display: none";
                ?>"><!--zz x_panel lower right-->
                <div class="x_panel">
                    <!--                    <div class="x_title">-->
                    <!--                        <h4>Add Associate Parts<small></small></h4>-->
                    <!--                        <div class="clearfix"></div>-->
                    <!--                    </div>-->
                    <div class="x_content">
                        <h3>My Cart</h3>
                        <br />
                        <button type="button" class="submit_btn" onclick="clearcart()">Clear</button>
                        <button type="button" class="submit_btn" onclick="submit_or_proceed_cart()"><?php echo ($is_warehouse_admin)?"Proceed":"Submit";?></button>
                        <button type="button" class="submit_btn" onclick="pending()">Pend to</button>
                        <input type="text" id="client" class="input_field_w w60" value="" autocomplete="off"/>
                        <div id="mycart"></div>
                    </div>
                </div>
            </div><!--zz x_panel lower right-->

            <div id="msg_direct_depart">
                <?php
                if($msg_direct_depart){
                    echo $msg_direct_depart;
                }
                ?>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <!--                    <div class="x_title">-->
                    <!--                        <h4>Add Associate Parts<small></small></h4>-->
                    <!--                        <div class="clearfix"></div>-->
                    <!--                    </div>-->
                    <div class="x_content">
                        <h3>Detail Information<small></small></h3>
                        <br />
                        <div class="row">
                            <div class="col-md-3 col-xs-12">
                                <a href="<?php echo($a_check['photo_url']); ?>"
                                   target="_blank">
                                    <img width="300" height="300" class ="withborder" src="<?php echo($a_check['photo_url']); ?>" class="image_wrapper" />
                                </a>
                            </div>
                            <div class="col-md-1 col-xs-12"></div>
                            <div class="col-md-8 col-xs-12">
                                <p>
                                    <a href="
        <?php
                                    if($table == "ew_part"){
                                        echo "view_part.php";
                                    }
                                    else{echo "view_car.php";}
                                    ?>?barcode=<?php echo $barcode;?>"
                                       target="_blank" class="a-underline-zz">
                                        Visit part detail page of <?php echo $barcode;?> ->
                                    </a>
                                    <br /><br />
                                    <!--    //zz xsearch应该是专为searchSuggestion功能弄的一个column，有全部各个域的value串成一长string，也被用于display part details-->
                                    <label class="control-label" style="/*font-size: medium*/">Barcode: </label>
                                    <?php echo $a_check['barcode'];?><br/>
                                    <label class="control-label" style="/*font-size: medium*/">Name: </label>
                                    <?php echo $a_check['name'];?><br/>
                                    <label class="control-label" style="/*font-size: medium*/">Price: </label>
                                    <?php echo $a_check['p_price'];?> - <?php echo $a_check['w_price'];?> - <?php echo $a_check['r_price'];?><br/>
                                    <label class="control-label" style="/*font-size: medium*/">Location: </label>
                                    <?php echo $a_check["l_zone"]."_".$a_check["l_column"]."_".$a_check["l_level"]; ?><br/>
                                    <label class="control-label" style="/*font-size: medium*/">In stock: </label>
                                    <?php echo $a_check['quantity'];?><br/>
                                    <label class="control-label" style="/*font-size: medium*/">Warning: </label>
                                    <?php echo $a_check['w_quantity'];?><br/>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- page content -->

<?PHP
include('template_footer_scripts.php');
?>
<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: enter.php
* This file performs enter related functions
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

//$load = " onload=\"load()\"";
//$title_by_page = "Arrive";
//include('header.php');
include_template_header_css_sidebar_topbar(" onload=\"load()\"","Receiving","enter");

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
            // document.form_barcode_scan.focus_on.focus();
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
                <h2>Receiving</h2>
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
                        <h3>Part to be received<small></small></h3>
                        <br />
                        <form class="form-horizontal form-label-left" name="form_barcode_scan" method="post" action="enter.php">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="font-size: medium">Scan Barcode <i class="fa fa-question-circle" title="also auto-submit one unit to MyCart..."></i></label>
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

                        <form name="form_confirm_increase" method="post" id="form_confirm_increase" action="enter.php" class="form-horizontal form-label-left">
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
                                    <input type="text" class="form-control" name="stock" disabled
                                           value="<?php echo($a_check['quantity']); ?>"/>
                                </div>
                            </div>
                            <!--zz  "Expect Stock" is removed temporarily-->
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Receiving Quantity</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="amount"
                                           value="1">
                                </div>
                            </div>
                            <div class="form-group" style="
                                <?php
                                if(!$is_warehouse_admin){
                                    echo "display:none;";
                                }
                                ?>">
                                <label class="col-md-3 col-sm-3 col-xs-12 control-label">Omit Otto's Cart</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <div class="checkbox">
                                        <input type="checkbox" onclick="onclick_cb_ooc()" id="cb_ooc" name="cb_ooc" value=""
                                                <?php if($cookie_is_to_omit_cart == "true")echo "checked";?>
                                                   class="flat">
                                        <label>Write to database directly without cart? (Admin Required)</label>
                                    </div>
                                </div>
                            </div>
                            <input type="text" style="display:none;" name="barcode" value = "<?php echo $barcode;?>" autocomplete="off"/>
                            <input type="text" style="display:none;" name="is_edit_cart" value = "<?php echo ($is_edit_cart)?$is_edit_cart:"";?>" autocomplete="off"/>
                            <input type="text" style="display:none;" name="appli_old" value = "<?php echo ($appli_old)?$appli_old:"";?>" autocomplete="off"/>
                            <div class="form-group">
                                <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                    <input type="submit" name="submit_confirm_increase" id="submit_confirm_increase" class="btn btn-success"
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
            <div class="col-md-6 col-sm-6 col-xs-12"  style="
                <?php
            if ($cookie_is_to_omit_cart == "true")
                echo "display: none";
            ?>"><!--zz x_panel right-->
                <div class="x_panel">
                    <!--                    <div class="x_title">-->
                    <!--                        <h4>Add Associate Parts<small></small></h4>-->
                    <!--                        <div class="clearfix"></div>-->
                    <!--                    </div>-->
                    <div class="x_content">
                        <h3>My Cart</h3>
                        <br />
                        <button type="button" class="submit_btn" onclick="clearcart()">Clear List</button>
                        <button type="button" class="submit_btn" onclick="submit_or_proceed_cart()"><?php echo ($is_warehouse_admin)?"Proceed List":"Submit List";?></button>
<!--                        <button type="button" class="submit_btn" onclick="pending()">Pend to</button>-->
<!--                        <input type="text" id="client" class="input_field_w w60" value="" autocomplete="off"/>-->
                        <div id="mycart"></div>
                    </div>
                </div>
            </div><!--zz x_panel right-->

            <div id="msg_direct_depart">
                <?php
                if($msg_direct_depart){
                    echo $msg_direct_depart;
                }
                ?>
            </div>
        </div><!--zz row-->

        <div class="row"><!--zz Detail Information-->
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
        </div><!--zz Detail Information-->

    </div><!-- page content -->

<?PHP
include('template_footer_scripts.php');
?>
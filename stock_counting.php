<?PHP
/*
* Copyright © 2013 Elaine Warehouse
 * Created by PhpStorm.
 * User: AGT John
 * Date: 2018-12-07
 * Time: 9:37 AM
* File: stock_counting.php
* This file updates the profile of an existing part item.
*/
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//zz check for cookie for countingEvent persist
$selected_current_c_event="Please choose... ";
if($_COOKIE['selected_c_c_event_id']){
    $selected_c_c_event_id = $_COOKIE['selected_c_c_event_id'];
    $selected_current_c_event =
        get_c_event_by('c_event_id',$selected_c_c_event_id)['c_event_name'];
}

//zz (cookie for countingEvent persist) setter
if($_GET['selected_c_c_event_id']){
    setcookie("selected_c_c_event_id",$_GET['selected_c_c_event_id'],time()+60*60*2);
    $selected_c_c_event_id = $_GET['selected_c_c_event_id'];
    $selected_current_c_event =
        get_c_event_by('c_event_id',$_GET['selected_c_c_event_id'])['c_event_name'];
}

//handler for edit mode
$display_edit_section = 'style="display:none"';
$display_view_section = 'style=""';
if(isset($_GET['edit_mode'])){
    $display_edit_section = 'style=""';
    $display_view_section = 'style="display:none"';
}

//zz add a new countingEvent (mainly for countingEvent dropdownBtnGroup)
if(isset($_GET['txt_new_c_event'])){
    if(!add_new_c_event($_GET['txt_new_c_event'])){
        echo("<script>window.alert('DB Error!');</script>");
        die('<meta http-equiv="refresh" content="0;URL=stock_counting.php">');
    }
    else{
        echo("<script>window.alert('New Counting Event has been created!');</script>");
        die('<meta http-equiv="refresh" content="0;URL=stock_counting.php">');
    }
}

//handle delete part request
if ($_GET['do']=='del') {
    $barcode = $_GET['barcode'];
    $stock = get_anything($barcode,"quantity");
    if($stock != 0){
        stop("you can not delete an item when stock amount is not zero");
    }

    if(check_data('ew_part','barcode',$barcode)){
        $sql_code = "UPDATE `ew_part` SET `quantity` = '0', `w_quantity` = '-1', `date` = CURRENT_TIMESTAMP WHERE `barcode` = '".$barcode."';";
        if (!($result=mysql_query($sql_code))) {

            mysql_close($link);
            stop('DB Error!');
        }
        else{
            sys_log($_COOKIE['ew_user_name'],"del part, barcode=$barcode,name=$name");
            mysql_close($link);
            echo("<script>window.alert('Part has been Disable!');</script>");
            die('<meta http-equiv="refresh" content="0;URL=index.php">');
        }
    }else{
        stop("Barcode not found!");
    }
}

//zz "view part" & data rendering for "edit part"
if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];
    if(check_data('ew_part','barcode',$barcode)){
        $sql_code = "select * from ew_part where barcode = '".$barcode."';";
        $result_info = mysql_query($sql_code);
        $a_check = mysql_fetch_array($result_info);
    }else{
        stop("Barcode not found!");
    }
    $c_event_name = get_c_event_by("c_event_id", $a_check['last_counting_event'])['c_event_name'];
    $c_event_name = ($c_event_name == false)?"N/A":$c_event_name;
}

//handle post form for update profile request
if($_POST['submit_edit']){
    if($_FILES["file"]["size"] == 0){
        $photo_url = $_POST["photo_url"];

    }else{
        $allowedExts = array("gif", "jpeg", "jpg", "png","GIF", "JPEG", "JPG", "PNG");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        if (
            (
                ($_FILES["file"]["type"] == "image/gif")
                || ($_FILES["file"]["type"] == "image/jpeg")
                || ($_FILES["file"]["type"] == "image/jpg")
                || ($_FILES["file"]["type"] == "image/pjpeg")
                || ($_FILES["file"]["type"] == "image/x-png")
                || ($_FILES["file"]["type"] == "image/png")
            )
            && ($_FILES["file"]["size"] < 10000000)
            && in_array($extension, $allowedExts)
        ){
            //			&& ($_FILES["file"]["size"] < 2000000)
            if ($_FILES["file"]["error"] > 0){
                echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
            }
            else{
                $file_name = $_FILES["file"]["name"];
                $new_file_name = round(microtime(true) * 1000)."."."$extension";

                if (file_exists("upload/" . $new_file_name)){
                    echo $new_file_name . " already exists. ";
                }
                else{
                    move_uploaded_file($_FILES["file"]["tmp_name"],
                        "upload/" . $new_file_name);
                    $photo_url = "upload/" . $new_file_name;
                    $thumb_url = "upload/thumb/" . $new_file_name;
                    generate_image_thumbnail($photo_url, $thumb_url);
                }
            }
        }else{
            echo "Invalid file type or size exceed 500kb";
        }
    }
    //echo($photo_url);

    $barcode = $_POST["barcode"];
    check($barcode,12,"Barcode");
    check_int($barcode,"Barcode");

    $name = $_POST["name"];
    check($name,80,"Name");

    $part_num = $_POST["part_num"];
    check($part_num,40,"Part Number");

    $part_num_yigao = $_POST["part_num_yigao"];
//    check($part_num_yigao,40,"Part Number (YiGao)");

    $category = $_POST["category"];

//	$sub_category = "";
//	if(isset($_POST['sub1'])){
//		$sub_category = $sub_category.$_POST['sub1'].",";
//	}
//	if(isset($_POST['sub2'])){
//		$sub_category = $sub_category.$_POST['sub2'].",";
//	}
//	if(isset($_POST['sub3'])){
//		$sub_category = $sub_category.$_POST['sub3'].",";
//	}
//	if(isset($_POST['sub4'])){
//		$sub_category = $sub_category.$_POST['sub4'].",";
//	}
//	$sub_category = rtrim($sub_category, ",");
//	if($sub_category == ""){
//		$sub_category = "UNKNOW";
//	}

    $sub_category = "AGT";//zz remove 'For' field

    $color = $_POST["color"];
    check($color,20,"Color");

    $p_price = $_POST["p_price"];
    check($p_price,10,"Purchase price");
    check_num($p_price,"Purchase price");

    $w_price = $_POST["w_price"];
    check($w_price,10,"wholesale price");
    check_num($w_price,"Wholesale price");

    $r_price = $_POST["r_price"];
    check($r_price,10,"Retail price");
    check_num($r_price,"Retail price");

    $quantity = $_POST["quantity"];
    check($quantity,10,"Quantity");
    check_int($quantity,"Quantity");

    $w_quantity = $_POST["w_quantity"];
    check($w_quantity,10,"Warning Quantity");
    check_int($w_quantity,"Warning Quantity");

    $l_zone = $_POST["l_zone"];
    check($l_zone,20,"Location Zone");

    $l_column = $_POST["l_column"];
    check($l_column,20,"Location Column");

    $l_level = $_POST["l_level"];
    check($l_level,20,"Location Level");

    $des = $_POST["des"];
    $location = $l_zone."_".$l_column."_".$l_level;
    $xsearch = strtolower("barcode:$barcode, name:$name, model:$part_num, category:$category, sub category:$sub_category, color:$color, location:$location");
    //echo($xsearch);

    $organizing1809 = $_POST["organizing1809"];

    $last_counting_event = $_POST["c_event_id"];

    $sql_code = "UPDATE `ew_part` SET 
				`barcode` ='$barcode',
				`name` ='$name',
				`photo_url` ='$photo_url',
				`part_num` ='$part_num',
				`part_num_yigao` ='$part_num_yigao',
				`category` ='$category',
				`color` ='$color',
				`sub_category` ='$sub_category',
				`p_price` ='$p_price',
				`w_price` ='$w_price',
				`r_price` ='$r_price',
				`quantity` ='$quantity',
				`w_quantity` ='$w_quantity',
				`l_zone` ='$l_zone',
				`l_column` ='$l_column',
				`l_level` ='$l_level',
				`des` ='$des',
				`xsearch` ='$xsearch',
				`organizing201809` = '$organizing1809',
				`last_counting_event` = $last_counting_event
				WHERE `barcode` = '$barcode';";
    //echo($sql_code);

    if (!($result=mysql_query($sql_code))) {

        mysql_close($link);
        check_pass(0);
        echo("<script>window.alert('DB Error!');</script>");
        die('<meta http-equiv="refresh" content="0;URL=index.php">');
    }
    else{
        sys_log($_COOKIE['ew_user_name'],"edit part, barcode=$barcode,name=$name");
        mysql_close($link);
        echo("<script>window.alert('Part profile has been updated!');</script>");
        die("<meta http-equiv=\"refresh\" content=\"0;URL=stock_counting.php?barcode=$barcode\">");
    }
}

//post form handler - create a new part
if($_POST['submit_new']){
    if($_FILES["file"]["size"] == 0){
        $photo_url = $defaultset[photo];
    }else{
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        if (
            (
                ($_FILES["file"]["type"] == "image/gif")
                || ($_FILES["file"]["type"] == "image/jpeg")
                || ($_FILES["file"]["type"] == "image/jpg")
                || ($_FILES["file"]["type"] == "image/pjpeg")
                || ($_FILES["file"]["type"] == "image/x-png")
                || ($_FILES["file"]["type"] == "image/png")
            )
//			&& ($_FILES["file"]["size"] < 2000000)
            && ($_FILES["file"]["size"] < 10000000)
            && in_array($extension, $allowedExts)
        ){
            if ($_FILES["file"]["error"] > 0){
                echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
            }else{
                $file_name = $_FILES["file"]["name"];
                $new_file_name = round(microtime(true) * 1000)."."."$extension";

                if (file_exists("upload/" . $new_file_name)){
                    echo $new_file_name . " already exists. ";
                }else{
                    move_uploaded_file($_FILES["file"]["tmp_name"],
                        "upload/" . $new_file_name);
                    $photo_url = "upload/" . $new_file_name;
                    $thumb_url = "upload/thumb/" . $new_file_name;
                    generate_image_thumbnail($photo_url, $thumb_url);
                }
            }
        }else{
            echo "Invalid file type or size exceed 500kb";
        }
    }
    //echo($photo_url);

    $barcode = $_POST["barcode"];
    check($barcode,12,"Barcode");
    check_int($barcode,"Barcode");

    $name = $_POST["name"];
    check($name,80,"Name");

    $part_num = $_POST["part_num"];
    check($part_num,40,"Part Number");

    $part_num_yigao = $_POST["part_num_yigao"];
//    check($part_num_yigao,40,"Part Number (YiGao)");

    $category = $_POST["category"];


    $sub_category = "";
//	if(isset($_POST['sub1'])){
//		$sub_category = $sub_category.$_POST['sub1'].",";
//	}
//	if(isset($_POST['sub2'])){
//		$sub_category = $sub_category.$_POST['sub2'].",";
//	}
//	if(isset($_POST['sub3'])){
//		$sub_category = $sub_category.$_POST['sub3'].",";
//	}
//	if(isset($_POST['sub4'])){
//		$sub_category = $sub_category.$_POST['sub4'].",";
//	}
//	$sub_category = rtrim($sub_category, ",");
//	if($sub_category == ""){
//		$sub_category = "UNKNOW";
//	}
    $sub_category = "AGT";

    $color = $_POST["color"];
    check($color,20,"Color");

    $p_price = $_POST["p_price"];
    check($p_price,10,"Purchase price");
    check_num($p_price,"Purchase price");

    $w_price = $_POST["w_price"];
    check($w_price,10,"wholesale price");
    check_num($w_price,"Wholesale price");

    $r_price = $_POST["r_price"];
    check($r_price,10,"Retail price");
    check_num($r_price,"Retail price");

    $quantity = $_POST["quantity"];
    check($quantity,10,"Quantity");
    check_int($quantity,"Quantity");

    $w_quantity = $_POST["w_quantity"];
    check($w_quantity,10,"Warning Quantity");
    check_int($w_quantity,"Warning Quantity");

    $l_zone = $_POST["l_zone"];
    check($l_zone,20,"Location Zone");

    $l_column = $_POST["l_column"];
    check($l_column,20,"Location Column");

    $l_level = $_POST["l_level"];
    check($l_level,20,"Location Level");

    $des = $_POST["des"];
    $location = $l_zone."_".$l_column."_".$l_level;
    $xsearch = strtolower("barcode:$barcode, name:$name, model:$part_num, category:$category, sub category:$sub_category, color:$color, location:$location");
    //echo($xsearch);

    $organizing1809 = $_POST["organizing1809"];
    $last_counting_event = $_POST["c_event_id"];

    //col list: barcode, name, photo_url, part_num, part_num_yigao, category, sub_category, color, p_price, w_price, r_price,
    // quantity, w_quantity, l_zone, l_column, l_level, date, des, xsearch, organizing201809, last_counting_event
    $result_anp = add_new_part($barcode,$name,$photo_url,$part_num,$part_num_yigao,$category,$sub_category,$color,
        $p_price,$w_price,$r_price,$quantity,$w_quantity,$l_zone,$l_column,$l_level,
        $des,$xsearch,$organizing1809,$last_counting_event);

    mysql_close($link);

    if (!$result_anp){

        echo("<script>window.alert('DB Error!');</script>");
        die('<meta http-equiv="refresh" content="0;URL=stock_counting.php">');
    }
    else{
        echo("<script>window.alert('New Part has been created!');</script>");
        die('<meta http-equiv="refresh" content="0;URL=stock_counting.php?barcode='.$barcode.'">');
    }
}

//zz load all countingEvents
$array_c_events = get_all_c_events();

//zz get countingEvent by countingEventId
//$row_c_event = get_c_event_by("c_event_id", $c_event_id);
//$row_c_event = get_c_event_by("c_event_id", "1");


//zz add a new countingEvent
//if(!add_new_c_event($c_event_name)){
//    echo("<script>window.alert('DB Error!');</script>");
//    die('<meta http-equiv="refresh" content="0;URL=stock_counting.php">');
//}
//else{
//    echo("<script>window.alert('New Counting Event has been created!');</script>");
//    die('<meta http-equiv="refresh" content="0;URL=stock_counting.php?barcode='.$barcode.'">');
//}

$load = " onload=\"load()\"";
include('template_header_css_sidebar_topbar.php');
?>

    <script type="text/javascript">
        function add_new_c_evnt(){
            let action = "add_new_c_evnt";
            $.ajax({
                url: "ajax/ajax_stock_counting.php",
                data: "action="+action,
                dataType: "json",
                type: "post",
                success: function(data){

                    console.log(data);
                    $("#div_c_event_ddl").html(data.html_code).fadeIn("fast");
                },
                error: function(data){
                    console.log("Ajax went to error (probably return type wrong):"+ data);
                }
            });
        }
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
            xmlhttp.open("GET","ajax/attach_part.php?main=<?php echo($a_check['barcode']); ?>&option=edit",true);
            xmlhttp.send();
        }

        function load()
        {
            loadXMLDoc();
        }

        //smartSearch
        function suggest(key)
        {
            var xmlhttp;
            var table = "ew_part";
            var special = "stock_counting";
            var postdata = "keyword="+encodeURIComponent(key)+"&table="+table+"&special="+special;
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
            };

            xmlhttp.open("POST","ajax/search_suggestion.php",true);
            xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xmlhttp.setRequestHeader("Content-length", postdata.length);
            xmlhttp.send(postdata);
        }
        function btn_toggle_edit(){
            if($('#div_edit_part:visible').length == 0){
                $('#div_edit_part').show();
                $('#div_view_part').hide();
                $('#div_add_new_part').hide();
            }
            else{
                $('#div_edit_part').hide();
                $('#div_view_part').show();
                $('#div_add_new_part').hide();
            }
        }
        function btn_show_add_new(){
            $('#div_add_new_part').show();
            $('#div_edit_part').hide();
            $('#div_view_part').hide();
        }
    </script>

    <!-- page content -->
    <div class="right_col" role="main">

        <!--        smart search-->
        <form name="form2" method="get" action="search.php" >
            Smart Search:
<!--            <select name="table" id="db_table" class="select_field">-->
<!--                <option value="ew_part" --><?php //if($table == 'ew_part'){ echo("selected=\"selected\"");} ?><!--Part</option>-->
<!--                <option value="ew_car" --><?php //if($table == 'ew_car'){ echo("selected=\"selected\"");} ?><!--Car</option>-->
<!--            </select>-->
            <input type="hidden" name="table" value="ew_part" hidden/>
            <input type="text" id="keyword" name="keyword" class="input_field" value="<?php echo $temp_key; ?>" autocomplete="off" onkeyup="suggest(this.value)"/>
            <input type="submit" class="submit_btn" value="Search"/>
        </form>

        <p id="suggestion"></p>
        <!--        /smart search-->

        <!--zz countingEvent ddl-->
        <div id="div_c_event_ddl" style="float: right;position: relative">
<!--            <select name="last_counting_event"-->
<!--                    id="last_counting_event">-->
<!--                <option value="" selected disabled>--Please select the current counting event--</option>-->
<!--                --><?php //?>
<!--                <option value="N/A"-->
<!--                        title="">-->
<!--                    N/A</option>-->
<!--                <option value="buy_new"-->
<!--                        title="">-->
<!--                    Buying new cart</option>-->
<!--                <option value="trade_in"-->
<!--                        title="">-->
<!--                    Trade in old cart</option>-->
<!--                <option value="" selected disabled><hr></option>-->
<!--                <option value="" selected disabled>--Please select the current counting event--</option>-->
<!--            </select>-->
            <label>Current counting event:&nbsp;</label>
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <?php echo $selected_current_c_event;?> &nbsp;
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <?php
                        foreach($array_c_events as $c_event){
                            echo <<<temp
                                <li>
                                    <a href="stock_counting.php?selected_c_c_event_id={$c_event['c_event_id']}">
                                        {$c_event['c_event_name']}
                                    </a>
                                </li>
temp;
                        }
                    ?>
                    <li role="separator" class="divider"></li>
                    <li><button onclick="add_new_c_evnt()">+ Add New</button></li>
                </ul>
                <style>
                    .dropdown-menu-right{
                        right:0;
                        left:auto;
                    }
                </style>
            </div>
        </div>
        <!--zz /countingEvent ddl-->


        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Default Example <small>Users</small></h2>
                        <btn class="btn btn-default btn-sm" id="btn_toggle_edit" onclick="btn_toggle_edit()">Toggle edit</btn>
                        <btn class="btn btn-default btn-sm float_r" id="btn_toggle_edit" onclick="btn_show_add_new()">Add New Part</btn>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <!--                            <li class="dropdown">-->
                            <!--                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>-->
                            <!--                                <ul class="dropdown-menu" role="menu">-->
                            <!--                                    <li><a href="#">Settings 1</a>-->
                            <!--                                    </li>-->
                            <!--                                    <li><a href="#">Settings 2</a>-->
                            <!--                                    </li>-->
                            <!--                                </ul>-->
                            <!--                            </li>-->
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <!--zz view part-->
                        <div id="div_view_part" <?php echo $display_view_section;?>>
                            <h2>View Parts</h2>

                            <div class="cleaner"></div>
                            <div class="cleaner h30"></div>

                            <div class="row">
                                <div class="col-md-4 col-xs-12"><!--left column-->
                                    <h4>Photo Preview</h4>
                                    <a href="<?php echo($a_check['photo_url']); ?>" target="_blank">
                                        <img style="width:auto;height:auto;object-fit: cover;overflow: hidden" class ="withborder" src="<?php echo get_thumb($a_check['photo_url']); ?>" />

                                        <!--    width="300" height="300" class="image_wrapper" -->
                                    </a>
<!--                                    <div class="col-md-5 col-sm-3 col-xs-12 text-right">-->
<!--                                        Photo-->
<!--                                    </div>-->
<!--                                    <span class="col-md-7 col-sm-3 col-xs-12 text-right">-->
<!--                                        --><?php //echo($a_check['photo_url']); ?>
<!--                                    </span>-->
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Name
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['name']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Barcode
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['barcode']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Part Number
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['part_num']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-5 col-sm-3 col-xs-12 text-right">
                                                    Part Number (Eagle)
                                                </th>
                                                <td class="col-md-7 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['part_num_yigao']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Category
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['category']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Color
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['color']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Purchase Price
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['p_price']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Wholesale Price
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['w_price']); ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div><!--middle column-->
                                <div class="col-md-4 col-xs-12">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th class="col-md-5 col-sm-3 col-xs-12 text-right">
                                                    Retail Price
                                                </th>
                                                <td class="col-md-7 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['r_price']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-5 col-sm-3 col-xs-12 text-right">
                                                    Quantity
                                                </th>
                                                <td class="col-md-7 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['quantity']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Stock Warning
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['w_quantity']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Location
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <a href="search.php?table=ew_part&keyword=<?php echo($a_check['l_zone']."_".$a_check['l_column']."_".$a_check['l_level']); ?>"><?php echo($a_check['l_zone']."_".$a_check['l_column']."_".$a_check['l_level']); ?></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Latest Update
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['date']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Flag Organizing1809
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['organizing201809']); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Last Counting Event
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($c_event_name); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                    Description
                                                </th>
                                                <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                    <?php echo($a_check['des']); ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div><!--right column-->
                            </div>

<!--                            <div class="col_w320 float_l">-->
<!--                                <h4>Photo Preview</h4>-->
<!--                                <a href="--><?php //echo($a_check['photo_url']); ?><!--" target="_blank">-->
<!--                                    <img style="width:auto;height:auto;object-fit: cover;overflow: hidden" class ="withborder" src="--><?php //echo get_thumb($a_check['photo_url']); ?><!--" />-->
<!---->
<!--                                    <!--    width="300" height="300" class="image_wrapper" -->
<!--                                </a>-->
<!--                                <p>-->
<!--                                    <a href="edit_part.php?barcode=--><?php //echo($a_check['barcode']); ?><!--">[Edit Profile]</a>-->
<!--                                    <a href="enter.php?barcode=--><?php //echo($a_check['barcode']); ?><!--">[Quick Enter]</a>-->
<!--                                    <a href="depart.php?barcode=--><?php //echo($a_check['barcode']); ?><!--">[Quick Depart]</a>-->
<!--                                </p>-->
<!---->
<!--                            </div>-->

                            <div class="cleaner h20"></div>

                            <h4>Associated Part</h4>
                            <div id="attach_part"></div>


                            <div class="cleaner h30"></div>
                            <div class="cleaner"></div>
                            <div class="cleaner"></div>
                        </div>
                        <!--zz /view part-->

                        <!--zz edit part-->
                        <div id="div_edit_part" <?php echo $display_edit_section;?>>
                            <h2>Edit Part</h2>
<!--                            <div class="cleaner"></div>-->
                            <p>You should no change barcode for an exsiting part for any reason. Each input field should not exceed max allowed size or violate corresponding data type in DB. Details refers to [section 2.2.5 Table: ew_part] in design document.</p>
<!--                            <div class="cleaner h30"></div>-->
<!--                            <div class="col_w320 float_r">-->
                            <form class="form-horizontal form-label-left"
                                name="form" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Photo</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <label class="btn btn-default">
                                                Browse <input type="file" name="file" hidden
                                                    onchange="$('#upload-file-name').html(this.files[0].name)">
                                            </label><span class="" id="upload-file-name"></span>
                                            <input type="text" class="form-control"
                                                   style="display:none;" name="photo_url" value="<?php echo($a_check['photo_url']); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Barcode</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="barcode"
                                                   value="<?php echo($a_check['barcode']); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="name"
                                                   value="<?php echo($a_check['name']); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Part Number</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="part_num"
                                                   value="<?php echo($a_check['part_num']); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Part Number (Eagle)</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="part_num_yigao"
                                                   value="<?php echo($a_check['part_num_yigao']); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Category</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <select name="category" class="form-control">
                                                <option value="body" <?php if($a_check['category'] == 'body'){ echo("selected=\"selected\"");} ?>>Body</option>
                                                <option value="accessory" <?php if($a_check['category'] == 'accessory'){ echo("selected=\"selected\"");} ?>>Accessory</option>
                                                <option value="tire_and_rim" <?php if($a_check['category'] == 'tire_and_rim'){ echo("selected=\"selected\"");} ?>>Tire and Rim</option>
                                                <option value="mechanical" <?php if($a_check['category'] == 'mechanical'){ echo("selected=\"selected\"");} ?>>Mechanical</option>
                                                <option value="electrical" <?php if($a_check['category'] == 'electrical'){ echo("selected=\"selected\"");} ?>>Electrical</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Color</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="color"
                                                   value="<?php echo($a_check['color']); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Price</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="p_price"
                                                   value="<?php echo($a_check['p_price']); ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Quantity</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="quantity"
                                                   value="<?php echo($a_check['quantity']); ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>


                                <div id="newcar_form">

                                    <form name="form" method="post" enctype="multipart/form-data">
                                        <label>Photo:</label><input type="file" name="file"><input type="text" style="display:none;" name="photo_url" value="<?php echo($a_check['photo_url']); ?>"/><br>
                                        <label>Barcode: </label><input type="text" name="barcode" value="<?php echo($a_check['barcode']); ?>"/><br />
                                        <label>Car Name: </label><input type="text" name="name" value="<?php echo($a_check['name']); ?>"/><br />
                                        <label title="This part number is for AGT. They are older, more stable and referred on our product manuals.">
                                            Part Number: </label><input type="text" name="part_num" value="<?php echo($a_check['part_num']); ?>"/><br />
                                        <label title="The newest part number on the domestic, Eagle side. It is useful when ordering parts from them.">
                                            Part Number (Eagle): </label><input type="text" name="part_num_yigao" value="<?php echo($a_check['part_num_yigao']); ?>"/><br />

                                        <label>Category: </label>
                                        <select name="category">
                                            <option value="body" <?php if($a_check['category'] == 'body'){ echo("selected=\"selected\"");} ?>>Body</option>
                                            <option value="accessory" <?php if($a_check['category'] == 'accessory'){ echo("selected=\"selected\"");} ?>>Accessory</option>
                                            <option value="tire_and_rim" <?php if($a_check['category'] == 'tire_and_rim'){ echo("selected=\"selected\"");} ?>>Tire and Rim</option>
                                            <option value="mechanical" <?php if($a_check['category'] == 'mechanical'){ echo("selected=\"selected\"");} ?>>Mechanical</option>
                                            <option value="electrical" <?php if($a_check['category'] == 'electrical'){ echo("selected=\"selected\"");} ?>>Electrical</option>
                                        </select><br />

                                        <!--<label>For: </label>-->
                                        <!--<input type="checkbox" name="sub1" value="CLUB" --><?php //if (strpos($a_check['sub_category'], 'CLUB') !== false){echo "checked";} ?><!-- >CLUB-->
                                        <!--<input type="checkbox" name="sub2" value="EZGO" --><?php //if (strpos($a_check['sub_category'], 'EZGO') !== false){echo "checked";} ?><!-- >EZGO-->
                                        <!--<input type="checkbox" name="sub3" value="AGT" --><?php //if (strpos($a_check['sub_category'], 'AGT') !== false){echo "checked";} ?><!-- >AGT-->
                                        <!--<input type="checkbox" name="sub4" value="YAMAHA" --><?php //if (strpos($a_check['sub_category'], 'YAMAHA') !== false){echo "checked";} ?><!-- >YAMAHA-->
                                        <!--<br />-->
                                        <label>Color: </label><input type="text" name="color" value="<?php echo($a_check['color']); ?>"/><br />
                                        <label>Purchase Price: </label><input type="text" name="p_price" value="<?php echo($a_check['p_price']); ?>"/><br />
                                        <label>Wholesale Price: </label><input type="text" name="w_price" value="<?php echo($a_check['w_price']); ?>"/><br />
                                        <label>Retail Price: </label><input type="text" name="r_price" value="<?php echo($a_check['r_price']); ?>"/><br />
                                        <label>Quantity: <?php /*echo($a_check['quantity']); */?></label><input type="text" style="/*display:none;*/" name="quantity" value="<?php echo($a_check['quantity']); ?>"/><br />
                                        <label>Warning Quantity: </label><input type="text" name="w_quantity" value="<?php echo($a_check['w_quantity']); ?>"/><br />
                                        <!--    zz-->
                                        <img src="images/map.gif" height="" width="300" style="margin-top: 10px;margin-bottom: 10px" usemap="#map1">
                                        <map id="map1" name="map2">
                                            <area shape="rect" coords="98,46,240,104" alt="" title="P1" onclick="testzz()" style="cursor: pointer"/>
                                            <script>
                                                function testzz() {
                                                    // alert("haha");
                                                    // history.go(-1);
                                                    window.location = '#location_z';
                                                    document.getElementById("location_z").value="P1";
                                                    document.form.l_column.value = '';
                                                    document.form.l_column.focus();
                                                }
                                            </script>
                                        </map>
                                        <!--    zz-->
                                        <label>Location Zone: </label><!--zz --><input id="location_z" type="text" name="l_zone" value="<?php echo($a_check['l_zone']); ?>"/><br />
                                        <label>Location Column: </label><input type="text" name="l_column" value="<?php echo($a_check['l_column']); ?>"/><br />
                                        <label>Location Level: </label><input type="text" name="l_level" value="<?php echo($a_check['l_level']); ?>"/><br />

                                        <!--    zz temp for organizing1809-->
                                        <label>- Flag Organizing1809: </label><input type="text" name="organizing1809" value="<?php echo($a_check['organizing201809']); ?>"/><br />

                                        <label>Last Counting Event: </label>
                                        <select name="c_event_id" title="Last Counting Event that the part has involved and counted..">
                                            <?php
                                            foreach($array_c_events as $c_event){
                                                $c_event_name_temp=$c_event['c_event_name'];
                                                $c_event_id_temp=$c_event['c_event_id'];
                                                $selected_temp = ($c_event_name_temp == $selected_current_c_event)?'selected="selected"':'';
                                                echo <<<temp
                                <option value="$c_event_id_temp" {$selected_temp}>
                                    {$c_event_name_temp}
                                </option>
temp;
                                            }
                                            ?>
                                            <option value="NULL" {$selected_temp}>
                                                NULL (not belong to any)
                                            </option>
                                        </select><br />

                                        <label>Description: </label><br/>
                                        <textarea rows="4" cols="50" name="des"><?php echo($a_check['des']); ?></textarea><br/>
                                        <input type="submit" name="submit_edit" class="submit_btn float_l" value="Edit"/>
                                    </form>

                                </div>
                            </div>
                        <style>
                            [hidden] {
                                display: none !important;
                            }
                        </style>

                            <div class="col_w320 float_l">
                                <h4>Photo Preview</h4>

                                <!--<div style="width: 300px;height: 300px;" class="withborder">-->
                                <a href="<?php echo($a_check['photo_url']); ?>" target="_blank">
                                    <img style="width:auto;height:auto;object-fit: cover;overflow: hidden" class="withborder" src="<?php echo get_thumb($a_check['photo_url']); ?>"/>
                                    <!--        class="image_wrapper"-->
                                </a>
                                <!--</div>-->
                                <div class="cleaner h10"></div>
                                <p><a href="edit_part.php?barcode=<?php echo $a_check["barcode"]; ?>&do=del">[ Delete ]</a> - Warning, delete will set the part quantity to "0" and warning quantity to "-1".</p>
                                <h4>Associated Part </h4>
                                <form name="form2" method="get" action="ajax/attach_part.php">
                                    <input type="text" style="display:none;" name="do" value="add"/><input type="text" style="display:none;" name="main" value="<?php echo $a_check["barcode"]; ?>"/>
                                    Attach:<input type="text" class="input_field_w w90" name="attach"/>Amount:<input type="text" class="input_field_w w50" name="amount"/> <input type="submit" class="submit_btn" value="Add"/>
                                </form>
                                <div class="cleaner h10"></div>
                                <div id="attach_part"></div>

                            </div>


                            <div class="cleaner h30"></div>
                            <div class="cleaner"></div>
                            <div class="cleaner"></div>
                        </div>
                        <!--zz /edit part-->

                        <!--zz new part-->
                        <div id="div_add_new_part">
                            <h2>Add a New Part</h2>
                            <div class="cleaner"></div>
                            <p>Barcode has to be a unique 12 digits number. If you are not familiar with unique number generation algorithms, simply leave it unchanged. If you wish to only create a type of part without any inventory, please set quantity to 0, otherwise please specify inventory quantity.</p>
                            <div class="cleaner h30"></div>

                            <div class="col_w320 float_r">
                                <div id="newcar_form">

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">

                                        </div>
                                        <div class="col-md-6 col-xs-12">

                                        </div>
                                    </div>

                                    <form class="form-horizontal form-label-left"
                                          name="form" method="post" enctype="multipart/form-data">
                                        <label>Photo:</label><input type="file" name="file"><br>
                                        <label>Barcode: </label><input type="text" name="barcode" value ="<?php echo "1".substr(round(microtime(true) * 1000),0, -2); ?>"/><br />
                                        <label>Name: </label><input type="text" name="name"/><br />
                                        <label>Part Number: </label><input type="text" name="part_num"/><br />
                                        <label title="The newest part number on the domestic, Eaglee side. It is useful when ordering parts from them.">
                                            Part Number (Eagle): </label><input type="text" name="part_num_yigao"/><br />
                                        <label>Category: </label>
                                        <select name="category">
                                            <option value="body">Body</option>
                                            <option value="accessory">Accessory</option>
                                            <option value="tire_and_rim">Tire and Rim</option>
                                            <option value="mechanical">Mechanical</option>
                                            <option value="electrical">Electrical</option>
                                        </select><br />
                                        <!--<label>For:</label>-->
                                        <!--<input type="checkbox" name="sub1" value="CLUB">CLUB-->
                                        <!--<input type="checkbox" name="sub2" value="EZGO">EZGO-->
                                        <!--<input type="checkbox" name="sub3" value="AGT">AGT-->
                                        <!--<input type="checkbox" name="sub4" value="YAMAHA">YAMAHA<br />-->
                                        <label>Color: </label><input type="text" name="color" value="default"/><br />
                                        <label>Purchase Price: </label><input type="text" name="p_price" value="0"/><br />
                                        <label>Wholesale Price: </label><input type="text" name="w_price"value="0"/><br />
                                        <label>Retail Price: </label><input type="text" name="r_price" value="0"/><br />
                                        <label>Quantity: </label><input type="text" name="quantity"value="0"/><br />
                                        <label>Warning Quantity: </label><input type="text" name="w_quantity" value="0"/><br />
                                        <label>Location Zone: </label><input type="text" name="l_zone" value=""/><br />
                                        <label>Location Column: </label><input type="text" name="l_column" value=""/><br />
                                        <label>Location Level: </label><input type="text" name="l_level"value=""/><br />
                                        <label>Description: </label><br/>
                                        <textarea rows="4" cols="50" name="des">
                        </textarea><br/>
                                        <!--    zz temp for organizing1809-->
                                        <label>- Flag Organizing1809: </label><input type="text" name="organizing1809" value=""/><br />

                                        <label>Last Counting Event: </label>
                                        <select name="c_event_id" title="Last Counting Event that the part has involved and counted..">
                                            <?php
                                            foreach($array_c_events as $c_event){
                                                $c_event_name_temp=$c_event['c_event_name'];
                                                $c_event_id_temp=$c_event['c_event_id'];
                                                $selected_temp = ($c_event_name_temp == $selected_current_c_event)?'selected="selected"':'';
                                                echo <<<temp
                                <option value="$c_event_id_temp" {$selected_temp}>
                                    {$c_event_name_temp}
                                </option>
temp;
                                            }
                                            ?>
                                            <option value="NULL" {$selected_temp}>
                                                NULL (not belong to any)
                                            </option>
                                        </select><br />

                                        <input type="submit" name="submit_new" value="Create"/>
                                    </form>
                                </div>
                            </div>
                            <div class="col_w320 float_l">
                                <h4>Photo Preview</h4>
                                <a href="<?php echo($defaultset[photo]); ?>" target="_blank"><img width="300" height="300" class ="withborder" src="<?php echo($defaultset[photo]); ?>" class="image_wrapper" /></a>
                            </div>

                            <div class="cleaner h30"></div>
                            <div class="cleaner"></div>
                            <div class="cleaner"></div>
                        </div>
                        <!--zz /new part-->
                    </div><!--x_content-->
                </div><!--x_panel-->
            </div>
        </div>

    </div>
    <!-- /page content -->

    <style>
        #div_add_new_part {
            display: none;
        }
        /*#div_edit_part {*/
            /*display: none;*/
        /*}*/
    </style>

<?PHP
include('template_footer_scripts.php');
?>
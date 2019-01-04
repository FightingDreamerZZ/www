<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: new_part.php
* This file allows user to create a new car item.
*/

//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//$defaultset[photo] = "upload/default.jpg";

//post form handler - create a new part
if($_POST['submit']){
	if($_FILES["file"]["size"] == 0){
		$photo_url = $defaultset[photo];
	}else{
		$allowedExts = array("gif", "jpeg", "jpg", "png",
            "GIF","JPEG","JPG","PNG");
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

    $organizing201809 = $_POST["organizing201809"];
    $last_counting_event = "NULL";

    $result_anp = add_new_part($barcode,$name,$photo_url,$part_num,$part_num_yigao,$category,$sub_category,$color,
        $p_price,$w_price,$r_price,$quantity,$w_quantity,$l_zone,$l_column,$l_level,
        $des,$xsearch,$organizing201809,$last_counting_event);

    mysql_close($link);
	
	if (!$result_anp) {
			echo("<script>window.alert('DB Error!);</script>");
			die('<meta http-equiv="refresh" content="0;URL=new_part.php">');
	}
	else{
			echo("<script>window.alert('New Part has been created!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=view_part.php?barcode='.$barcode.'">');
	}

}

//$title_by_page = "New Part";
//include('header.php');
include_template_header_css_sidebar_topbar("","New Part","");
?>

    <style>
        [hidden] {
            display: none !important;
        }
    </style>

<!-- page content -->
<div class="right_col" role="main">

    <!--zz x_panel single big-->
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>New Part<small></small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!--zz new part-->
                    <div id="div_add_new_part">
                        <p>Barcode has to be a unique 12 digits number. If you are not familiar with unique number generation algorithms, simply leave it unchanged. If you wish to only create a type of part without any inventory, please set quantity to 0, otherwise please specify inventory quantity.</p>
                        <div class="row">
                            <form class="form-horizontal form-label-left"
                                  name="form-add-new" method="post" enctype="multipart/form-data">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Photo</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <label class="btn btn-default">
                                                Browse <input type="file" name="file" hidden
                                                              onchange="$('#upload-file-name-addnew').html(this.files[0].name)">
                                            </label><span class="" id="upload-file-name-addnew"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Barcode</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <?php $barcode_temp = generate_new_barcode();
                                            echo <<<temp
                                                <input type="text" class="form-control" name="barcode" disabled
                                                       value="{$barcode_temp}"/>
                                                <input type="text" class="form-control" name="barcode" style="display: none"
                                                       value="{$barcode_temp}"/>
temp;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="name"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Part Number</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="part_num"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Part Number (Eagle)</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="part_num_yigao"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Category</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <select name="category" class="form-control">
                                                <option value="" selected disabled>Please select one...</option>
                                                <option value="body">Body</option>
                                                <option value="accessory">Accessory</option>
                                                <option value="tire_and_rim">Tire and Rim</option>
                                                <option value="mechanical">Mechanical</option>
                                                <option value="electrical">Electrical</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Color</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="color"
                                                   value="default"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Price</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="p_price"
                                                   value="0.00"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Wholesale Price</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="w_price"
                                                   value="0.00"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Retail Price</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="r_price"
                                                   value="0.00"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Quantity</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="quantity"
                                                   value="0"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Warning Quantity</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="w_quantity"
                                                   value="0"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Location Zone</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="l_zone"
                                                   value="A"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Location Column</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="l_column"
                                                   value="0"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Location Level</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="l_level"
                                                   value="0"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Flag Organizing1809</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="organizing201809"
                                                   value=""/>
                                        </div>
                                    </div>
<!--                                    <div class="form-group">-->
<!--                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Last Counting Event</label>-->
<!--                                        <div class="col-md-9 col-sm-9 col-xs-12">-->
<!--                                            <select name="c_event_id" class="form-control" title="Last Counting Event that the part has involved and counted..">-->
<!--                                                --><?php
//                                                foreach($array_c_events as $c_event){
//                                                    $c_event_name_temp=$c_event['c_event_name'];
//                                                    $c_event_id_temp=$c_event['c_event_id'];
//                                                    $selected_temp = ($c_event_name_temp == $selected_current_c_event)?'selected="selected"':'';
//                                                    echo <<<temp
//                                                <option value="$c_event_id_temp" {$selected_temp}>
//                                                    {$c_event_name_temp}
//                                                </option>
//temp;
//                                                }
//                                                ?>
<!--                                                <option value="NULL">-->
<!--                                                    NULL (not belong to any)-->
<!--                                                </option>-->
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Description</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <textarea class="form-control" rows="3" cols="50" name="des"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                            <button type="reset" class="btn btn-primary">Reset</button>
                                            <input type="submit" name="submit" class="btn btn-success" value="Add New Part" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                        <div class="col_w320 float_l">
                            <h4>Photo Preview</h4>
                            <a href="<?php echo($defaultset[photo]); ?>" target="_blank"><img width="300" height="300" class ="withborder" src="<?php echo($defaultset[photo]); ?>" class="image_wrapper" /></a>
                        </div>
                    </div>
                    <!--zz /new part-->
                </div><!--x_content-->
            </div><!--x_panel-->
        </div>
    </div><!--zz x_panel single big-->

</div>
    <!-- /page content -->

<?PHP
include('template_footer_scripts.php');
?>
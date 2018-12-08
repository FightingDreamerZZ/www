<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: edit_part.php
* This file updates the profile of an existing part item.
*/
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();


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
		stop("Barcode not found!11");
	}	
}

//load profile from barcode
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

//handle post form for update profile request
if($_POST['submit']){
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
    check($part_num_yigao,40,"Part Number (YiGao)");
	
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
				`organizing201809` = '$organizing1809'
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
			die("<meta http-equiv=\"refresh\" content=\"0;URL=view_part.php?barcode=$barcode\">");
		}

	}

$load = " onload=\"load()\"";
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
	xmlhttp.open("GET","ajax/attach_part.php?main=<?php echo($a_check['barcode']); ?>&option=edit",true);
	xmlhttp.send();
	}
	
   function load()
   {
	  loadXMLDoc();
   }
</script>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">


<h2>Edit Part Profile</h2>
<div class="cleaner"></div>
<p>You should no change barcode for an exsiting part for any reason. Each input field should not exceed max allowed size or violate corresponding data type in DB. Details refers to [section 2.2.5 Table: ew_part] in design document.</p>
<div class="cleaner h30"></div>
<div class="col_w320 float_r">
<div id="newcar_form">

<form name="form" method="post" enctype="multipart/form-data">
<label>Photo:</label><input type="file" name="file"><input type="text" style="display:none;" name="photo_url" value="<?php echo($a_check['photo_url']); ?>"/><br>
<label>Barcode: </label><input type="text" name="barcode" value="<?php echo($a_check['barcode']); ?>"/><br />
<label>Car Name: </label><input type="text" name="name" value="<?php echo($a_check['name']); ?>"/><br />
<label title="This part number is for AGT. They are older, more stable and referred on our product manuals.">
    Part Number: </label><input type="text" name="part_num" value="<?php echo($a_check['part_num']); ?>"/><br />
<label title="The newest part number on the domestic, Yigao side. It is useful when ordering parts from them.">
    Part Number (YiGao): </label><input type="text" name="part_num_yigao" value="<?php echo($a_check['part_num_yigao']); ?>"/><br />

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

<label>Description: </label><br/>
<textarea rows="4" cols="50" name="des"><?php echo($a_check['des']); ?></textarea><br/>
<input type="submit" name="submit" class="submit_btn float_l" value="Edit"/>
</form>

</div>
</div>

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
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>
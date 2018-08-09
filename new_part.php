<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: new_part.php
* This file allows user to create a new car item.
*/

error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();


$defaultset[photo] = "upload/default.jpg";

//post form handler - create a new part
if($_POST['submit']){
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
			&& ($_FILES["file"]["size"] < 2000000)
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
	$sql_code = "INSERT INTO `ew_part` 
	(`barcode`, `name`, `photo_url`, `part_num`, `category`, `color`, `sub_category`, `p_price`, `w_price`, `r_price`, `quantity`, `w_quantity`, `l_zone`, `l_column`, `l_level`, `date`, `des`, `xsearch`) 
	VALUES ('$barcode', '$name', '$photo_url', '$part_num', '$category', '$color', '$sub_category', '$p_price', '$w_price', '$r_price', '$quantity', '$w_quantity', '$l_zone', '$l_column', '$l_level', CURRENT_TIMESTAMP, '$des', '$xsearch');";
	//echo($sql_code);
	
	if (!($result=mysql_query($sql_code))) { 
			
			mysql_close($link); 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=new_part.php">');
		}
		else{
			tran($_COOKIE['ew_user_name'],$barcode,'part',$quantity);
			sys_log($_COOKIE['ew_user_name'],"add new part, barcode=$barcode,name=$name,amount=$quantity");
			//cart($_COOKIE['ew_user_name'],$barcode,$quantity,'ew_part');
			mysql_close($link);
			echo("<script>window.alert('New Part has been created!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=new_part.php">');
		}

	}

$title_by_page = "New Part";
include('header.php');
?>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">
<h2>Add a New Part</h2>
 <div class="cleaner"></div>
 <p>Barcode has to be a unique 12 digits number. If you are not familiar with unique number generation algorithms, simply leave it unchanged. If you wish to only create a type of part without any inventory, please set quantity to 0, otherwise please specify inventory quantity.</p>
<div class="cleaner h30"></div>

<div class="col_w320 float_r">
<div id="newcar_form">
<form name="form" method="post" enctype="multipart/form-data">
<label>Photo:</label><input type="file" name="file"><br>
<label>Barcode: </label><input type="text" name="barcode" value ="<?php echo "1".substr(round(microtime(true) * 1000),0, -2); ?>"/><br />
<label>Name: </label><input type="text" name="name"/><br />
<label>Part Number: </label><input type="text" name="part_num"/><br />
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
<label>Location Zone: </label><input type="text" name="l_zone" value="w"/><br />
<label>Location Column: </label><input type="text" name="l_column" value="1"/><br />
<label>Location Level: </label><input type="text" name="l_level"value="1"/><br />
<label>Description: </label><br/>
<textarea rows="4" cols="50" name="des">
</textarea><br/>
<input type="submit" name="submit" value="Create"/>
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
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->



<?PHP
include('footer.php');
?>
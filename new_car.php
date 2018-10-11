<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: new_car.php
* This file allows user to create a new car item.
*/

error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('lib\sql.php');
include('lib\user_lib.php');

check_user_cookie();

//default attributes for car
$defaultset = array(
'name' => "",
'model' => "",
'pprice' => "",
'wprice' => "",
'rprice' => "",
'lzone' => "",
'lcolumn' => "",
'llevel' => "",
'photo' => "upload/default.jpg",
);

//template access handler
if (isset($_GET["temp"])) { 
	$temp_name = $_GET["temp"]; 
	$temp_dir = "cartemp\\".$temp_name;
	include($temp_dir);
}

//post form handler - create a new car
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
	
	$model = $_POST["model"];
	check($model,40,"Model");
	
	$category = $_POST["category"];
	
	$color = $_POST["color"];
	check($color,20,"Color");
	
	$year = $_POST["year"];
	check($year,10,"Year");
	check_int($year,"Year");
	
	$vin = $_POST["vin"];
	check($vin,40,"VIN");
	
	$condition = $_POST["condition"];
	
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
	$xsearch = strtolower("barcode:$barcode, name:$name, model:$model, category:$category, color:$color, location:$location, condition:$condition, year:$year, vin:$vin");
	//echo($xsearch);
	$sql_code = "INSERT INTO `ew_car` 
	(`barcode`, `name`, `photo_url`, `model`, `category`, `color`, `year`, `vin`, `condition`, `p_price`, `w_price`, `r_price`, `quantity`, `w_quantity`, `l_zone`, `l_column`, `l_level`, `date`, `des`, `xsearch`) 
	VALUES ('$barcode', '$name', '$photo_url', '$model', '$category', '$color', '$year', '$vin', '$condition', '$p_price', '$w_price', '$r_price', '$quantity', '$w_quantity', '$l_zone', '$l_column', '$l_level', CURRENT_TIMESTAMP, '$des', '$xsearch');";
	//echo($sql_code);
	
	if (!($result=mysql_query($sql_code))) { 
			
			mysql_close($link); 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=new_car.php">');
		}
		else{
			tran($_COOKIE['ew_user_name'],$barcode,'car',$quantity);
			sys_log($_COOKIE['ew_user_name'],"add new car, barcode=$barcode,name=$name,amount=$quantity");
			//cart($_COOKIE['ew_user_name'],$barcode,$quantity,'ew_car');
			mysql_close($link);
			echo("<script>window.alert('New Car has been created!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=new_car.php">');
		}

	}

include('header.php');
?>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">

            <h2>Add a New Car</h2>
            <div class="cleaner"></div>
            <p>You can also add a new car thru <a href="car_temp.php">exsiting visual templates</a>, thus you do not have to worried about model, wholesale price, retail price etc. Barcode has to be a unique 12 digits number. If you are not familiar with unique number generation algorithms, simply leave it unchanged.</p>
            <div class="cleaner h30"></div>
            <div class="col_w320 float_r">
                <div id="newcar_form">
                    <form name="form" method="post" enctype="multipart/form-data">
					<label>Photo:</label><input type="file" name="file"><br />
					<label>Barcode: </label><input type="text" name="barcode" value ="<?php echo "0".substr(round(microtime(true) * 1000),0, -2); ?>"/><br />
					<label>Color: </label><input type="text" name="color"/><br />
					<label>Year: </label><input type="text" name="year" value="2013"/><br />
					<label>VIN: </label><input type="text" name="vin" value="undefine"/><br />
					<label>Car Name: </label><input type="text" name="name" value="<?php echo($defaultset[name]); ?>"/><br />
					<label>Model: </label><input type="text" name="model" value="<?php echo($defaultset[model]); ?>"/><br />
					<label>Category: </label>
					<select name="category">
					  <option value="finish" selected="selected")>Finished</option>
					  <option value="semi")>Semi-Finished</option>
					</select><br />
					<label>Condition: </label>
					<select name="condition">
					  <option value="new" selected="selected")>New</option>
					  <option value="used")>Used</option>
					  <option value="demo")>Demo</option>
					  <option value="damaged")>Damaged</option>
					</select><br />
					<label>Purchase Price: </label><input type="text" name="p_price" value="<?php echo($defaultset[pprice]); ?>"/><br />
					<label>Wholesale Price: </label><input type="text" name="w_price" value="<?php echo($defaultset[wprice]); ?>"/><br />
					<label>Retail Price: </label><input type="text" name="r_price" value="<?php echo($defaultset[rprice]); ?>"/><br />
					<label>Quantity: </label><input type="text" name="quantity"value="1"/><br />
					<label>Warning Quantity: </label><input type="text" name="w_quantity" value="0"/><br />
					<label>Location Zone: </label><input type="text" name="l_zone" value="<?php echo($defaultset[lzone]); ?>"/><br />
					<label>Location Column: </label><input type="text" name="l_column" value="<?php echo($defaultset[lcolumn]); ?>"/><br />
					<label>Location Level: </label><input type="text" name="l_level" value="<?php echo($defaultset[llevel]); ?>"/><br />
					<label>Description: </label><br/>
					<textarea name="des"></textarea><br/>
					<input type="submit" name="submit" class="submit_btn float_l" value="ADD CAR"/>
					&nbsp;&nbsp;<input type="reset" value="Reset" id="reset" name="reset" class="submit_btn" />
					</form>
                
                </div> 
            </div>
            
            <div class="col_w320 float_l">
                <h4>Photo Preview</h4>              
                
                <a href="<?php echo($defaultset[photo]); ?>" target="_blank"><img width="300" height="300" class ="withborder" src="<?php echo($defaultset[photo]); ?>" class="image_wrapper" /></a>   
                                  
                <div class="cleaner h30"></div>
                
                <h4>Model Templates</h4>
                <p><?php include('cartemp/index.php'); ?></p>
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
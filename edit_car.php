<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: edit_car.php
* This file updates the profile of an existing car item.
*/
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();


//handle delete car request
if ($_GET['do']=='del') { 
	
	stop('Function diabled, ask your admin for details.');
	
	$barcode = $_GET['barcode'];
	if(check_data('ew_car','barcode',$barcode)){
		$sql_code = "UPDATE `ew_car` SET `quantity` = '0', `w_quantity` = '-1', `date` = CURRENT_TIMESTAMP WHERE `barcode` = '".$barcode."';";
		if (!($result=mysql_query($sql_code))) { 
			
			mysql_close($link); 
			stop('DB Error!');
		}
		else{
			mysql_close($link);
			echo("<script>window.alert('Car has been removed!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=car_list.php">');
		}
	}else{
		stop("Barcode not found!");
	}	
}

//load profile with barcode
if (isset($_GET['barcode'])) { 
	$barcode = $_GET['barcode'];
	if(check_data('ew_car','barcode',$barcode)){
		$sql_code = "select * from ew_car where barcode = '".$barcode."';";
		$result_info = mysql_query($sql_code);
		$a_check = mysql_fetch_array($result_info);
	}else{
		stop("Barcode not found!");
	}	
}

//handle post form update profile request
if($_POST['submit']){
	if($_FILES["file"]["size"] == 0){
		$photo_url = $_POST["photo_url"];

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
			echo("<script>window.alert('Invalid file type or size exceed 500kb');</script>");
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
	//echo "===========";
	//echo $w_quantity;
	
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
	$sql_code = "UPDATE `ew_car` SET 
				`barcode` ='$barcode',
				`name` ='$name',
				`photo_url` ='$photo_url',
				`model` ='$model',
				`category` ='$category',
				`color` ='$color',
				`year` ='$year',
				`vin` ='$vin',
				`condition` ='$condition',
				`p_price` ='$p_price',
				`w_price` ='$w_price',
				`r_price` ='$r_price',
				`quantity` ='$quantity',
				`w_quantity` ='$w_quantity',
				`l_zone` ='$l_zone',
				`l_column` ='$l_column',
				`l_level` ='$l_level',
				`des` ='$des',
				`xsearch` ='$xsearch'
				WHERE `barcode` = '$barcode';";
	//echo($sql_code);
	
	if (!($result=mysql_query($sql_code))) { 
			
			mysql_close($link); 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=new_car.php">');
		}
		else{
			sys_log($_COOKIE['ew_user_name'],"edit car, barcode=$barcode,name=$name");
			mysql_close($link);
			echo("<script>window.alert('Car profile has been updated!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=view_car.php?barcode='.$barcode.'">');
		}

	}

include('header.php');
?>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">


<h2>Edit Car Profile</h2>
            <div class="cleaner"></div>
            <p>You can also add a new car thru <a href="car_temp.php">exsiting visual templates</a>, thus you do not have to worried about model, wholesale price, retail price etc. Barcode has to be a unique 12 digits number. If you are not familiar with unique number generation algorithms, simply leave it unchanged.</p>
            <div class="cleaner h30"></div>
            <div class="col_w320 float_r">
                <div id="newcar_form">
                    <form name="form" method="post" enctype="multipart/form-data">
					<label>Photo:</label><input type="file" name="file"><input type="text" style="display:none;" name="photo_url" value="<?php echo($a_check['photo_url']); ?>"/><br>
					<label>Barcode: </label><input type="text" name="barcode" value="<?php echo($a_check['barcode']); ?>"/><br />
					<label>Car Name: </label><input type="text" name="name" value="<?php echo($a_check['name']); ?>"/><br />
					<label>Model: </label><input type="text" name="model" value="<?php echo($a_check['model']); ?>"/><br />
					<label>Category: </label>
					<select name="category">
					  <option value="finish" <?php if($a_check['category'] == 'finish'){ echo("selected=\"selected\"");} ?>>Finished</option>
					  <option value="semi" <?php if($a_check['category'] == 'semi'){ echo("selected=\"selected\"");} ?>>Semi-Finished</option>
					</select><br />
					<label>Color: </label><input type="text" name="color" value="<?php echo($a_check['color']); ?>"/><br />
					<label>Year: </label><input type="text" name="year" value="<?php echo($a_check['year']); ?>"/><br />
					<label>VIN: </label><input type="text" name="vin" value="<?php echo($a_check['vin']); ?>"/><br />
					<label>Condition: </label>
					<select name="condition">
					  <option value="new" <?php if($a_check['condition'] == 'new'){ echo("selected=\"selected\"");} ?>>New</option>
					  <option value="used" <?php if($a_check['condition'] == 'used'){ echo("selected=\"selected\"");} ?>>Used</option>
					  <option value="demo" <?php if($a_check['condition'] == 'demo'){ echo("selected=\"selected\"");} ?>>Demo</option>
					  <option value="damaged" <?php if($a_check['condition'] == 'damaged'){ echo("selected=\"selected\"");} ?>>Damaged</option>
					</select><br />
					<label>Purchase Price: </label><input type="text" name="p_price" value="<?php echo($a_check['p_price']); ?>"/><br />
					<label>Wholesale Price: </label><input type="text" name="w_price" value="<?php echo($a_check['w_price']); ?>"/><br />
					<label>Retail Price: </label><input type="text" name="r_price" value="<?php echo($a_check['r_price']); ?>"/><br />
					<label>Quantity: <?php echo($a_check['quantity']); ?></label><input type="text" style="display:none;" name="quantity" value="<?php echo($a_check['quantity']); ?>"/><br />
					<label>Warning Quantity: </label><input type="text" name="w_quantity" value="<?php echo($a_check['w_quantity']); ?>"/><br />
					<label>Location Zone: </label><input type="text" name="l_zone" value="<?php echo($a_check['l_zone']); ?>"/><br />
					<label>Location Column: </label><input type="text" name="l_column" value="<?php echo($a_check['l_column']); ?>"/><br />
					<label>Location Level: </label><input type="text" name="l_level" value="<?php echo($a_check['l_level']); ?>"/><br />
					<label>Description: </label><br/>
					<textarea rows="4" cols="50" name="des"><?php echo($a_check['des']); ?></textarea><br/>
					<input type="submit" name="submit" class="submit_btn float_l" value="Edit Profile"/>

					</form>
                
                </div> 
            </div>
            
            <div class="col_w320 float_l">
                <h4>Photo Preview</h4>              
                
                <a href="<?php echo($a_check['photo_url']); ?>" target="_blank"><img width="300" height="300" class ="withborder" src="<?php echo get_thumb($a_check['photo_url']); ?>" class="image_wrapper" /></a>   
				<div class="cleaner h30"></div>
                  <p><a href="edit_car.php?do=del&barcode=<?php echo $a_check["barcode"]; ?>">[ Delete ]</a> - Warning, delete means permanent removed from database. Deleted items can not be recovered.</p>
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
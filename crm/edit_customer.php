<?PHP
/*
* Copyright © 2013 Elaine CRM
* File: index.php
* This file display a user panel to access all frequently used functions.
*/
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('lib/sql.php');
include('lib/user_lib.php');
check_user_cookie();

$defaultset[photo] = "upload/defaultlogo.jpg";

if(isset($_GET['cid'])){
	$cid = $_GET['cid'];
	if(check_data('ec_customer','cid',$cid)){
		$sql_code = "select * from `ec_customer` where `cid` = '".$cid."';";
		$result_info = mysql_query($sql_code);
		$a_check = mysql_fetch_array($result_info);
		$sql_code_contact = "select * from `ec_contact` where `cid` = '".$cid."';";
		$result_contact = mysql_query($sql_code_contact);
	}else{
		stop("CID(Customer ID) not found!");
	}
}

if($_GET['del']){
	$contact_id = $_GET['del'];
	$sql_code_del = "delete from `ec_contact` where `contact_id` ='$contact_id';";
	if (!($result=mysql_query($sql_code_del))) { 
		mysql_close($link); 
		stop('DB Error!');
	}else{
		die('<meta http-equiv="refresh" content="0;URL=edit_customer.php?cid='.$cid.'">');
	}
}

if($_POST['submit_contact']){
	$cid = $_POST["cid"];
	
	$name_contact = $_POST["name_contact"];
	check($name_contact,40,"Name");
	
	$title_contact = $_POST["title_contact"];
	if($title_contact != ""){check($title_contact,40,"Title");}
	
	$phone_contact = $_POST["phone_contact"];
	if($phone_contact != ""){check($phone_contact,20,"Phone");}
	
	$email_contact = $_POST["email_contact"];
	if($email_contact != ""){check($email_contact,80,"Email");}
	
	$sql_code="
	INSERT INTO `ec_contact` (
	`cid` ,
	`name` ,
	`title` ,
	`phone` ,
	`email`
	)
	VALUES (
	'$cid',  '$name_contact',  '$title_contact',  '$phone_contact',  '$email_contact'
	);";
	
	if (!($result=mysql_query($sql_code))) { 
			
			mysql_close($link); 
			stop('DB Error!');
		}else{
			die('<meta http-equiv="refresh" content="0;URL=edit_customer.php?cid='.$cid.'">');
		}
}

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
			&& ($_FILES["file"]["size"] < 200000)
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
			  }
			}
		  }else{
			echo("<script>window.alert('Invalid file type or size exceed 2000kb');</script>");
		  }
	}
	//echo($photo_url);
	
	$name = $_POST["name"];
	check($name,100,"Name");
	
	$contact = $_POST["contact"];
	check($contact,40,"Contact");
	
	$phone = $_POST["phone"];
	check($phone,20,"Phone");
	
	$fax = $_POST["fax"];
	if($fax != ""){
		check($fax,20,"Fax");
	}
	
	$email = $_POST["email"];
	check($email,80,"Email");
	
	$web = $_POST["web"];
	if($web != ""){
		check($web,80,"Website");
	}
	
	$address1 = $_POST["address1"];
	if($address1 != ""){
		check($address1,100,"Address Line 1");
	}
	
	$address2 = $_POST["address2"];
	if($address2 != ""){
		check($address2,100,"Address Line 2");
	}
	
	$des = $_POST["des"];
	
	$type = $_POST["type"];
	
	$status = $_POST["status"];
	
	$xsearch = strtolower("company:$name, type:$type, status:$status, contact:$contact, phone:$phone, fax:$fax, web:$web, email:$email , address:$address1 $address2");
	//echo($xsearch);
	//$sql_code = "INSERT INTO `ec_customer` (`cid`, `logo_url`, `name`, `contact`, `phone`, `fax`, `email`, `web`, `address1`, `address2`, `des`, `interact`, `type`, `status`, `xsearch`) VALUES (NULL, '$photo_url', '$name', '$contact', '$phone', '$fax', '$email', '$web', '$address1', '$address2', '$des', CURRENT_TIMESTAMP, '$type', '$status', '$xsearch');";
	$sql_code = "UPDATE `ec_customer` SET 
				`logo_url` ='$photo_url',
				`name` ='$name',
				`contact` ='$contact',
				`phone` ='$phone',
				`fax` ='$fax',
				`email` ='$email',
				`web` ='$web',
				`address1` ='$address1',
				`address2` ='$address2',
				`des` ='$des',
				`interact` = CURRENT_TIMESTAMP,
				`type` ='$type',
				`status` ='$status',
				`xsearch` ='$xsearch'
				WHERE `cid` = '$cid';";
	//echo($sql_code);

	if (!($result=mysql_query($sql_code))) { 
			
			mysql_close($link); 
			stop('DB Error!');
		}else{
			sys_log($_COOKIE['ec_user_name'],"add new customer, company:$name, phone:$phone");
			mysql_close($link);
			echo("<script>window.alert('Customer Profile has been updated!');</script>");
			//remember to change
			die('<meta http-equiv="refresh" content="0;URL=view.php?cid='.$cid.'">');
		}

	}

include('header.php');
//print_r($_COOKIE);
?>

<script type="text/javascript">
function new_contact(cid)
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
		document.getElementById("new_contact").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/new_contact.php?cid="+cid,true);
	xmlhttp.send();
	}
function close_add()
	{
	document.getElementById("new_contact").innerHTML= "";
	}
</script>
    
<div id="main"><span class="mf mft"></span><span class="mf mfb"></span>
<div class="col col_2">
<h3>Edit Customer Profile</h3>

<div id="newcus_form">
<img class = "image_frame" src="<?php echo($a_check['logo_url']); ?>"/>
<form name="form" method="post" enctype="multipart/form-data">

<label>CID: <?php echo($a_check['cid']); ?></label><input type="text" name="cid" style="display:none;" value="<?php echo($a_check['cid']); ?>"/><br />
<label>Customer Logo:</label><input type="file" name="file" ><input type="text" style="display:none;" name="photo_url" value="<?php echo($a_check['logo_url']); ?>"/><br />
<label>Current Status:</label>
<select name="status" class="select_field w90">
<option value="potential" <?php if($a_check['status'] == 'potential'){ echo("selected=\"selected\"");} ?>>Potential</option>
<option value="existing" <?php if($a_check['status'] == 'existing'){ echo("selected=\"selected\"");} ?>>Existing</option>
</select><br />
<label>Customer Type:</label>
<select name="type" class="select_field w90">
<option value="dealer" <?php if($a_check['type'] == 'dealer'){ echo("selected=\"selected\"");} ?>>Dealer</option>
<option value="partner" <?php if($a_check['type'] == 'partner'){ echo("selected=\"selected\"");} ?>>Partner</option>
<option value="enterprise" <?php if($a_check['type'] == 'enterprise'){ echo("selected=\"selected\"");} ?>>Enterprise</option>
<option value="individual" <?php if($a_check['type'] == 'individual'){ echo("selected=\"selected\"");} ?>>Individual</option>
</select><br />
<label>INC. Name*:</label><input type="text" name="name" class="input_field_w w180" value ="<?php echo($a_check['name']); ?>"/><br />
<label>Contact Person*: </label><input type="text" name="contact" class="input_field_w w180" value ="<?php echo($a_check['contact']); ?>"/><br />
<label>Phone Number*: </label><input type="text" name="phone" class="input_field_w w180" value ="<?php echo($a_check['phone']); ?>"/><br />
<label>Fax Number: </label><input type="text" name="fax" class="input_field_w w180" value ="<?php echo($a_check['fax']); ?>"/><br />
<label>Email Address*: </label><input type="text" name="email" class="input_field_w w180" value ="<?php echo($a_check['email']); ?>"/><br />
<label>Company Web: </label><input type="text" name="web" class="input_field_w w180" value ="<?php echo($a_check['web']); ?>"/><br />
<label>Address Line1: </label><input type="text" name="address1" class="input_field_w w180" value ="<?php echo($a_check['address1']); ?>"/><br />
<label>Address Line2: </label><input type="text" name="address2" class="input_field_w w180" value ="<?php echo($a_check['address2']); ?>"/><br />
<br/>
<label>Description: </label><br/>
<textarea name="des" class="w280"><?php echo($a_check['des']); ?></textarea><br/>
<input type="submit" name="submit" class="submit_btn float_l" value="Edit"/>
&nbsp;&nbsp;<input type="reset" value="Reset" id="reset" name="reset" class="submit_btn" />
</form>
                
</div> 

</div>
        
<div class="col col_2">
<h3>Additional Contacts</h3>
<button type="button" class="submit_btn" onclick="new_contact(<?php echo($a_check['cid']); ?>)">Add New Contact</button>
<div id="new_contact">
</div>
<ol>
<?php while ($contact_row = mysql_fetch_assoc($result_contact)) { ?> 
<li>
Contact: <?php echo $contact_row["name"]; ?> <a href="edit_customer.php?cid=<?php echo($a_check['cid']); ?>&del=<?php echo $contact_row["contact_id"]; ?>">[Del]</a><br />
Title: <?php echo $contact_row["title"]; ?> <br />
Phone: <?php echo $contact_row["phone"]; ?> <br />
Email: <?php echo $contact_row["email"]; ?>
</li><br />
<?php }; ?> 
</ol>
</div>

<div class="clear"></div>
</div> <!-- END of main -->
    

    

<?PHP
include('footer.php');
?>
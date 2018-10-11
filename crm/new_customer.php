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
			echo("<script>window.alert('Invalid file type or size exceed 200kb');</script>");
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
	$sql_code = "INSERT INTO `ec_customer` (`cid`, `logo_url`, `name`, `contact`, `phone`, `fax`, `email`, `web`, `address1`, `address2`, `des`, `interact`, `type`, `status`, `xsearch`) VALUES (NULL, '$photo_url', '$name', '$contact', '$phone', '$fax', '$email', '$web', '$address1', '$address2', '$des', CURRENT_TIMESTAMP, '$type', '$status', '$xsearch');";
	//echo($sql_code);

	if (!($result=mysql_query($sql_code))) { 
			
			mysql_close($link); 
			stop('DB Error!');
		}
		else{
			sys_log($_COOKIE['ec_user_name'],"add new customer, company:$name, phone:$phone");
			mysql_close($link);
			echo("<script>window.alert('New Customer has been created!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=new_customer.php">');
		}

	}

include('header.php');
//print_r($_COOKIE);
?>

<script type="text/javascript">
function load()
	{
	var xmlhttp;
	var url = document.getElementById("load_url").value;
	var postdata = "link="+url;
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
		document.getElementById("load_page").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("POST","ajax/load.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", postdata.length);
	xmlhttp.send(postdata);
	}
</script>
    
<div id="main"><span class="mf mft"></span><span class="mf mfb"></span>
<div class="col col_2">
<h3>New Customer Profile</h3>

<div id="newcus_form">
<img class = "image_frame"src="upload/defaultlogo.jpg"/>
<form name="form" method="post" enctype="multipart/form-data">
<label>Customer Logo:</label><input type="file" name="file" ><br />
<label>Current Status:</label>
<select name="status" class="select_field w90">
<option value="potential")>Potential</option>
<option value="existing" selected="selected")>Existing</option>
</select><br />
<label>Customer Type:</label>
<select name="type" class="select_field w90">
<option value="dealer" selected="selected")>Dealer</option>
<option value="partner" )>Partner</option>
<option value="enterprise" )>Enterprise</option>
<option value="individual" )>Individual</option>
</select><br />
<label>INC. Name*:</label><input type="text" name="name" class="input_field_w w180" value =""/><br />
<label>Contact Person*: </label><input type="text" name="contact" class="input_field_w w180" value =""/><br />
<label>Phone Number*: </label><input type="text" name="phone" class="input_field_w w180" value =""/><br />
<label>Fax Number: </label><input type="text" name="fax" class="input_field_w w180" value =""/><br />
<label>Email Address*: </label><input type="text" name="email" class="input_field_w w180" value =""/><br />
<label>Company Web: </label><input type="text" name="web" class="input_field_w w180" value =""/><br />
<label>Address Line1: </label><input type="text" name="address1" class="input_field_w w180" value =""/><br />
<label>Address Line2: </label><input type="text" name="address2" class="input_field_w w180" value =""/><br />
<br/>
<label>Description: </label><br/>
<textarea name="des" class="w280"></textarea><br/>
<input type="submit" name="submit" class="submit_btn float_l" value="Create"/>
&nbsp;&nbsp;<input type="reset" value="Reset" id="reset" name="reset" class="submit_btn" />
</form>
                
</div> 

</div>
        
<div class="col col_2">
<h3>Turbo Copy Paste</h3>
<input type="text" id="load_url" name="url" class="input_field_w w180" value =""/> <button type="button" class="submit_btn" onclick="load()">Load</button>
<div id="load_page">
</div>
</div>

<div class="clear"></div>
</div> <!-- END of main -->
    

    

<?PHP
include('footer.php');
?>
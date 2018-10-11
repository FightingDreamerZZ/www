<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: msg.php
* This file displays messages, performs message related functions
*/
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//load messages
$sql_code_1 = "SELECT * FROM `ew_message` ORDER BY `mid` DESC;";
$result_info_1 = mysql_query($sql_code_1);

//handle delete message request
if(isset($_GET['del'])){
	$mid = $_GET['del'];
	$sql_delfile = "SELECT `path` FROM `ew_message` WHERE `mid` = '".$mid."';";
	$result_file = mysql_query($sql_delfile);
	$del_file = mysql_result($result_file, 0);
	if (!unlink($del_file)){
		echo "<script>window.alert('System did not find relative download file!');</script>";
	}
	$sql_del = "DELETE FROM `ew_message` WHERE `mid` = '".$mid."';";
	if(!($result=mysql_query($sql_del))){ 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}else{
			die('<meta http-equiv="refresh" content="0;URL=msg.php">');
		}
}


if($_POST['compose']){
	if($_FILES["file"]["size"] == 0){
		$photo_url = "";
	}else{
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);
		if ($_FILES["file"]["size"] < 1000000){
		
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
			echo "Size exceeded 1000kb!";
		  }
	}
	$sql_msg = "INSERT INTO `ew_message` (`mid`, `user`, `message`, `time`, `path`) VALUES (NULL, '".$_COOKIE['ew_user_name']."', '".$_POST['msg']."', CURRENT_TIMESTAMP,'".$photo_url."');";
	if(!($result=mysql_query($sql_msg))){ 
			stop("DB_Error!");
		}else{
			die('<meta http-equiv="refresh" content="0;URL=msg.php">');
		}


}


include('header.php');
?>
<script type="text/javascript">
function eraseText() {
    document.getElementById("text_area").value = "";
}
function load()
   {
      document.form.serial.focus();
   }
</script>
<div id="main">
	 
<div class="content_box_top"></div>
<div class="content_box">
<h2>Message Center</h2>
<ul class="list">
<?php 
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
?> 

<li>Message ID: <?php echo $row_1[mid]; ?>&emsp; Composed by: <?php echo $row_1[user]; ?>&emsp; AT: <?php echo $row_1[time]; ?>&emsp; <a href="<?php echo $row_1[path]; ?>">[Download]</a>&emsp; <a href="msg.php?del=<?php echo $row_1[mid]; ?>">[Delete]</a></li>
<p><?php echo $row_1[message]; ?></p>

<?php 
}; 
?> 
</ul>

<div id="msg_form">
<form name="form" method="post" enctype="multipart/form-data">
	<input type="submit" name="compose" class="submit_btn_tab float_l" value="Compose Message"/>
	<input type="button" class="submit_btn_tab float_l" value="Clear Textarea" onclick="javascript:eraseText();"/>
	
	<div class="cleaner"></div>
	<textarea name="msg" id="text_area"></textarea>
	<div class="cleaner"></div>
	<input type="file" name="file" class="submit_btn_tab" >
</form>
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
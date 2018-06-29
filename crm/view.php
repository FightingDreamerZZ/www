<?PHP
/*
* Copyright © 2013 Elaine CRM
* File: index.php
* This file display a user panel to access all frequently used functions.
*/
error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');
check_user_cookie();

if(isset($_GET['cid'])){
	$cid = $_GET['cid'];
	if(check_data('ec_customer','cid',$cid)){
		$sql_code = "select * from `ec_customer` where `cid` = '".$cid."';";
		$result_info = mysql_query($sql_code);
		$a_check = mysql_fetch_array($result_info);
		
		//load lists with page spliter
		$split_by = '10';

		if (isset($_GET["page"])) { 
			$page = $_GET["page"]; 
		} else { 
			$page=1; 
		}
		$start_from = ($page-1) * $split_by;
		
		$sql_code_log = "SELECT * FROM `ec_commlog` WHERE `client` = '".$cid."' ORDER BY `log_time` DESC LIMIT ".$start_from.",".$split_by.";";
		$result_logs = mysql_query($sql_code_log);	
		$sql_log_2 = "SELECT COUNT(lid) FROM `ec_commlog` WHERE `client` = '".$cid."';"; 
		$result_log_2 = mysql_query($sql_log_2);
		
		$row_2 = mysql_fetch_row($result_log_2); 
		$total_records = $row_2[0]; 
		$total_pages = ceil($total_records / $split_by); 
		
		$sql_code_contact = "select * from `ec_contact` where `cid` = '".$cid."';";
		$result_contact = mysql_query($sql_code_contact);
		
	}else{
		stop("CID(Customer ID) not found!");
	}
}else{
	//stop("CID(Customer ID) required!");
}

if(isset($_GET['del'])){
	$lid = $_GET['del'];
	$cid = $_GET['cid'];
	$sql_del = "DELETE FROM `ec_commlog` WHERE `lid` = '".$lid."';";
	if(!($result=mysql_query($sql_del))){ 
			echo("<script>window.alert('DB Error!');</script>");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}else{
			die('<meta http-equiv="refresh" content="0;URL=view.php?cid='.$cid.'">');
		}
}

if($_POST['submit']){
	if($_FILES["file"]["size"] == 0){
		$photo_url = "";
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
		  
	$cid = $_POST["cid"];
	$user = $_COOKIE['ec_user_name'];
	
	$title = str_replace("'", "&rsquo;", $_POST["title"]);
	check($title,100,"Title");
	
	if(($_POST["date"] != "") && ($_POST["time"] != "")){
		$time = $_POST["date"]." ".$_POST["time"].":00";
	}else{
		$time = NULL;
	}
	
	$location = str_replace("'", "&rsquo;", $_POST["location"]);
	if($location != ""){
		check($location,255,"Location");
	}
	
	
	$content = str_replace("'", "&rsquo;", $_POST["content"]);
	if($content != ""){
		check($content,60000,"Content");
	}
	
	$sql_code="INSERT INTO `ec_commlog` (`lid`, `user`, `client`, `title`, `content`, `log_time`, `event_time`, `location`, `photo_url`) VALUES (NULL, '$user', '$cid', '$title', '$content', CURRENT_TIMESTAMP, '$time', '$location', '$photo_url');";
	
	if (!($result=mysql_query($sql_code))) { 
		mysql_close($link); 
		stop('DB Error!');
	}else{
		update_interact($cid);
		mysql_close($link);
		echo("<script>window.alert('New record has been added!');</script>");
		//remember to change
		die('<meta http-equiv="refresh" content="0;URL=view.php?cid='.$cid.'">');
	}
	
	
}

//$load = " onload=\"load()\"";
$title_by_page = "View";
include('header.php');

?>

<script type="text/javascript">
function new_record(cid)
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
		document.getElementById("add_new").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/new_record.php?cid="+cid,true);
	xmlhttp.send();
	}
	
function new_event(cid)
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
		document.getElementById("add_new").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/new_event.php?cid="+cid,true);
	xmlhttp.send();
	}
	
	function counter(Object)
	{
	var v_len = Object.value.length;
	document.getElementById("d_counter").innerHTML= "["+v_len+"/60000]";
	}
	
	function close_add()
	{
	document.getElementById("add_new").innerHTML= "";
	}
	
</script>

    
<div id="main"><span class="mf mft"></span><span class="mf mfb"></span>
        
 <div id="content" class="col col_32">
 <div id="add_new">
 </div>
 
<?php 
while ($log_row = mysql_fetch_assoc($result_logs)) { 
?> 
<div class="post-item" id="<?php echo $log_row["lid"]; ?>">
	<div class="post-meta">
	<img src="images/author.png" alt="post author image" />
		<div class="post-meta-content">
			<h2><?php echo $log_row["title"]; ?></h2>
			This <span><?php echo record_event($log_row["event_time"]); ?></span> was created
            by <span><?php echo $log_row["user"]; ?></span>
            on <span><?php echo $log_row["log_time"]; ?></span>
        </div>
        <div class="post_comment"><a href="view.php?cid=<?php echo($a_check['cid']); ?>&del=<?php echo $log_row["lid"]; ?>"><span>DEL</span></a></div>
        <div class="clear"></div>
	</div>
        <?php if($log_row["photo_url"] != ""){echo "<img class=\"img_border img_border_b img_nom\" width=\"586px\" src=\"".$log_row["photo_url"]."\" />";} ?>
		<?php if(record_event($log_row["event_time"]) != "Record"){echo "<ul><b><li>Event Time: ".$log_row["event_time"]."</li><li>Location: ".$log_row["location"]."</li></b></ul>";} ?>
        <p><?php echo $log_row["content"]; ?></p>
</div>
<?php 
}; 
?> 

<div class="paging">
<ul>
<li><span>Page:</span></li>
<?php 

for ($i=1; $i<=$total_pages; $i++) { 
            echo "<li><a href='view.php?".trim_url("&page=")."&page=".$i.$urltag."'>".$i."</a></li> "; 
}; 
?>
</ul>
<div class="clear"></div>
</div>
        
</div>

        
        <div id="sidebar" class="col col_3">
        	<div class="sidebar_section">
                <h3><button type="button" class="submit_btn" onclick="new_record(<?php echo($a_check['cid']); ?>)">Add Record</button>
				<button type="button" class="submit_btn" onclick="new_event(<?php echo($a_check['cid']); ?>)">Add Event</button></h3>
                <h3><?php echo($a_check['name']); ?></h3>
				<img class = "image_frame" width="262px" src="<?php echo($a_check['logo_url']); ?>"/>
                <ul class="sidebar_link_list">
                    <li>Contact: <?php echo($a_check['contact']); ?><a class="right" href="edit_customer.php?cid=<?php echo $a_check["cid"]; ?>">Edit Profile</a></li>
                    <li>Phone: <?php echo($a_check['phone']); ?></li>
                    <li>Fax: <?php echo($a_check['fax']); ?></li>
                    <li>Email: <a href="mailto:<?php echo($a_check['email']); ?>"><?php echo($a_check['email']); ?></a></li>
                    <li>Web: <a target="_blank" href="http://<?php echo($a_check['web']); ?>"><?php echo($a_check['web']); ?></a></li>
                    <li>Address: <?php echo($a_check['address1'].$a_check['address2']); ?></li>
					<li>Customer ID: <?php echo($a_check['cid']); ?></li>
					<li>Status: <?php echo($a_check['status']); ?></li>
					<li>Type: <?php echo($a_check['type']); ?></li>
					<li>Interact: <?php echo($a_check['interact']); ?></li>
					<li>Description: <?php echo($a_check['des']); ?></li>
                </ul>
			</div>
            
            
            <div class="sidebar_section sidebar_section_bg">
                <h3>Additional Contact</h3>
                <ul class="sidebar_link_list">
					<?php while ($contact_row = mysql_fetch_assoc($result_contact)) { ?> 
					<li>
					Contact: <?php echo $contact_row["name"]; ?><br />
					Title: <?php echo $contact_row["title"]; ?> <br />
					Phone: <?php echo $contact_row["phone"]; ?> <br />
					Email: <?php echo $contact_row["email"]; ?>
					</li><br />
					<?php }; ?> 
                </ul>
			</div>
            
        </div>
    	
      <div class="clear"></div>		
		

</div> <!-- END of main -->
    
 
    

<?PHP
include('footer.php');
?>
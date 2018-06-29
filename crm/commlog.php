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

//load lists with page spliter
$split_by = '12';
if (isset($_GET["page"])) { 
	$page = $_GET["page"]; 
} else { 
	$page=1; 
}
$start_from = ($page-1) * $split_by;
if($_GET[display] == "all"){
	$sql_code_log = "SELECT * FROM `ec_commlog` ORDER BY `log_time` DESC LIMIT ".$start_from.",".$split_by.";";
	$sql_log_2 = "SELECT COUNT(lid) FROM `ec_commlog`;"; 
}else if(isset($_GET[display_user])){
	$sql_code_log = "SELECT * FROM `ec_commlog` WHERE `user` = '".$_GET[display_user]."' ORDER BY `log_time` DESC LIMIT ".$start_from.",".$split_by.";";
	$sql_log_2 = "SELECT COUNT(lid) FROM `ec_commlog` WHERE `user`='".$_GET[display_user]."';"; 
}else{
	$sql_code_log = "SELECT * FROM `ec_commlog` WHERE `user` = '".$_COOKIE['ec_user_name']."' ORDER BY `log_time` DESC LIMIT ".$start_from.",".$split_by.";";
	$sql_log_2 = "SELECT COUNT(lid) FROM `ec_commlog` WHERE `user`='".$_COOKIE['ec_user_name']."';"; 
}

$result_logs = mysql_query($sql_code_log);	
$result_log_2 = mysql_query($sql_log_2);
		
$row_2 = mysql_fetch_row($result_log_2); 
$total_records = $row_2[0]; 
$total_pages = ceil($total_records / $split_by); 

//$load = " onload=\"load()\"";
include('header.php');

?>
<script type="text/javascript">
</script>

    
<div id="main"><span class="mf mft"></span><span class="mf mfb"></span>

<div id="commlog">
<ul>

<?php 
while ($log_row = mysql_fetch_assoc($result_logs)) { 
?> 
	<li>
	<img src="<?php if($log_row["photo_url"] != ""){echo $log_row["photo_url"];}else{ echo "upload/commlogdefault.jpg";} ?>" width="266" height="133"/>
	<span><b><?php echo $log_row["title"]; ?></b><br /> 
	<i>by <?php echo $log_row["user"]; ?>, on <?php echo substr($log_row["log_time"],0,-9); ?><br />
	in <?php echo get_cname($log_row["client"]); ?> [<?php echo record_event($log_row["event_time"]); ?>]</i><br />
	<?php echo strip_tags(substr($log_row["content"],0,320)."..."); ?><a href="view.php?cid=<?php echo $log_row["client"]."#".$log_row["lid"]; ?>">More</a></span>
	</li>
<?php 
}; 
?> 	
	  
</ul>
</div>

<div class="col col_1">
	<div class="paging">
	<ul>
	<li><span>Page:</span></li>
	<?php 

	for ($i=1; $i<=$total_pages; $i++) { 
				echo "<li><a href='commlog.php?".trim_url("&page=")."&page=".$i.$urltag."'>".$i."</a></li> "; 
	}; 
	?>
	</ul>
	</div>
</div>
<div class="clear"></div>
</div>

		
<div class="clear"></div>
</div> <!-- END of main -->
    
 
    

<?PHP
include('footer.php');
?>
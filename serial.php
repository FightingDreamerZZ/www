<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: serial.php
* This file handles serial inputs from barcode scanner
*/
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//enter warehouse
if($_POST['enter']){
	$serial = explode(PHP_EOL, $_POST['serial']);
	$counter = 0;
	foreach ($serial as $line) {
		if(strlen($line) == 12){
			$table = get_table($line);
			cart($_COOKIE['ew_user_name'],$line,1,$table);
			$counter = $counter + 1;
		}
	}
	$msg = "<p>$counter entry(s) has dumped to <a href=\"enter.php\">[cart]</a>.</p>";
}

//depart warehouse
if($_POST['depart']){
	$serial = explode(PHP_EOL, $_POST['serial']);
	$valid = true;
	$counter = 0;
	$counter_fail = 0;
	$str_fail ="";
	foreach ($serial as $line) {
		if(strlen($line) == 12 && cart_valid($line)){
			$table = get_table($line);
			cart($_COOKIE['ew_user_name'],$line,-1,$table);
			$counter = $counter + 1;
		}else if(strlen($line) == 12){
			$counter_fail = $counter_fail+1;
			$str_fail = $str_fail.$line."\n";
		}
	}
	
	$msg = "<p>$counter entry(s) has dumped to <a href=\"enter.php\">[cart]</a>.</p>";
	if($counter_fail > 0){
		$msg = $msg."<p>$counter_fail entry(s) can not be dumped due to stock issues(listed below).</a>.</p>";
	}
}


$load = " onload=\"load()\"";
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



<h2>Barcode Serial Input</h2>

<div id="serial_form">
<form name="form" method="post" enctype="multipart/form-data">
	<input type="button" class="submit_btn_tab float_l" value="Clear" onclick="javascript:eraseText();"/>
	<input type="submit" name="enter" class="submit_btn_tab float_l" value="Arrive"/>
	<input type="submit" name="depart" class="submit_btn_tab float_l" value="Depart"/>
	<div class="cleaner"></div>
	<?php echo($msg); ?>
	<div class="cleaner"></div>
	<textarea name="serial" id="text_area"><?php echo($str_fail); ?></textarea>
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
<?PHP
error_reporting(E_ALL ^ E_NOTICE);
include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

if(isset($_GET['barcode'])){
	$barcode = $_GET['barcode'];
}

?>


<form name="form1" method="get">
	
	<input type="text" name="new_value" style="width:30px;" autocomplete="off" value = "<?php echo cart_amount($_COOKIE['ew_user_name'],$barcode) ?>"/>
	
	<input type="submit" style="display:none;" name="barcode" value="<?php echo $barcode; ?>"/>
</form>



<?PHP
error_reporting(E_ALL ^ E_NOTICE);
include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

$user_of_cart = $_GET['user'];

if(isset($_GET['barcode'])){
	$barcode = $_GET['barcode'];
}
if(isset($_GET['appli'])){
    $appli = $_GET['appli'];
}

if(isset($_GET['field'])){
    $field = $_GET['field'];
}

switch ($field){

    case "appli":
        $value = $appli;
        $width = "100px";
        break;
    default:
        $value = cart_amount($user_of_cart,$barcode);
        $width = "30px";
        break;
}


?>


<form name="form1" method="get">
	<input type="text" name="new_value" style="width: <?php echo $width;?>;" autocomplete="off" value = "<?php
        echo $value;
    ?>"/>
	
	<input type="submit" style="display:none;" name="barcode" value="<?php echo $barcode; ?>"/>
    <input type="text" style="display:none;" name="appli" value = "<?php echo $appli;?>" autocomplete="off"/>
    <input type="text" style="display:none;" name="field" value = "<?php echo $field;?>" autocomplete="off  "/>
    <input type="text" style="display:none;" name="user" value = "<?php echo $user_of_cart;?>" autocomplete="off"/>
</form>



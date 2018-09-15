<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: cart_edit_for_carts_tbp.php
* This file is for ajax edit request, mainly for carts_to_proceed_detail page which for admin to proceed submitted carts into DB. tbp == to be proceeded
*/
error_reporting(E_ALL ^ E_NOTICE);
include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

$user_of_cart = $_GET['user'];

function error() {
    stop("Item information error!");
}

if(isset($_GET['barcode'])){
	$barcode = $_GET['barcode'];
}else{error();}
if(isset($_GET['appli'])){
    $appli = $_GET['appli'];
}else{error();}

if(isset($_GET['field'])){
    $field = $_GET['field'];
}
elseif($_GET['do'] == 'delete') {
    $field = "appli";
} else{error();}

if($_GET['do'] == 'delete'){
    cart_delete_single_entity($user_of_cart,$barcode,$appli);
}

//switch ($field){
//
//    case "appli":
//        $value = $appli;
//        $style = "width:100px;display:block;";
//        break;
//    default:
//        $value = cart_amount($user_of_cart,$barcode);
//        $style = "width:100px;display:block;";
//        break;
//}


?>
<script>
    function load() {
        document.getElementById("select_appli").value = "<?php echo $appli;?>";
    }
</script>

<form name="form1" method="get">
    <select name="<?php echo ($field == 'appli')?'new_value':'none';?>"
            id="select_appli"
            style="<?php echo ($field == 'appli')?'':'display:none';?>">
        <option value="unknown">Unknown</option>
        <option value="sold_retail">Sold as retail</option>
        <option value="sold_wholesale">Sold as wholesale</option>
        <option value="consumed_repair" title="Consumed when repairing a vehicle in warranty..">Warranty</option>
        <option value="consumed_assembly">Consumed in assembly</option>
    </select>
	<input type="text"
           name="<?php echo ($field == 'appli')?'none':'new_value';?>"
           style="width: 30px;<?php echo ($field == 'appli')?'display:none':'';?>" autocomplete="off"
           value = "<?php echo cart_amount($user_of_cart,$barcode);?>"/>
	
	<input type="submit" style="" name="Submit" value="Submit"/>
    <input type="text" style="display:none;" name="barcode" value = "<?php echo $barcode; ?>" autocomplete="off"/>
    <input type="text" style="display:none;" name="appli" value = "<?php echo $appli;?>" autocomplete="off"/>
    <input type="text" style="display:none;" name="field" value = "<?php echo $field;?>" autocomplete="off  "/>
    <input type="text" style="display:none;" name="user" value = "<?php echo $user_of_cart;?>" autocomplete="off"/>
</form>



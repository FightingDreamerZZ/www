<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: ypartnum_edit_for_create_part_order.php
* This file is for editing part_num_yigao of part table in an ajax request, mainly for create_part_ordering_sheet page
*/
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

function error() {
    stop("Item information error!");
}

if(isset($_GET['barcode'])){
    $barcode = $_GET['barcode'];
}else{error();}

if(isset($_GET['old_part_num_yigao'])){
    $old_part_num_yigao = $_GET['old_part_num_yigao'];
}else{error();}
//if(isset($_GET['appli'])){
//    $appli = $_GET['appli'];
//}else{error();}
//
//if(isset($_GET['field'])){
//    $field = $_GET['field'];
//}
//elseif($_GET['do'] == 'delete') {
//    $field = "appli";
//} else{error();}

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
    //function load() {
    //    document.getElementById("select_appli").value = "<?php //echo $appli;?>//";
    //}
</script>

<form name="form1" method="get">
<!--    <select name="--><?php //echo ($field == 'appli')?'new_value':'none';?><!--"-->
<!--            id="select_appli"-->
<!--            style="--><?php //echo ($field == 'appli')?'':'display:none';?><!--">-->
<!--        <option value="unknown">Unknown</option>-->
<!--        <option value="sold_retail">Sold as retail</option>-->
<!--        <option value="sold_wholesale">Sold as wholesale</option>-->
<!--        <option value="consumed_repair" title="Consumed when repairing a vehicle in warranty..">Warranty</option>-->
<!--        <option value="consumed_assembly">Consumed in assembly</option>-->
<!--    </select>-->
    <label for="txt_part_num_yigao">Part Number(Yigao): </label>
    <input type="text"
           name="txt_part_num_yigao"
           style="width: 30px;" autocomplete="off"
           value = "<?php echo $old_part_num_yigao;?>"/>

    <input type="submit" style="" name="submit_edit_partnumyigao" value="Submit"/>
    <input type="text" style="display:none;" name="barcode" value = "<?php echo $barcode; ?>" autocomplete="off"/>
<!--    <input type="text" style="display:none;" name="appli" value = "--><?php //echo $appli;?><!--" autocomplete="off"/>-->
<!--    <input type="text" style="display:none;" name="field" value = "--><?php //echo $field;?><!--" autocomplete="off  "/>-->
<!--    <input type="text" style="display:none;" name="user" value = "--><?php //echo $user_of_cart;?><!--" autocomplete="off"/>-->
</form>



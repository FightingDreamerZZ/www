<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: ajax/suggestion.php
* This file offers search suggestions based on user's incomplete inputs.
*/
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('../lib/sql.php');//zz path forwardSlash tempForMac
include('../lib/user_lib.php');

check_user_cookie();

$table = $_POST['table'];

//search auto completion for 'name' column --name of the part
if(isset($_POST['keyword']) && isset($table)){
	$segments = explode(" ", $_POST['keyword']);
	//SELECT * FROM ... WHERE (`name` LIKE '%xx%' or `name` LIKE '%yy%' or ... ) LIMIT 0,10;
    $sql_code = "SELECT * FROM `".$table."` WHERE (";
	foreach ($segments as $segment){
        $sql_code .= "`name` LIKE '%{$segment}%' and ";
    }
    $sql_code = substr($sql_code, 0, -5);
    $sql_code .= ") LIMIT 0,10";
//    echo $sql_code;
//	$sql_code = "SELECT * FROM `".$table."` WHERE `name` LIKE '%".$_POST['keyword']."%' LIMIT 0,10;";
	//echo $_POST['keyword'];
	$result = mysql_query($sql_code);
}

//search auto completion for 'part_num' column --factory id number of the part
if(isset($_POST['keyword']) && isset($table) && ($table) == 'ew_part'){

    $sql_code1 = "SELECT * FROM `ew_part` WHERE `part_num` LIKE '%".$_POST['keyword']."%' LIMIT 0,10;";
    //echo $_POST['keyword'];
    $result_pn = mysql_query($sql_code1);
}

//search auto correction in `part` table for `part_num` col
$result_ac_pn = array();
if(isset($_POST['keyword']) && isset($table) &&
        mysql_num_rows($result) == 0 && mysql_num_rows($result_pn) == 0) {
    $result_temp = mysql_query("SELECT * FROM `ew_part`;");
    while ($row_temp = mysql_fetch_assoc($result_temp)){
        if(check_string_similarity($_POST['keyword'], $row_temp["part_num"], 1)){
            array_push($result_ac_pn,$row_temp);
        }
    }
}

//zz if there is a special need (posted "special" not null), adjust triggered event (change href to send para be catched by their listener)
//$_POST['special'] might be: create_part_ordering_sheet (for page create_part_ordering_sheet.php), depart (for page depart.php), enter (for page enter.php)
$special = null;
if(isset($_POST['special'])) {
    $special = $_POST['special'];
}
?>

<b>Suggestion:</b><br/>

Exact match<br/>

<?php 
while ($row_1 = mysql_fetch_assoc($result)) {
$url = str_replace("+","%2B",$row_1["name"]);
$url = str_replace(" ","+",$url);
?> 

    <?php switch($special){
        case "create_part_ordering_sheet":
            echo "<a href='../create_part_ordering_sheet.php?barcode=".$row_1["barcode"]."'>".$row_1["name"]."&nbsp;".$row_1["part_num"]."</a>";
            break;
        case "depart":
            break;
        case "enter":
            break;
        case null:
            //zz <a href="view_part.php?barcode=xxxxx">partName partNumber</a>
            $page_url = get_view($table)."?barcode=".$row_1["barcode"];
            $inner_html = $row_1["name"]."&nbsp;".$row_1["part_num"];
            echo "<a href='{$page_url}'>{$inner_html}</a>";
            break;
    }?>

<?php 
};
?>

<?php
while ($row_11 = mysql_fetch_assoc($result_pn)) {
$url = str_replace("+","%2B",$row_11["name"]);
$url = str_replace(" ","+",$url);
?>

    <?php switch($special) {
        case "create_part_ordering_sheet":
            echo "<a href='../create_part_ordering_sheet.php?barcode=" . $row_11["barcode"] . "'>" . $row_11["name"] . "&nbsp;" . $row_11["part_num"] . "</a>";
            break;
        case "depart":
            break;
        case "enter":
            break;
        case null:
            $page_url = "view_part.php?barcode=".$row_11["barcode"];
            $inner_html = $row_11["name"]."&nbsp;".$row_11["part_num"];
            echo "<a href='{$page_url}'>{$inner_html}</a>";
            break;
    }?>

<?php
};
?>

<br/>
Do you mean
<br/>

<?php
foreach ($result_ac_pn as $row_12) {
    $url = str_replace("+","%2B",$row_12["name"]);
    $url = str_replace(" ","+",$url);
    ?>

    <?php switch($special) {
        case "create_part_ordering_sheet":
            echo "<a href='../create_part_ordering_sheet.php?barcode=" . $row_12["barcode"] . "'>" . $row_12["name"] . "&nbsp;" . $row_12["part_num"] . "</a>";
            break;
        case "depart":
            break;
        case "enter":
            break;
        case null:
            //zz <a href="view_part.php?barcode=xxxxx">partName partNumber</a>
            $page_url = "view_part.php?barcode=".$row_12["barcode"];
            $inner_html = $row_12["name"]."&nbsp;".$row_12["part_num"];
            echo "<a href='{$page_url}'>{$inner_html}</a>";
            break;
    }?>



    <?php
};
?>

<br/>
<hr/>
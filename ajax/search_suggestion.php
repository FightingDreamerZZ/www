<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: ajax/suggestion.php
* This file offers search suggestions based on user's incomplete inputs.
*/
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('../lib/sql.php');//zz path forwardSlash tempForMac
include('../lib/user_lib.php');

check_user_cookie();

//search auto completion for 'name' column --name of the part
if(isset($_POST['keyword']) && isset($_POST['table'])){
	
	$sql_code = "SELECT * FROM `".$_POST['table']."` WHERE `name` LIKE '%".$_POST['keyword']."%' LIMIT 0,10;";
	//echo $_POST['keyword'];
	$result = mysql_query($sql_code);
}

//search auto completion for 'part_num' column --factory id number of the part
if(isset($_POST['keyword']) && isset($_POST['table']) && ($_POST['table']) == 'ew_part'){

    $sql_code1 = "SELECT * FROM `ew_part` WHERE `part_num` LIKE '%".$_POST['keyword']."%' LIMIT 0,10;";
    //echo $_POST['keyword'];
    $result_pn = mysql_query($sql_code1);
}

//search auto correction in `part` table for `part_num` col
$result_ac_pn = array();
if(isset($_POST['keyword']) && isset($_POST['table']) &&
        mysql_num_rows($result) == 0 && mysql_num_rows($result_pn) == 0) {
    $result_temp = mysql_query("SELECT * FROM `ew_part`;");
    while ($row_temp = mysql_fetch_assoc($result_temp)){
        if(check_string_similarity($_POST['keyword'], $row_temp["part_num"], 1)){
            array_push($result_ac_pn,$row_temp);
        }
    }
}
?>

<b>Suggestion:</b><br/>

Exact match<br/>

<?php 
while ($row_1 = mysql_fetch_assoc($result)) { 
$url = str_replace("+","%2B",$row_1["name"]);
$url = str_replace(" ","+",$url);
?> 

	<a href="search.php?keyword=<?php echo $url; ?>&table=<?php echo $_POST['table']; ?>">
        <?php echo $row_1["name"]."&nbsp;".$row_1["part_num"]; ?>
    </a>&nbsp;

<?php 
};
?>

<?php
while ($row_11 = mysql_fetch_assoc($result_pn)) {
$url = str_replace("+","%2B",$row_11["name"]);
$url = str_replace(" ","+",$url);
?>

	<a href="search.php?keyword=<?php echo $url; ?>&table=<?php echo $_POST['table']; ?>">
        <?php echo $row_11["name"]."&nbsp;".$row_11["part_num"]; ?>
    </a>&nbsp;

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

    <a href="search.php?keyword=<?php echo $url; ?>&table=<?php echo $_POST['table']; ?>">
        <?php echo $row_12["name"]."&nbsp;".$row_12["part_num"]; ?>
    </a>&nbsp;

    <?php
};
?>

<br/>
<hr/>
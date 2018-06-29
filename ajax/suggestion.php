<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: ajax/suggestion.php
* This file offers search suggestions based on user's incomplete inputs.
*/
error_reporting(E_ALL ^ E_NOTICE);
include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

if(isset($_POST['keyword']) && isset($_POST['table'])){
	
	$sql_code = "SELECT DISTINCT `name` FROM `".$_POST['table']."` WHERE `name` LIKE '%".$_POST['keyword']."%' LIMIT 0,10;";
	//echo $_POST['keyword'];
	$result = mysql_query($sql_code);
	}
	
?>


Suggestion: 

<?php 
while ($row_1 = mysql_fetch_assoc($result)) { 
$url = str_replace("+","%2B",$row_1["name"]);
$url = str_replace(" ","+",$url);
?> 

	<a href="search.php?keyword=<?php echo $url; ?>&table=<?php echo $_POST['table']; ?>"><?php echo $row_1["name"]; ?></a>
	


<?php 
}; 
?> 

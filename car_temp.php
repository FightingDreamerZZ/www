<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: car_temp.php
* This file displays all car templates.
*/

error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

include('header.php');
?>


<style>
.block{
	float:left;
	margin: 10px;
	border: 1px solid;
}
.block a{
	text-decoration: none;
}

</style>

<div id="main">

<div class="content_box_top"></div>
<div class="content_box">
<h2>Select Car Model</h2>

<?PHP
if ($handle = opendir('cartemp/')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." &&  $entry != "index.php" &&  $entry != "sample.php") {
			echo "<div class = \"block\">";
            echo "<a href = \"new_car.php?temp=$entry\">";
			echo $entry;
			echo "<br />";
			echo "<img src=\"upload/sample/";
			echo str_replace(".php",".jpg",$entry);
			echo "\" width=\"150px\" height=\"150px\"/>";
			echo "</a>";
			echo "</div>";
        }
    }
    closedir($handle);
}
?>


<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>


<style>

#cartemp ul { 
	margin: 0; 
	padding: 0;
}

#cartemp ul li { 
	display: block; 
	position: relative; 
	float: left; 
	height: 20px; 
	padding: 6px; 
	background: #ccc; 
	margin: 0 5px 5px 0; 
}
</style>
<div id="cartemp">
<ul>
<?PHP
if ($handle = opendir('cartemp/')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." &&  $entry != "index.php" &&  $entry != "sample.php") {	
            echo "<a href = \"new_car.php?temp=$entry\">";
			echo "<li>";
			echo str_replace(".php","",$entry);
			echo "</li>";
			echo "</a>";
			
			//echo "</div>";
        }
    }
    closedir($handle);
}
?>
</ul>
</div>


<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: search.php
* This file performs search related functions
*/

//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

echo "<a href='http://localhost/www/car_sys/public/vehicle/list?haha=hihihi'>hahaha</a>"

?>

<script type="text/javascript">
	function suggest(key)
	{
	var xmlhttp;
	// var table = document.getElementById("db_table").value;
	var postdata = "keyword="+encodeURIComponent(key);
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("suggestion").innerHTML=xmlhttp.responseText;
		}
	  }
	
	xmlhttp.open("POST","test_calc.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	// xmlhttp.setRequestHeader("Content-length", postdata.length);
	xmlhttp.send(postdata);
	
	}
	
</script>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">



<form name="form2" method="get" action="search.php" >
	Factory price:
    <input type="text" id="keyword" name="keyword" class="input_field" value="<?php echo $temp_key; ?>" autocomplete="off" onkeyup="suggest(this.value)"/>
	<input type="submit" class="submit_btn" value="Search"/>
</form>

<p id="suggestion"></p>


<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: search.php
* This file performs search related functions
*/

error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//Barcode Search handler
if ($_GET['do']=='barcode') { 
	$barcode = $_POST[keyword];
	$table = get_table($barcode);
	if($table=="ew_car"){
		die('<meta http-equiv="refresh" content="0;URL=view_car.php?barcode='.$barcode.'">');
	}else{
		die('<meta http-equiv="refresh" content="0;URL=view_part.php?barcode='.$barcode.'">');
	}
}
//======================


//Page Seperator
$split_by = '10';
if (isset($_GET["page"])) { 
	$page = $_GET["page"]; 
} else { 
	$page=1; 
}
$start_from = ($page-1) * $split_by;

//================



if (isset($_GET["keyword"])) { 
	$temp_key = $_GET[keyword]; 
} else { 
	$temp_key = '';
}
if (isset($_GET["table"])) { 
	$table = $_GET[table]; 
} else { 
	$table = 'ew_part';
}
	
$highlight = str_replace(","," ",$temp_key);
$keyword = explode(',', $temp_key);
$sqltag = '';
foreach ($keyword as &$value) {
	if($sqltag ==''){
		$sqltag = "`xsearch` LIKE '%$value%' "; //zz xsearch应该是专为searchSuggestion功能弄的一个column，有全部各个域的value串成一长string，也被用于display part details
	}else{
		$sqltag = $sqltag."AND `xsearch` LIKE '%$value%' ";
	}
}

if($table == "ew_part"){
	$sql_code_1 = "SELECT * FROM `".$table."` WHERE (`w_quantity` != '-1') AND (".$sqltag.") ORDER BY `barcode` DESC LIMIT ".$start_from.",".$split_by;
	$sql_code_2 = "SELECT COUNT(barcode) FROM `".$table."` WHERE (`w_quantity` != '-1') AND (".$sqltag.")"; 
}else{
	$sql_code_1 = "SELECT * FROM `".$table."` WHERE (`quantity` > '0') AND (".$sqltag.") ORDER BY `barcode` DESC LIMIT ".$start_from.",".$split_by;
	$sql_code_2 = "SELECT COUNT(barcode) FROM `".$table."` WHERE (`quantity` > '0') AND (".$sqltag.")"; 
}
$result_info_1 = mysql_query($sql_code_1);


$result_info_2 = mysql_query($sql_code_2);
$row_2 = mysql_fetch_row($result_info_2); 
$total_records = $row_2[0]; 
$total_pages = ceil($total_records / $split_by);

$title_by_page = "Search";
include('header.php');
?>

<script type="text/javascript">
	function suggest(key)
	{
	var xmlhttp;
	var table = document.getElementById("db_table").value;
	var postdata = "keyword="+encodeURIComponent(key)+"&table="+table;
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
	
	xmlhttp.open("POST","ajax/suggestion.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", postdata.length);
	xmlhttp.send(postdata);
	
	}
	
</script>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">



<form name="form2" method="get" action="search.php" >
	Smart Search:<select name="table" id="db_table" class="select_field">

	<option value="ew_part" <?php if($table == 'ew_part'){ echo("selected=\"selected\"");} ?>>Part</option>
    <option value="ew_car" <?php if($table == 'ew_car'){ echo("selected=\"selected\"");} ?>>Car</option>
	</select><input type="text" id="keyword" name="keyword" class="input_field" value="<?php echo $temp_key; ?>" autocomplete="off" onkeyup="suggest(this.value)"/>
	<input type="submit" class="submit_btn" value="Search"/>
	</form>
	<p id="suggestion"></p>

<div id = "search_result">
<ul>
<p><?php echo($total_records); ?> result(s) was found in this query.</p>

<?php 
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
?>
<!--    zz xsearch应该是专为searchSuggestion功能弄的一个column，有全部各个域的value串成一长string，也被用于display part details-->
	<li><a href="<?php echo get_view($table); ?>?barcode=<?php echo $row_1["barcode"]; ?>"><?php echo $row_1["barcode"]; ?> <?php echo $row_1["name"]; ?></a> [Stock: <?php echo $row_1["quantity"]; ?>]
	<br /><?php echo highlight($row_1["xsearch"],$highlight); if($row_1["des"]!=""){echo "<br />Description: ";echo $row_1["des"];}?>
	</li>
	


<?php 
}; 
?> 
</ul>
</div>
<div class="cleaner"></div>
<p>Page:
<?php 
for ($i=1; $i<=$total_pages; $i++) { 
            echo "<a href='search.php?".trim_url("&page=")."&page=".$i.$urltag."'>".$i."</a> "; 
}; 
?>
</p>


<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>

<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: search.php
* This file performs search related functions
*/

error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();


//Page Seperator
$split_by = '9';
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
	$table = 'ec_customer';
}
	
$highlight = str_replace(","," ",$temp_key);
$keyword = explode(',', $temp_key);
$sqltag = '';
foreach ($keyword as &$value) {
	if($sqltag ==''){
		$sqltag = "`xsearch` LIKE '%$value%' ";
	}else{
		$sqltag = $sqltag."AND `xsearch` LIKE '%$value%' ";
	}
}

if($table == "ec_customer"){
	$sql_code_1 = "SELECT * FROM `".$table."` WHERE ".$sqltag." ORDER BY `cid` DESC LIMIT ".$start_from.",".$split_by;
	$sql_code_2 = "SELECT COUNT(cid) FROM `".$table."` WHERE ".$sqltag; 
}else{
	$sql_code_1 = "SELECT * FROM `".$table."` WHERE ".$sqltag." ORDER BY `cid` DESC LIMIT ".$start_from.",".$split_by;
	$sql_code_2 = "SELECT COUNT(cid) FROM `".$table."` WHERE ".$sqltag; 
}
$result_info_1 = mysql_query($sql_code_1);


$result_info_2 = mysql_query($sql_code_2);
$row_2 = mysql_fetch_row($result_info_2); 
$total_records = $row_2[0]; 
$total_pages = ceil($total_records / $split_by); 

include('header.php');
?>

<div id="main"><span class="mf mft"></span><span class="mf mfb"></span>
<div class="col col_1">
<form name="form2" method="get" action="search.php" >
	Smart Search: <select name="table" id="db_table" class="select_field w90">
	<option value="ec_customer" <?php if($table == 'ec_customer'){ echo("selected=\"selected\"");} ?>>Customer</option>
	<option value="ec_commlog" <?php if($table == 'ec_commlog'){ echo("selected=\"selected\"");} ?>>*CommLog</option>
	</select> <input type="text" id="keyword" name="keyword" class="input_field" value="<?php echo $temp_key; ?>" autocomplete="off" onkeyup="suggest(this.value)"/>
	<input type="submit" class="submit_btn" value="Search"/>
	</form>        
	<p><?php echo($total_records); ?> result(s) was found in this query.</p>
	
<div id = "search_result">
<ul>
<?php 
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
?> 
 <li><a href="view.php?cid=<?php echo $row_1["cid"]; ?>"><?php echo $row_1["name"]; ?> [Tel:<?php echo $row_1["phone"]; ?>]</a>
 <br /><?php echo highlight($row_1["xsearch"],$highlight); if($row_1["des"]!=""){echo "<br />Description: ";echo $row_1["des"];}?>
 </li>
<?php 
}; 
?> 
</ul>
</div>
 <div class="paging">
<ul>
<div class="clear"></div>
<li><span>Page:</span></li>
<?php 

for ($i=1; $i<=$total_pages; $i++) { 
            echo "<li><a href='search.php?".trim_url("&page=")."&page=".$i.$urltag."'>".$i."</a></li> "; 
}; 
?>
</ul>
<div class="clear"></div>
</div>
 
</div> 
<div class="clear"></div>
</div> <!-- END of main -->

<?PHP
include('footer.php');
?>

<?PHP
/*
* Copyright © 2013 Elaine CRM
* File: index.php
* This file display a user panel to access all frequently used functions.
*/
error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');
check_user_cookie();

$sqltag="";
$urltag="";
$default_sort=" ORDER BY `interact` DESC ";

//sort list based on inputs
if($_GET['sort']=='name'){
	$sort = " ORDER BY `name` ASC ";
	$urltag= $urltag."&sort=".$_GET['sort'];
}else if($_GET['sort']=='status'){
	$sort = " ORDER BY `status` ASC ";
	$urltag= $urltag."&sort=".$_GET['sort'];
}else if($_GET['sort']=='type'){
	$sort = " ORDER BY `type` ASC ";
	$urltag= $urltag."&sort=".$_GET['sort'];
}else{
	$sort = $default_sort;
}


//load lists with page spliter
$split_by = '20';

if (isset($_GET["page"])) { 
	$page = $_GET["page"]; 
} else { 
	$page=1; 
}
$start_from = ($page-1) * $split_by;
$sql_code_1 = "SELECT * FROM `ec_customer` ".$sqltag.$sort."LIMIT ".$start_from.",".$split_by;
$result_info_1 = mysql_query($sql_code_1);

$sql_code_2 = "SELECT COUNT(cid) FROM `ec_customer` ".$sqltag.";"; 
$result_info_2 = mysql_query($sql_code_2);
$row_2 = mysql_fetch_row($result_info_2); 
$total_records = $row_2[0]; 
$total_pages = ceil($total_records / $split_by); 



//$load = " onload=\"load()\"";
include('header.php');

?>
<script type="text/javascript">
</script>

    
<div id="main"><span class="mf mft"></span><span class="mf mfb"></span>
<div class="col col_1">

<p><?php echo($total_records); ?> result(s) was found in this query. Sort by <a href ="customer.php?<?php echo trim_url("&sort="); ?>">[Interact]</a> <a href="customer.php?<?php echo trim_url("&sort="); ?>&sort=name">[Name]</a> <a href="customer.php?<?php echo trim_url("&sort="); ?>&sort=status">[Status]</a>  <a href="customer.php?<?php echo trim_url("&sort="); ?>&sort=type">[Type]</a></p>


<div class="paging">
<ul>
<li><span>Page:</span></li>
<?php 

for ($i=1; $i<=$total_pages; $i++) { 
            echo "<li><a href='customer.php?page=".$i.$urltag."'>".$i."</a></li> "; 
}; 
?>
</ul>
<div class="clear"></div>
</div>

<table>
<tr>

<td>CID</td>
<td>Name</td>
<td>Status</td>
<td>Type</td>
<td>Contact</td>
<td>Phone</td>
<td>Address</td>
<td>Action</td>
</tr>
<?php 
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
?> 
<tr>

<td><?php echo $row_1["cid"]; ?></td>
<td><a href="view.php?cid=<?php echo $row_1["cid"]; ?>"><?php echo $row_1["name"]; ?></a></td>
<td><?php echo $row_1["status"]; ?></td>
<td><?php echo $row_1["type"]; ?></td>
<td><?php echo $row_1["contact"]; ?></td>
<td><?php echo $row_1["phone"]; ?></td>
<td><?php echo $row_1["address1"].$row_1["address2"]; ?></td>
<td><a href="edit_customer.php?cid=<?php echo $row_1["cid"]; ?>">Edit</a></td>

</tr>
<?php 
}; 
?> 
</table>


</div>
        
<div class="clear"></div>
</div> <!-- END of main -->
    
 
    

<?PHP
include('footer.php');
?>
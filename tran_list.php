<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: tran_list.php
* This file displays transaction list based on user input conditions
*/

//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

$today = date("Y-m-d");
$this_month = date("Y-m-01"); //zz 本月第一天的dateObj下同
$the_day_30days_before_today = date("Y-m-d", strtotime("-30 days"));
$this_year = date("Y-01-01");

$sqltag="";
$urltag="";
$default_sort=" ORDER BY `time` DESC ";

//page spliter

if (isset($_GET["supersplit"])) { 
	$split_by = $_GET["supersplit"];
}else{
	$split_by = '20';
}

//transaction period //zz filter时间区间 --其实是开放了接口的 --其实也实现了功能在stats页
if (isset($_GET["start"]) && isset($_GET["end"])) { 
	$sqltag="WHERE `time` BETWEEN '".$_GET["start"]." 00:00:00' AND '".$_GET["end"]." 23:59:59'";
	$urltag="&start=".$_GET["start"]."&end=".$_GET["end"];	
}

//item type  //zz filter'type'--是车还是零件
if (isset($_GET["type"]) && !$_GET["type"]=="") { 
	if($sqltag==""){
		$sqltag="WHERE `type` = '".$_GET["type"]."'";
		$urltag="&type=".$_GET["type"];
	}else{
	$sqltag= $sqltag."AND `type` = '".$_GET["type"]."'";
	$urltag= $urltag."&type=".$_GET["type"];	
	}
}

//transaction type  //zz filter'tran_type' --是入库还是取件（其实是有考虑的而且也实现了）
if (isset($_GET["tran_type"]) && !$_GET["tran_type"]=="") { 
	if($_GET["tran_type"] =="enter"){
		$operator = ">";
	}else{
		$operator = "<";
	}
	if($sqltag==""){
		$sqltag="WHERE `quantity` ".$operator." '0'";
		$urltag="&tran_type=".$_GET["tran_type"];
	}else{
	$sqltag= $sqltag."AND `quantity` ".$operator." '0'";
	$urltag= $urltag."&tran_type=".$_GET["tran_type"];	
	}
}

//sort list based on inputs  //zz sort
if($_GET['sort']=='name'){
	$sort = " ORDER BY `name` ASC ";
	$urltag= $urltag."&sort=".$_GET['sort'];
}else if($_GET['sort']=='user'){
	$sort = " ORDER BY `user` ASC ";
	$urltag= $urltag."&sort=".$_GET['sort'];
}else{
	$sort = $default_sort;
}

//load transactions
if (isset($_GET["page"])) { 
	$page = $_GET["page"]; 
} else { 
	$page=1; 
}

$start_from = ($page-1) * $split_by;
$sql_code_1 = "SELECT * FROM `transaction_view_w_appli`".$sqltag.$sort." LIMIT ".$start_from.",".$split_by.";"; //zz LIMIT 就是SQL语句自带的分页效果。。从0开始也ok的，那样一开始就是第一个。。

$result_info_1 = mysql_query($sql_code_1);

$sql_code_2 = "SELECT COUNT(tid) FROM `transaction_view`".$sqltag.";"; //zz $sqltag=基本就是各种filter例如type是零件还是车、transT是入库还是出库；
//$urltag=url上的对应的filter例如各种query，$sort排序专用的sql的filter、其他像$start_from $split_by就是和分页有关的部分了

$result_info_2 = mysql_query($sql_code_2);
$row_2 = mysql_fetch_row($result_info_2); 
$total_records = $row_2[0]; 
$total_pages = ceil($total_records / $split_by);


$loader1 = "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"css/jsDatePick_ltr.min.css\" />";//zz 还预留了子页可能需要的css或js文件的ref
$loader2 = "<script type=\"text/javascript\" src=\"ajax/jsDatePick.min.1.3.js\"></script>";
include('header.php');
?>

    <script type="text/javascript">
        window.onload = function(){
            Object1 = new JsDatePick({
                useMode:2,
                target:"inputField",
                dateFormat:"%Y-%m-%d"
            });
            Object2 = new JsDatePick({
                useMode:2,
                target:"inputField2",
                dateFormat:"%Y-%m-%d"
            });

            Object3 = new JsDatePick({
                useMode:2,
                target:"inputField3",
                dateFormat:"%Y-%m-%d"
            });
            Object4 = new JsDatePick({
                useMode:2,
                target:"inputField4",
                dateFormat:"%Y-%m-%d"
            });
        };

    </script>

<div id="main">
	 
<div class="content_box_top"></div>
<div class="content_box">
<h2>Transaction List</h2>

    <div class="cleaner"></div>
    <h5>Filters:</h5>
    <p>
    <form name="form_filter" method="get" action="tran_list.php" autocomplete="off">
        Start:<input type="text" name="start" class="input_field_w w70" id="inputField" value="<?php echo $this_month; ?>"/>
        End:<input type="text" name="end" class="input_field_w w70" id="inputField2" value="<?php echo $today; ?>"/>
        Cars or Parts:<select name="type" class="select_field">
            <option value="">All</option>
            <option value="car">Car</option>
            <option value="part">Part</option>
        </select>
        Arrive or Depart:<select name="tran_type" class="select_field">
            <option value="">All</option>
            <option value="enter">Arrive</option>
            <option value="depart">Depart</option>
        </select>
        <input type="submit" class="submit_btn" value="Search"/><br/>
        Display:&nbsp;
        <a href="tran_list.php?start=<?php echo $the_day_30days_before_today;?>&end=<?php echo $today;?>">Last 30 days</a>&nbsp;
        <a href="tran_list.php?start=<?php echo $this_year;?>&end=<?php echo $today;?>">This year till today</a>
    </form>
    </p>


    <div class="cleaner h20"></div>

<p><?php echo($total_records); ?> transaction(s) was found in the system. Sort by <a href ="tran_list.php?<?php echo trim_url("&sort="); ?>">[Time]</a> <a href="tran_list.php?<?php echo trim_url("&sort="); ?>&sort=name">[Name]</a> <a href="tran_list.php?<?php echo trim_url("&sort="); ?>&sort=user">[User]</a></p>
<p>Page:
<?php 


for ($i=1; $i<=$total_pages; $i++) { 
            echo "<a href='tran_list.php?page=".$i.$urltag."'>".$i."</a> "; //zz 串上的这个$urltag可以有效地保证在翻页时仍保留原本的url参数（get query）
}; 
?>
</p>

<table>
<tr>

<td>User</td>
<td>Barcode</td>
<td>Name</td>
<td>Type</td>
<td>Amount</td>
<td>Time</td>
<td>Application</td>
</tr>
<?php 
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
?> 
            <tr>
            
            <td><?php echo $row_1["user"]; ?></td>
            <td>
                <a href="view_part.php?barcode=<?php echo $row_1["barcode"]; ?>">
                    <?php echo $row_1["barcode"]; ?>
                </a>
            </td>
			<td><?php echo get_name($row_1["barcode"]); ?></td>
			<td><?php echo $row_1["type"]; ?></td>
			<td><?php echo $row_1["quantity"]; ?></td>
            <td><?php echo $row_1["time"]; ?></td>
            <td><?php echo $row_1["application"]; ?></td>
            </tr>
<?php 
}; 
?> 
</table>


<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>
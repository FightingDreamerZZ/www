<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: stats.php
* This file displays stats information
*/
error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//zz 把给定sql语句的查询结果的第一行第一列、完全无法查询就返回“ERROR”、查询结果为null则返回0  （其实就是查询各种COUNT）
function stats($sql_staus){
	if($result_info_s = mysql_query($sql_staus)){
		$row_s = mysql_fetch_row($result_info_s); 
	}else{
		return "ERROR";
	}
	if($row_s[0]==""){
		return '0';
	}else{
		return $row_s[0]; 
	}
}

//find quarter start date
function getquarter($today){
	if($today < date("Y-04-01")){  //zz date("Y-04-01")可以返回本年四月一号的dateObj，这里的str是format、锁定了0401、时间没给默认就是今天
		return date("Y-01-01");  //学起来
	}else if($today < date("Y-07-01")){
		return date("Y-04-01");
	}else if($today < date("Y-10-01")){
		return date("Y-07-01");
	}else{
		return date("Y-10-01");
	}
}

//load stats information //zz 程序写死了各种查询--其实也就是确定了所有想要的统计数据
$stats[total] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0');");
$stats[finish] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`category` = 'finish' );");
$stats[semi] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`category` = 'semi' );");
$stats[new_car] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`condition` = 'new' );");
$stats[used] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`condition` = 'used' );");
$stats[demo] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`condition` = 'demo' );");
$stats[damage] = stats("SELECT COUNT(barcode) FROM `ew_car` WHERE (`quantity` > '0') AND (`condition` = 'damaged' );");

$stats[total_parts] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`w_quantity` != '-1');");
$stats[total_parts_amount] = stats("SELECT SUM(quantity) FROM `ew_part` WHERE (`w_quantity` != '-1');");
$stats[total_short] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE `ew_part`.w_quantity > `ew_part`.quantity;");
$stats[total_out] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`quantity` = '0') AND (`w_quantity` != '-1');");
$stats[total_in] = stats("SELECT COUNT(barcode) FROM `ew_part` WHERE (`quantity` > '0') AND (`w_quantity` != '-1');");

$system_day = date("2013-11-01");
$today = date("Y-m-d");
$this_month = date("Y-m-01"); //zz 本月第一天的dateObj下同
$this_quarter = getquarter($today);
$this_year = date("Y-01-01");

$total_trans[month] = stats("SELECT COUNT(tid) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_month." 00:00:00' AND '".$today." 23:59:59';");
$total_trans[quarter] = stats("SELECT COUNT(tid) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_quarter." 00:00:00' AND '".$today." 23:59:59';");
$total_trans[year] = stats("SELECT COUNT(tid) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_year." 00:00:00' AND '".$today." 23:59:59';");
$total_trans[all] = stats("SELECT COUNT(tid) FROM `ew_transaction`WHERE `time` BETWEEN '".$system_day." 00:00:00' AND '".$today." 23:59:59';");

$car_enter[month] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_month." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'car' AND `quantity` > '0';");
$car_depart[month] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_month." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'car' AND `quantity` < '0';");
$part_enter[month] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_month." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'part' AND `quantity` > '0';");
$part_depart[month] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_month." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'part' AND `quantity` < '0';");

$car_enter[quarter] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_quarter." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'car' AND `quantity` > '0';");
$car_depart[quarter] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_quarter." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'car' AND `quantity` < '0';");
$part_enter[quarter] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_quarter." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'part' AND `quantity` > '0';");
$part_depart[quarter] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_quarter." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'part' AND `quantity` < '0';");

$car_enter[year] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_year." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'car' AND `quantity` > '0';");
$car_depart[year] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_year." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'car' AND `quantity` < '0';");
$part_enter[year] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_year." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'part' AND `quantity` > '0';");
$part_depart[year] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$this_year." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'part' AND `quantity` < '0';");
$car_enter[all] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$system_day." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'car' AND `quantity` > '0';");
$car_depart[all] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$system_day." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'car' AND `quantity` < '0';");
$part_enter[all] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$system_day." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'part' AND `quantity` > '0';");
$part_depart[all] = stats("SELECT SUM(quantity) FROM `ew_transaction`WHERE `time` BETWEEN '".$system_day." 00:00:00' AND '".$today." 23:59:59' AND `type` = 'part' AND `quantity` < '0';");

//zz
//handler for parts consumption stats:
if($_POST['start']){
    $flag_has_post_start_date = $_POST['start'];
    $sqltag="";
    $urltag="";
    $sqltag="WHERE `time` BETWEEN '".$_POST["start"]." 00:00:00' AND '".$_POST["end"]." 23:59:59'";
    $urltag="&start=".$_GET["start"]."&end=".$_GET["end"];
    $sqltag_append_for_type = "AND `type` = 'part' AND `quantity` < '0'";
    $sqltag .= $sqltag_append_for_type;

    $sql_code_11 = "SELECT * FROM `transaction_view`".$sqltag.";";
    $result_info_11 = mysql_query($sql_code_11);

    $array_result_set_after_combine = combine_same_barcode($result_info_11);
    $array_result_set_after_sort =
        sort_by_two_fields($array_result_set_after_combine,"quantity", true,"name",true);
}

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

<h2>Warehouse Status Monitor</h2>

<div class="cleaner"></div>



<div class="post_box float_l">
<h4>Car Inventory Status</h4>
		<ul class = "list">
				<li>Total Inventory: <?php echo $stats[total]; ?> <a href="search.php?table=ew_car">[CHECK]</a></li>
				<h6>By Category:</h6>
				<li>Finished: <?php echo $stats[finish]; ?> <a href="search.php?table=ew_car&keyword=finish">[CHECK]</a></li>
				<li>Semifinished: <?php echo $stats[semi]; ?> <a href="search.php?table=ew_car&keyword=semi">[CHECK]</a></li>
				<h6>By Condition:</h6>
				<li>New Cars: <?php echo $stats[new_car]; ?> <a href="search.php?table=ew_car&keyword=new">[CHECK]</a></li>	
				<li>Used Cars: <?php echo $stats[used]; ?> <a href="search.php?table=ew_car&keyword=used">[CHECK]</a></li>	
				<li>Demo Cars: <?php echo $stats[demo]; ?> <a href="search.php?table=ew_car&keyword=demo">[CHECK]</a></li>	
				<li>Damage Cars: <?php echo $stats[damage]; ?> <a href="search.php?table=ew_car&keyword=damaged">[CHECK]</a></li>	
				
		</ul>
<div class="cleaner"></div>
</div>

<div class="post_box float_l">
<h4>Parts Inventory Status</h4>
		<ul class = "list">
				<li>Total Types of Parts: <?php echo $stats[total_parts]; ?> <a href="list.php?table=ew_part">[CHECK]</a></li>
				<li>Total Amount of Parts: <?php echo $stats[total_parts_amount]; ?> <a href="list.php?table=ew_part">[CHECK]</a></li>
				<h6>By Inventory:</h6>
				<li>In Stock Parts: <?php echo $stats[total_in]; ?> <a href="list.php?check=inventory&table=ew_part">[CHECK]</a></li>
				<li>Out of Stock Parts: <?php echo $stats[total_out]; ?> <a href="list.php?check=out&table=ew_part">[CHECK]</a></li>
				<h6>Short Supply Warning:</h6>
				<li>Short Supply Parts: <?php echo $stats[total_short]; ?> <a href="list.php?check=short&table=ew_part">[CHECK]</a></li>
		
		</ul>
<div class="cleaner"></div>
</div>

<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>

<div class="content_box_top"></div>
<div class="content_box">

<h2>Warehouse Transaction Trace</h2>

<div class="cleaner"></div>
<p>
<form name="form2" method="get" action="tran_list.php" autocomplete="off">
	Start:<input type="text" name="start" class="input_field_w w70" id="inputField" value="<?php echo $this_month; ?>"/>
	End:<input type="text" name="end" class="input_field_w w70" id="inputField2" value="<?php echo $today; ?>"/>
	Type:<select name="type" class="select_field">
	<option value="">All</option>
	<option value="car">Car</option>
	<option value="part">Part</option>
	</select>
	Transaction:<select name="tran_type" class="select_field">
	<option value="">All</option>
	<option value="enter">Arrive</option>
	<option value="depart">Depart</option>
	</select>
	<input type="submit" class="submit_btn" value="Trace"/>
	</form>
</p>


<div class="cleaner h30"></div>


<div class="post_box float_l">
<h4>Month Summary</h4>
		<ul class = "list">
				<li>Month Started: <?php echo $this_month; ?></li>
				<li>Total Trasactions:<?php echo $total_trans[month]; ?></li>
				<h6>Car Trasactions:</h6>
				<li>Enter Amount:<?php echo $car_enter[month]; ?></li>
				<li>Depart Amount:<?php echo $car_depart[month]; ?></li>
				<h6>Part Trasactions:</h6>
				<li>Enter Amount:<?php echo $part_enter[month]; ?></li>
				<li>Depart Amount:<?php echo $part_depart[month]; ?></li>
		</ul>
<div class="cleaner"></div>
</div>

<div class="post_box float_l">
<h4>Quarter Summary</h4>
		<ul class = "list">
				<li>Quarter Started: <?php echo $this_quarter; ?></li>
				<li>Total Trasactions: <?php echo $total_trans[quarter]; ?></li></li>
				<h6>Car Trasactions:</h6>
				<li>Enter Amount:<?php echo $car_enter[quarter]; ?></li>
				<li>Depart Amount:<?php echo $car_depart[quarter]; ?></li>
				<h6>Part Trasactions:</h6>
				<li>Enter Amount:<?php echo $part_enter[quarter]; ?></li>
				<li>Depart Amount:<?php echo $part_depart[quarter]; ?></li>
		</ul>
<div class="cleaner"></div>
</div>

<div class="post_box float_l">
<h4>Year Summary</h4>
		<ul class = "list">
				<li>Year Started: <?php echo $this_year; ?></li>
				<li>Total Trasactions: <?php echo $total_trans[year]; ?></li></li>
				<h6>Car Trasactions:</h6>
				<li>Enter Amount:<?php echo $car_enter[year]; ?></li>
				<li>Depart Amount:<?php echo $car_depart[year]; ?></li>
				<h6>Part Trasactions:</h6>
				<li>Enter Amount:<?php echo $part_enter[year]; ?></li>
				<li>Depart Amount:<?php echo $part_depart[year]; ?></li>
		</ul>
<div class="cleaner"></div>
</div>


<div class="post_box float_l">
<h4>Total Summary</h4>
		<ul class = "list">
				<li>System Started: <?php echo $system_day; ?></li>
				<li>Total Trasactions: <?php echo $total_trans[all]; ?></li></li>
				<h6>Car Trasactions:</h6>
				<li>Enter Amount:<?php echo $car_enter[all]; ?></li>
				<li>Depart Amount:<?php echo $car_depart[all]; ?></li>
				<h6>Part Trasactions:</h6>
				<li>Enter Amount:<?php echo $part_enter[all]; ?></li>
				<li>Depart Amount:<?php echo $part_depart[all]; ?></li>
		</ul>
<div class="cleaner"></div>
</div>


<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>

    <div class="content_box_top"></div>
    <div class="content_box">

        <h2>Advanced Stats</h2>

        <div class="cleaner"></div>
        <h4>Parts Consumption Stats -- Most Consumed Parts</h4>
        <b>Set time span:</b>
        <form name="form3" method="post" action="stats.php" autocomplete="off">
            Start:<input type="text" name="start" class="input_field_w w70" id="inputField3" value="<?php echo $this_month; ?>"/>
            End:<input type="text" name="end" class="input_field_w w70" id="inputField4" value="<?php echo $today; ?>"/>
<!--            Type:<select name="type" class="select_field">-->
<!--                <option value="">All</option>-->
<!--                <option value="car">Car</option>-->
<!--                <option value="part">Part</option>-->
<!--            </select>-->
<!--            Transaction:<select name="tran_type" class="select_field">-->
<!--                <option value="">All</option>-->
<!--                <option value="enter">Arrive</option>-->
<!--                <option value="depart">Depart</option>-->
<!--            </select>-->
            <input type="submit" class="submit_btn" value="Get Stats"/>
        </form>


        <!--    zz-->
        <table <?php if(!$array_result_set_after_sort){echo "style=\"display:none;\"";}?>>
            <tr>


                <td>Barcode</td>
                <td>Name</td>

                <td>Total Consumption</td>

            </tr>
            <?php if($array_result_set_after_sort)
            foreach ($array_result_set_after_sort as $item) {
                ?>
                <tr>


                    <td>
                        <a href="view_part.php
                            ?barcode=<?php echo $item["barcode"]; ?>">
                            <?php echo $item["barcode"]; ?>
                        </a>
                    </td>
                    <td><?php echo $item["name"]; ?></td>

                    <td><?php echo $item["quantity"]; ?></td>

                </tr>
                <?php
            };
            ?>
        </table>
        <!--    zz-->

        <div class="cleaner h30"></div>
        <div class="cleaner"></div>
        <div class="cleaner"></div>
    </div> <!-- end of a content box -->
    <div class="content_box_bottom"></div>
	 
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>
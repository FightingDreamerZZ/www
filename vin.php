<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: vin.php
* This file performs vin # trace function
*/
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//load profile and history records if input vin
if (isset($_GET['vin'])) { 
	$vin = $_GET['vin'];
	if(check_data('ew_car','vin',$vin)){
		$sql_code = "select * from ew_car where `vin` = '".$vin."';";
		$result_info = mysql_query($sql_code);
		$a_check = mysql_fetch_array($result_info);
	}else{
		stop("VIN number not found!");
	}
	$sql_code_1 = "SELECT * FROM `ew_transaction` WHERE `barcode` = '".$a_check['barcode']."' ORDER BY `tid` ASC;";
	$result_info_1 = mysql_query($sql_code_1);

	
}


include('header.php');

?>


<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">


           <h2>VIN Number Trace</h2>
            <div class="cleaner"></div>
			
            <p><form name="form1" method="get" action="vin.php">
	VIN Number: <input type="text" name="vin" class="input_field" autocomplete="off"/>
	<input type="submit" class="submit_btn" value="Trace"/>
	</form></p>
            <div class="cleaner h30"></div>
            <div class="col_w320 float_r">
			<h4>CAR Profile</h4> 

               <ul class = "list">
					<li>Name: <a href="search.php?keyword=<?php echo(str_replace("+","%2B",$a_check['name'])); ?>"><?php echo($a_check['name']); ?></a></li>
					<li>Barcode: <?php echo($a_check['barcode']); ?></li>
					<li>Model: <a href="search.php?keyword=<?php echo($a_check['model']); ?>"><?php echo($a_check['model']); ?></a></li>
					<li>Category: <a href="search.php?keyword=<?php echo($a_check['category']); ?>"><?php echo($a_check['category']); ?></a></li>
					<li>Color: <a href="search.php?keyword=<?php echo($a_check['color']); ?>"><?php echo($a_check['color']); ?></a></li>
					<li>Condition: <a href="search.php?keyword=<?php echo($a_check['condition']); ?>"><?php echo($a_check['condition']); ?></a></li>
					<li>Year: <a href="search.php?keyword=<?php echo($a_check['year']); ?>"><?php echo($a_check['year']); ?></a></li>
					<li>VIN Number: <?php echo($a_check['vin']); ?></li>
					<li>Purchase Price: <?php echo($a_check['p_price']); ?></li>
					<li>Wholesale Price: <?php echo($a_check['w_price']); ?></li>
					<li>Retail Price: <?php echo($a_check['r_price']); ?></li>
					<li>Quantity: <?php echo($a_check['quantity']); ?></li>
					<li>Stock Warning: <?php echo($a_check['w_quantity']); ?></li>
					<li>Location: <a href="search.php?keyword=<?php echo($a_check['l_zone']."_".$a_check['l_column']."_".$a_check['l_level']); ?>"><?php echo($a_check['l_zone']."_".$a_check['l_column']."_".$a_check['l_level']); ?></a></li>
					<li>Latest Update: <?php echo($a_check['date']); ?></li>
					<li>Description: <?php echo($a_check['des']); ?></li>
					
				</ul>

            </div>
            
            <div class="col_w320 float_l">
                <h4>Photo Preview</h4>              
                
                <a href="<?php echo($a_check['photo_url']); ?>" target="_blank"><img width="300" height="300" class ="withborder" src="<?php echo($a_check['photo_url']); ?>" class="image_wrapper" /></a>
				 
				<div class="cleaner h10"></div>
				
				 <h4>History Records</h4>
				 <ol>
				 <?php 
					if($result_info_1 != null){
					while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
					if($row_1["quantity"] > 0){
						$str = "Enter Warehouse: ";
					}else{
						$str = "Depart Warehouse: ";
					}
					?> 
					<li><?php echo $str.$row_1["time"]." by ".$row_1["user"]; ?></li>
					<?php 
					}; 
					}
					?> 
				 </ol>
				
                
                
            </div>    	




<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>
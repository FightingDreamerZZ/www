<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: view_car.php
* This file displays car profile based on input barcode.
*/

error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//load profile if barcode given
if (isset($_GET['barcode'])) { 
	$barcode = $_GET['barcode'];
	if(check_data('ew_car','barcode',$barcode)){
		$sql_code = "select * from ew_car where barcode = '".$barcode."';";
		$result_info = mysql_query($sql_code);
		$a_check = mysql_fetch_array($result_info);
	}else{
		stop("Barcode not found!");
	}
	
	
}


$load = " onload=\"load()\"";
include('header.php');

?>

<script type="text/javascript">
   function load()
   {
      document.form1.keyword.focus();
   }
</script>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">


           <h2>View Cars</h2>
            <div class="cleaner"></div>
			
            <p><form name="form1" method="post" action="search.php?do=barcode">
	Barcode Search: <input type="text" name="keyword" autocomplete="off"/>
	<input type="submit" name="submit" value="Go"/>
	</form></p>
            <div class="cleaner h30"></div>
            <div class="col_w320 float_r">
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
                
                <a href="<?php echo($a_check['photo_url']); ?>" target="_blank"><img width="300" class ="withborder" src="<?php echo get_thumb($a_check['photo_url']); ?>" class="image_wrapper" /></a>
				 <p>
				<a href="edit_car.php?barcode=<?php echo($a_check['barcode']); ?>">[Edit Profile]</a>
				<a href="enter.php?barcode=<?php echo($a_check['barcode']); ?>">[Quick Enter]</a>
				<a href="depart.php?barcode=<?php echo($a_check['barcode']); ?>">[Quick Depart]</a>
				</p>
                
                
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
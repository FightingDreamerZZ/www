<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: cart.php
* This file handles clear() and proceed() request.
*/

//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

if ($_GET['get'] =='recent') { 
$sql_code_1 = "SELECT * FROM `ec_customer` ORDER BY `interact` DESC LIMIT 0,12 ;";
}
if ($_GET['get'] =='existing') { 
$sql_code_1 = "SELECT * FROM `ec_customer` WHERE `status`='existing' ORDER BY `interact` DESC LIMIT 0,12 ;";
}
if ($_GET['get'] =='potential') { 
$sql_code_1 = "SELECT * FROM `ec_customer` WHERE `status`='potential' ORDER BY `interact` DESC LIMIT 0,12 ;";
}


$result_info_1 = mysql_query($sql_code_1);



?>


<?php 
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
?> 
 <div class="col col_3">
            <h3><?php echo $row_1["name"]; ?></h3>
            <p><em><?php echo $row_1["address1"]; ?><br/><?php echo $row_1["address2"]; ?></em></p>
            <ul class="list_bullet">
                <li>Customer Type: <?php echo $row_1["type"]; ?></li>
                <li>Phone: <?php echo $row_1["phone"]; ?></li>
                <li>Fax: <?php echo $row_1["fax"]; ?></li>
                <li><a href="mailto:<?php echo $row_1["email"]; ?>">Email: <?php echo $row_1["email"]; ?></a></li>
                <li><a href="http://<?php echo $row_1["web"]; ?>" target="_blank">Web: <?php echo $row_1["web"]; ?></a></li> 				
            </ul>
            <a href="view.php?cid=<?php echo $row_1["cid"]; ?>" class="more">More</a>
		</div>

<?php 
}; 
?>
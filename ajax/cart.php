<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: cart.php
* This file handles clear() and proceed() request.
*/

//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('../lib/sql.php');//zz path forwardSlash tempForMac
include('../lib/user_lib.php');

check_user_cookie();

if(isset($_GET['user_of_cart'])){
    $user_of_cart = $_GET['user_of_cart'];
}
else {

    $user_of_cart = $_COOKIE['ew_user_name'];
}

if($_GET['do'] == 'clear'){ //zz get的参数‘do’作为flag标识了本次执行的行动
	clear_cart($user_of_cart);
}

//submit vs proceed: submit need to approve, proceed is final
if ($_GET['do'] == 'submit') {
    cart_submit($user_of_cart);
}

if($_GET['do'] == 'proceed'){
	$sql_get_cart = "SELECT * FROM `ew_cart` WHERE `user` = '".$user_of_cart."';";//zz proceed前check的逻辑是cart表中所有和当前用户相关的记录都会被使用（当做是全部的购物车--并没有购物车历史的记录，有的就是当前的）
	$result_cart = mysql_query($sql_get_cart);
	
	if ( mysql_num_rows($result_cart) == 0){
		exit("Empty Cart =_=, you have nothing to proceed!");
	}
	while ($cart_row = mysql_fetch_assoc($result_cart)) {
		$new_quantity = get_anything($cart_row[barcode],'quantity') + $cart_row[quantity];
		//zz 下面更新part主表的剩余数量、进行实质减法
		$update_sql = "UPDATE `".$cart_row[table]."` SET  `quantity` =  '".$new_quantity."' WHERE `barcode` =  '".$cart_row[barcode]."';";
		if (!($result=mysql_query($update_sql))) { 
			stop('DB Error!');
		}else{
			if($cart_row[quantity] != 0){
			    //zz tran()用于添加transaction表的记录(上面已经改了实质的part或者car表了，这里再添加上关于本次trans的信息到trans表)。type就是car或者part、就这两个string取其一。
				tran($user_of_cart,$cart_row[barcode],str_replace("ew_", "",$cart_row[table]),$cart_row[quantity],$cart_row[application]);
			}
		}
	}
	////zz to be updated and uncommented 这边这个sendMsg相关的（csv文件）都还没改（针对新加的appli域）。。待完成并重新打开
	//send_msg();
	$sql_del = "DELETE FROM `ew_cart` WHERE `user` = '".$user_of_cart."';";
	if(!($result=mysql_query($sql_del))){ 
			stop('DB Error!');
		}else{
			echo "<p>Your List has been proceeded successfully!</p>";//zz 这个echo就相当于return一个string了、由于这个file是以ajax res的形式存在的
		}
}


function clear_cart($user_of_cart){
	$sql_del = "DELETE FROM `ew_cart` WHERE `user` = '".$user_of_cart."';";
	if(!($result=mysql_query($sql_del))){ 
			stop('DB Error!');
	}
}

$sql_code_1 = "SELECT * FROM `ew_cart` WHERE `user` = '".$user_of_cart."' ORDER BY `cid` ASC;";
$result_info_1 = mysql_query($sql_code_1);


?>

<script>
    //zz javascript mocked html form submition http req (post/get)
    function sendHttpRequest(path, params, method) {
        let formForPosting = document.createElement("form");
        formForPosting.setAttribute("method", method);
        formForPosting.setAttribute("action", path);

        for (var key in params) {
            if (params.hasOwnProperty(key)){
                var hiddenInputTag = document.createElement("input");
                hiddenInputTag.setAttribute("type","hidden");
                hiddenInputTag.setAttribute("name", key);
                hiddenInputTag.setAttribute("value",params[key]);

                formForPosting.appendChild(hiddenInputTag);
            }
        }

        document.body.appendChild(formForPosting);
        formForPosting.submit();
    }

    //zz toBeContinueToResearch...
    function edit_handler(barcode,appli){
        sendHttpRequest("depart.php",{"barcode":barcode, "application":appli, "is_edit_cart":"true"},"post");
    }
</script>

<style>
table,th,td
{
border-collapse:collapse;
border-style:dotted;
border-width:1px;
text-align: center;
}
</style>
<table class="table table-bordered">
    <thead>
        <tr>

            <th></th>
            <th>No.</th>
            <th>Barcode &nbsp;&nbsp;<i class="fa fa-question-circle" title="Click link to depart more"></i></th>
            <th>Amount</th>
            <th>Name</th>
            <th>Application</th>
            <th>Submitted</th>

        </tr>
    </thead>

<?php 
$i = 0;
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
$i = $i+1;
?>
    <tbody>
        <tr>
            <!--    zz toBeContinueToResearch..-->
            <!--<td><button id="btn_edit" onclick="edit_handler(--><?php //echo $row_1[barcode]; ?><!--,--><?php //echo $row_1["application"]; ?><!--)">Edit</button></td>-->

            <td><a class="btn btn-primary btn-xs btn-inside-table"
                   data-placement="top" data-toggle="tooltip" data-original-title="Edit" title="Edit"
                   href="?barcode=<?php echo $row_1['barcode']; ?>&appli=<?php echo $row_1["application"]; ?>&is_edit_cart=true">
                    <i class="fa fa-pencil"></i>
                </a>
            </td>
            <td><?php echo $i."."; ?></td>
            <td><a href="?barcode=<?php echo $row_1[barcode]; ?>" title="Click link to depart more" class="a-underline-zz">
                    <?php echo $row_1[barcode]; ?></a></td>
            <td><?php echo $row_1[quantity]; ?></td>
            <td><?php echo get_name($row_1[barcode]); ?></td>
            <td><?php echo $row_1["application"]; ?></td>
            <td><?php echo $row_1["pending"]; ?></td>
        </tr>
    </tbody>
<?php 
}; 
?> 
</table>
<!-- jQuery -->
<script src="p_g_dash/vendors/jquery/dist/jquery.min.js"></script>
<script>
    // $('[data-toggle=tooltip]').
</script>





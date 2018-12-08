<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: create_part_ordering_sheet.php
* This file display item lists based on inputs
*/
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

$sqltag="";
$urltag="";
$default_sort=" ORDER BY `barcode` DESC ";

//check inventory
//if ($_GET['check']=='inventory') {
//	$sqltag="WHERE `quantity` > '0'";
//	$urltag=$urltag."&check=inventory";
//}

//check bin
//if ($_GET['check']=='bin') {
//	$sqltag="WHERE `w_quantity` = '-1'";
//	$urltag=$urltag."&check=bin";
//}

//check out of stock
//if ($_GET['check']=='out') {
//	$sqltag="WHERE `quantity` = '0' AND `w_quantity` != '-1'";
//	$urltag=$urltag."&check=out";
//}

//List of parts or cars? default cars
//if (isset($_GET["table"])) {
//	$table = $_GET["table"];
//	$urltag= $urltag."&table=$table";
//} else {
//	$table = "ew_car";
//	$urltag= $urltag."&table=ew_car";
//}
$table = "ew_part";
$urltag= $urltag."&table=ew_part";

//zz for create_part_ordering_sheet.php
//check short supply
//if ($_GET['check']=='short') {
	$sqltag= "WHERE `$table`.w_quantity > `$table`.quantity";
	$urltag=$urltag."&check=short";
//}

//sort list based on inputs
if($_GET['sort']=='name'){
	$sort = " ORDER BY `name` ASC ";
	$urltag= $urltag."&sort=".$_GET['sort'];
}else if($_GET['sort']=='color'){
	$sort = " ORDER BY `color` ASC ";
	$urltag= $urltag."&sort=".$_GET['sort'];
}else if($_GET['sort']=='category'){
	$sort = " ORDER BY `category` ASC ";
	$urltag= $urltag."&sort=".$_GET['sort'];
}else{
	$sort = $default_sort;
}

//zz pagenation removed in create_part_ordering_sheet.php, for better form submission
////load lists with page spliter
//$split_by = '40';
//
//if (isset($_GET["page"])) {
//	$page = $_GET["page"];
//} else {
//	$page=1;
//}

//zz loading data, select from part or car table with where clause, stored in $result_info_1. Also $total_records and $total_pages are counts of data..
//$start_from = ($page-1) * $split_by;
$sql_code_1 = "SELECT * FROM `".$table."` ".$sqltag.$sort;
//$sql_code_1 = "SELECT * FROM `ew_car` WHERE `quantity` > '0' ORDER BY `barcode` ASC LIMIT ".$start_from.",".$split_by;
//echo $sql_code_1;
$result_info_1 = mysql_query($sql_code_1);

$sql_code_2 = "SELECT COUNT(barcode) FROM `".$table."` ".$sqltag.";"; 
//$sql_code_2 = "SELECT COUNT(barcode) FROM `ew_car` WHERE `quantity` > '0';"; 
$result_info_2 = mysql_query($sql_code_2);
$row_2 = mysql_fetch_row($result_info_2); 
$total_records = $row_2[0]; 
//$total_pages = ceil($total_records / $split_by);

//zz handler for "search to add item" feature -- links to search results will come to this page with new paras to catch..
if(isset($_GET['barcode'])){
    $sql_query = "SELECT * FROM `ew_part` WHERE `barcode` = '".$_GET['barcode']."';";
    $result_3 = mysql_query($sql_query);
    $row_3 = mysql_fetch_assoc($result_3);
//    array_unshift($result_info_1,$row_3);//append new record to the beginning of result set assoc array.
}

//zz handler for "edit part num (YiGao) by double click cell"--after submit this cell
if(isset($_GET['txt_part_num_yigao'])){
//    $confirmed = false;
//    echo "<script>
//            let r=confirm('Following changes will be made to database: ' +
//             'The part number (YiGao) of [".$_GET['barcode']."]".get_anything($_GET['barcode'],'name')." will be changed to ".$_GET['txt_part_num_yigao']."');
//            if (r==true) {
//                ".."
//            }
//          </script>";
    $sql_query1 = "UPDATE `ew_part` SET 
				`part_num_yigao` ='".$_GET['txt_part_num_yigao']."'
				WHERE `barcode` = '".$_GET['barcode']."';";
    if (!($result=mysql_query($sql_query1))) {

        mysql_close($link);
        check_pass(0);
        echo("<script>window.alert('DB Error!');</script>");
        die('<meta http-equiv="refresh" content="0;URL=index.php">');
    }
    else{
        sys_log($_COOKIE['ew_user_name'],"edit part, barcode=".$_GET['barcode'].", name=".get_anything($_GET['barcode'],'name'));
        mysql_close($link);
        echo("<script>window.alert('Part profile has been updated!');</script>");
        die("<meta http-equiv='refresh' content='0;URL=create_part_ordering_sheet.php'>");
    }
}

$title_by_page = "Create Part Ordering Sheet";
include('header.php');

?>
<script>
    checked = false;
    function checkedAll()
    {
        checked = !checked;
        for (var i = 0; i < document.getElementById('form_parts_list').elements.length; i++) {
            document.getElementById('form_parts_list').elements[i].checked = checked;
        }
    }

    //zz JS Handler for smartSearch's keyUp event
    function suggest(key)
    {
        document.getElementById("suggestion").style.display = "block";
        var xmlhttp;
        //var table = document.getElementById("db_table").value;
        var table = "ew_part";
        var postdata = "keyword="+encodeURIComponent(key)+"&table="+table+"&special=create_part_ordering_sheet";
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

        xmlhttp.open("POST","ajax/search_suggestion.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.setRequestHeader("Content-length", postdata.length);
        xmlhttp.send(postdata);

    }

    //zz listener -- doubleClick to edit part_num_yigao
    function edit_part_num_yigao(barcode,old_part_num_yigao)
    {
        var xmlhttp;
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
                document.getElementById("cell_edit_partnumyigao_"+barcode).innerHTML=xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","ajax/ypartnum_edit_for_create_part_order.php?barcode="+barcode
            +"&old_part_num_yigao="+old_part_num_yigao
            ,true);
        xmlhttp.send();
    }


</script>

<div id="main">
<div class="content_box_top"></div>
<div class="content_box_top"></div>
<div class="content_box">

<form name="form_smart_search" method="get" action="" >
        <span>Search to add more items into the list below (click on one of the suggested result after searching):&nbsp;</span>
        <input type="hidden" name="table" value="ew_part"/>
        <input type="text" id="keyword" name="keyword" class="input_field" value="<?php //echo $temp_key; ?>" autocomplete="off" onkeyup="suggest(this.value)"/>
        <input type="submit" class="submit_btn" name="submit_smart_search" value="Search"/>
</form>

<div id="suggestion" style="display: none"></div>

<!--    zz pagenation removed in create_part_ordering_sheet.php, for better form submission-->
<!--<p>Page:-->
<?php //
//
//for ($i=1; $i<=$total_pages; $i++) {
//            echo "<a href='list.php?page=".$i.$urltag."'>".$i."</a> ";
//};
//?>
<!--</p>-->

<p>
    <?php echo(($row_3)?($total_records+1):$total_records); ?> result(s) was found in this query. Sort by <a href ="list.php?<?php echo trim_url("&sort="); ?>">[Default]</a> <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=name">[Name]</a> <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=color">[Color]</a>  <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=category">[Category]</a>
</p>

<form name="form_parts_list" id="form_parts_list" method="post" action="create_part_ordering_sheet.php">

<input type="submit" class="submit_btn" name="submit_create_order_list" value="Generate Order List"/>

<table style="table-layout: fixed;">
<tr>
    <th>Barcode</th>
    <th style="min-width: 70px">Name</th>
    <th>*Part Number</th>
    <th>Part Number(YiGao)</th>
    <!--<td>Category</td>-->
    <?php //if($table == "ew_part"){echo "<td>For</td>";} ?>
    <?php //if($table == "ew_car"){echo "<td>Model</td>";} ?>
    <!--<td>Color</td>-->
    <?php //if($table == "ew_car"){echo "<td>Condition</td>";} ?>
    <th>*InStock</th>
    <th>*Warning</th>
<!--    <th>Action</th>-->
    <th style="width:1%; white-space: nowrap">Add to List<br/>(Select all<input type="checkbox" name="a" onclick="checkedAll()"/>)</th>
    <th>Order Amount</th>
    <th>Note</th>
</tr>

    <!--    for newly added row from search bar-->
    <?php if($row_3){
        $w_quantity = ($row_3["w_quantity"] =='0')?"n/a":$row_3["w_quantity"];
        echo "<tr>

                        <td><a href='view_part.php?barcode=".$row_3[barcode]."'>
                                ".$row_3[barcode]."
                            </a>
                        </td>
                        <td style='min-width: 150px'>".$row_3["name"]."</td>
                        <td style='max-width: 70px；white-space: normal; word-wrap:break-word'>".$row_3["part_num"]."</td>
                        <td style='max-width: 70px;'
                            id='cell_edit_partnumyigao_".$row_1["barcode"]."'
                            ondblclick='edit_part_num_yigao(".$row_3["barcode"].",".$row_3["part_num_yigao"].")'
                            onblur='changed()'>
                            ".$row_3["part_num_yigao"]."</td>
                        <td>".$row_3["quantity"]."</td>
                        <td>".$w_quantity."</td>


                        <td><input type='checkbox' name='checkbox_".$row_3["barcode"]."' value='checked'/></td>
                        <td><input type='text' name='txt_order_amount_".$row_3["barcode"]."' value =''
                               class='input_field_w w50' autocomplete='off'/></td>
                        <td><input type='text' name='txt_note_".$row_3["barcode"]."' value =''
                               class='input_field_w w50' autocomplete='off'/></td>
                      </tr>";}
    ?>

<?php 
while ($row_1 = mysql_fetch_assoc($result_info_1)) { 
?> 
            <tr>
            
                <td><a href="view_part.php?barcode=<?php echo $row_1["barcode"]; ?>">
                        <?php echo $row_1["barcode"]; ?>
                    </a>
                </td>
                <td style="min-width: 150px"><?php echo $row_1["name"]; ?></td>
                <td style="max-width: 70px;white-space: normal;word-wrap:break-word"><?php echo $row_1["part_num"]; ?></td>
                <td style="max-width: 70px;"
                    id="cell_edit_partnumyigao_<?php echo $row_1["barcode"];?>"
                    ondblclick="edit_part_num_yigao('<?php echo $row_1["barcode"]; ?>','<?php echo $row_1["part_num_yigao"]; ?>')"
                    onblur="changed()"
                    ><?php echo $row_1["part_num_yigao"]; ?></td>
                <!--			--><?php //if($table == "ew_part"){echo "<td>".$row_1["sub_category"]."</td>";} ?>
    <!--			--><?php //if($table == "ew_car"){echo "<td>".$row_1["model"]."</td>";} ?>
    <!--			<td>--><?php //echo $row_1["color"]; ?><!--</td>-->
    <!--			--><?php //if($table == "ew_car"){echo "<td>".$row_1["condition"]."</td>";} ?>
                <td><?php echo $row_1["quantity"]; ?></td>
                <td><?php if($row_1["w_quantity"] =='0'){ echo "n/a";}else{ echo $row_1["w_quantity"];}; ?></td>
<!--                <td>--><?php //echo $row_1["l_zone"]."_".$row_1["l_column"]."_".$row_1["l_level"]; ?><!--</td>-->
<!--                <td>-->
<!--                    <a href="edit_--><?php //if($table == "ew_car"){echo "car";}else{echo "part";} ?><!--.php?barcode=--><?php //echo $row_1["barcode"]; ?><!--">Edit</a>-->
<!--                    <a href="">Delete</a>-->
<!--                </td>-->
                <td><input type="checkbox" name="checkbox_<?php echo $row_1["barcode"]; ?>" value="checked"/></td>
                <td><input type="text" name="txt_order_amount_<?php echo $row_1["barcode"]; ?>" value ="<?php //if($suggested_decrease){echo $suggested_decrease;}else{echo "-1";}?>"
                       class="input_field_w w50" autocomplete="off"/></td>
                <td><input type="text" name="txt_note_<?php echo $row_1["barcode"]; ?>" value ="<?php ?>"
                           class="input_field_w w50" autocomplete="off"/></td>
            </tr>
<?php 
}; 
?> 
</table>
</form>

<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>
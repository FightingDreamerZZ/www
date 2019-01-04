<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: list.php
* This file display item lists based on inputs
*/
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

$sqltag="";
$urltag="";
$default_sort=" ORDER BY `date` DESC ";

//check inventory
if ($_GET['check']=='inventory') { 
	$sqltag="WHERE `quantity` > '0'";
	$urltag=$urltag."&check=inventory";
}

//check inventory
if ($_GET['check']=='bin') { 
	$sqltag="WHERE `w_quantity` = '-1'";
	$urltag=$urltag."&check=bin";
}

//check out of stock
if ($_GET['check']=='out') { 
	$sqltag="WHERE `quantity` = '0' AND `w_quantity` != '-1'";
	$urltag=$urltag."&check=out";
}

//check short supply
if ($_GET['check']=='short') { 
	$sqltag= "WHERE `ew_part`.w_quantity > `ew_part`.quantity";
	$urltag=$urltag."&check=short";
}

//zz sorting
if($_GET['sort_order']){//init sort_order
    $sort_order = $_GET['sort_order'];
} else
    $sort_order = "asc";
$sort_order_reversed = ($sort_order=="asc")?"desc":"asc";//for d-click reversing

if($_GET['sort_field']){//persist url
    $urltag= $urltag."&sort_field=".$_GET['sort_field']."&sort_order=".$_GET['sort_order'];
}

//init
$icon_class_sort_barcode = "glyphicon glyphicon-sort";
$icon_class_sort_name = "glyphicon glyphicon-sort";
$icon_class_sort_part_num = "glyphicon glyphicon-sort";
$icon_class_sort_location = "glyphicon glyphicon-sort";
$icon_class_sort_organizing201809 = "glyphicon glyphicon-sort";
$q_s_sort_barcode = "&sort_field=barcode&sort_order=asc";
$q_s_sort_name = "&sort_field=name&sort_order=asc";
$q_s_sort_part_num = "&sort_field=part_num&sort_order=asc";
$q_s_sort_location = "&sort_field=location&sort_order=asc";
$q_s_sort_organizing201809 = "&sort_field=organizing201809&sort_order=asc";

switch ($_GET['sort_field']) {//case by case
    case "barcode":
        $sort = " ORDER BY `barcode` ".$sort_order." ";
        $q_s_sort_barcode = str_replace("asc",$sort_order_reversed,$q_s_sort_barcode);
        $icon_class_sort_barcode = ($sort_order=="asc")?"glyphicon glyphicon-sort-by-attributes":"glyphicon glyphicon-sort-by-attributes-alt";
        break;
    case "name":
        $sort = " ORDER BY `name` ".$sort_order." ";
        $q_s_sort_name = str_replace("asc",$sort_order_reversed,$q_s_sort_name);
        $icon_class_sort_name = ($sort_order=="asc")?"glyphicon glyphicon-sort-by-attributes":"glyphicon glyphicon-sort-by-attributes-alt";
        break;
    case "part_num":
        $sort = " ORDER BY `part_num` ".$sort_order." ";
        $q_s_sort_part_num = str_replace("asc",$sort_order_reversed,$q_s_sort_part_num);
        $icon_class_sort_part_num = ($sort_order=="asc")?"glyphicon glyphicon-sort-by-attributes":"glyphicon glyphicon-sort-by-attributes-alt";
        break;
    case "location":
        $sort = " ORDER BY `l_zone` ".$sort_order." ";
        $q_s_sort_location = str_replace("asc",$sort_order_reversed,$q_s_sort_location);
        $icon_class_sort_location = ($sort_order=="asc")?"glyphicon glyphicon-sort-by-attributes":"glyphicon glyphicon-sort-by-attributes-alt";
        break;
    case "organizing201809":
        $sort = " ORDER BY `organizing201809` ".$sort_order." ";
        $q_s_sort_organizing201809 = str_replace("asc",$sort_order_reversed,$q_s_sort_organizing201809);
        $icon_class_sort_organizing201809 = ($sort_order=="asc")?"glyphicon glyphicon-sort-by-attributes":"glyphicon glyphicon-sort-by-attributes-alt";
        break;
    default:
        $sort = $default_sort;
        break;
}
// zz /sorting

//sort list based on inputs
//if($_GET['sort']=='name'){
//	$sort = " ORDER BY `name` ASC ";
//	$urltag= $urltag."&sort=".$_GET['sort'];
//}else if($_GET['sort']=='color'){
//	$sort = " ORDER BY `color` ASC ";
//	$urltag= $urltag."&sort=".$_GET['sort'];
//}else if($_GET['sort']=='category'){
//	$sort = " ORDER BY `category` ASC ";
//	$urltag= $urltag."&sort=".$_GET['sort'];
//}else{
//	$sort = $default_sort;
//}

//load lists with page spliter
$split_by = '40';

if (isset($_GET["page"])) { 
	$page = $_GET["page"]; 
} else { 
	$page=1; 
}
$start_from = ($page-1) * $split_by;
$sql_code_1 = "SELECT * FROM `ew_part` ".$sqltag.$sort."LIMIT ".$start_from.",".$split_by;
//$sql_code_1 = "SELECT * FROM `ew_car` WHERE `quantity` > '0' ORDER BY `barcode` ASC LIMIT ".$start_from.",".$split_by;
//echo $sql_code_1;
$result_info_1 = mysql_query($sql_code_1);

$sql_code_2 = "SELECT COUNT(barcode) FROM `ew_part` ".$sqltag.";";
//$sql_code_2 = "SELECT COUNT(barcode) FROM `ew_car` WHERE `quantity` > '0';"; 
$result_info_2 = mysql_query($sql_code_2);
$row_2 = mysql_fetch_row($result_info_2); 
$total_records = $row_2[0]; 
$total_pages = ceil($total_records / $split_by);

//$title_by_page = "All Parts";
//include('header.php');

include_template_header_css_sidebar_topbar("","All Parts","");

?>
<!-- page content -->
<div class="right_col" role="main">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Part List<small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                    <p><?php echo($total_records); ?> result(s) was found in this query.
                        Sort by
                        <a href ="list.php?<?php echo trim_url("&sort="); ?>">
                            [Default]</a>
                        <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=name">
                            [Name]</a>
                        <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=color">
                            [Color]</a>
                        <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=category">
                            [Category]</a>
                    </p>


                    <p style="position: relative;float: left">Page:
                        <?php
                        for ($i=1; $i<=$total_pages; $i++) {
                            echo "<a href='list.php?page=".$i.$urltag."'>".$i."</a> ";
                        };
                        ?>
                    </p>

                    <table id="table_list_parts" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Barcode
                                <a href="list.php?<?php echo $q_s_sort_barcode;?>" class="<?php echo $icon_class_sort_barcode;?> href_sort"  aria-hidden="true"></a>
                            </th>
                            <th>Name
                                <a href="list.php?<?php echo $q_s_sort_name;?>" class="<?php echo $icon_class_sort_name;?> href_sort"  aria-hidden="true">
                            </th>
                            <th>PartNumber
                                <a href="list.php?<?php echo $q_s_sort_part_num;?>" class="<?php echo $icon_class_sort_part_num;?> href_sort"  aria-hidden="true">
                            </th>
                            <th>Category</th>
                            <th>InStock</th>
                            <th>warning</th>
                            <th>Location
                                <a href="list.php?<?php echo $q_s_sort_location;?>" class="<?php echo $icon_class_sort_location;?> href_sort"  aria-hidden="true">
                            </th>
                            <th>Organizing201809
                                <a href="list.php?<?php echo $q_s_sort_organizing201809;?>" class="<?php echo $icon_class_sort_organizing201809;?> href_sort"  aria-hidden="true">
                            </th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row_1 = mysql_fetch_assoc($result_info_1)) {
                            $c_event_name_tmp = get_c_event_by('c_event_id', $row_1["last_counting_event"])['c_event_name'];
                            $c_event_name_tmp = ($c_event_name_tmp==false)?"N/A":$c_event_name_tmp;
                            ?>
                            <tr>
                                <td>
                                    <a class="a-underline-zz" href="view_part.php?barcode=<?php echo $row_1["barcode"]; ?>">
                                        <?php echo $row_1["barcode"]; ?>
                                    </a>
                                </td>
                                <td><?php echo $row_1["name"]; ?></td>
                                <td><?php echo $row_1["part_num"]; ?></td>
                                <td><?php echo $row_1["category"]; ?></td>
                                <td><?php echo $row_1["quantity"]; ?></td>
                                <td><?php if($row_1["w_quantity"] =='0'){ echo "n/a";}else{ echo $row_1["w_quantity"];}; ?></td>
                                <td><?php echo $row_1["l_zone"]."_".$row_1["l_column"]."_".$row_1["l_level"]; ?></td>
                                <td><?php echo $row_1["organizing201809"]; ?></td>
                                <td>
                                    <a class="btn btn-primary btn-xs btn-inside-table"
                                       data-placement="top" data-toggle="tooltip" data-original-title="Edit"
                                       href="edit_part.php?barcode=<?php echo $row_1["barcode"]; ?>">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        };
                        ?>
                        </tbody>
                    </table>

                    <style>
                        .table thead .sorting-general::after {
                            content: '\e150';
                        }
                        .a-underline-zz {
                            text-decoration: underline;
                        }
                        .btn-inside-table {
                            margin: -5px 0px;
                        }
                        #table_list_parts .href_sort {
                            float: right;
                        }
                    </style>
                </div><!--x_content-->
            </div><!--x_panel-->
        </div><!--col-->
    </div><!--row-->

</div>
    <!-- /page content -->

<?PHP
include('template_footer_scripts.php');
?>
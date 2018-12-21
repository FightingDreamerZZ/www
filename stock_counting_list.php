<?php
/**
 * Created by PhpStorm.
 * Date: 2018-12-18
 * Time: 10:38 AM
 */
define("FILE_NAME", "stock_counting_list.php");
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();

//zz check for cookie for countingEvent persist
$selected_current_c_event_name="Please choose... ";
if($_COOKIE['selected_c_c_event_id']){
    $selected_c_c_event_id = $_COOKIE['selected_c_c_event_id'];
    $selected_current_c_event_name =
        get_c_event_by('c_event_id',$selected_c_c_event_id)['c_event_name'];
}

//zz (cookie for countingEvent persist) setter
if($_GET['selected_c_c_event_id']){
    setcookie("selected_c_c_event_id",$_GET['selected_c_c_event_id'],time()+60*60*2);
    $selected_c_c_event_id = $_GET['selected_c_c_event_id'];
    $selected_current_c_event_name =
        get_c_event_by('c_event_id',$_GET['selected_c_c_event_id'])['c_event_name'];
}
//&&($_GET['selected_c_c_event_id']!="select_all")
//else
//    $selected_current_c_event_name = "Showing all events";
if($_GET['select_all_c_event']){
    setcookie('selected_c_c_event_id',null,time()-3600);
}

//zz load all countingEvents
$array_c_events = get_all_c_events();

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
    $sqltag= "WHERE `w_quantity` > `quantity`";
    $urltag=$urltag."&check=short";
}

//zz check by counting event
if($_GET['selected_c_c_event_id']){
    $sqltag = "WHERE `last_counting_event` = ".$_GET['selected_c_c_event_id'];
}elseif($_COOKIE['selected_c_c_event_id'] && !isset($_GET['select_all_c_event'])) {
    $sqltag = "WHERE `last_counting_event` = ".$_COOKIE['selected_c_c_event_id'];
}

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

$title_by_page = "All Parts";
include('template_header_css_sidebar_topbar.php');

?>
<script>
    function add_new_c_evnt(){
        let action = "add_new_c_evnt";
        $.ajax({
            url: "ajax/ajax_stock_counting.php",
            data: "action="+action,
            dataType: "json",
            type: "post",
            success: function(data){

                console.log(data);
                $("#div_c_event_ddl").html(data.html_code).fadeIn("fast");
            },
            error: function(data){
                console.log("Ajax went to error (probably return type wrong):"+ data);
            }
        });
    }
</script>

    <!-- page content -->
    <div class="right_col" role="main">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Default Example <small>Users</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
<!--                            <li class="dropdown">-->
<!--                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>-->
<!--                                <ul class="dropdown-menu" role="menu">-->
<!--                                    <li><a href="#">Settings 1</a>-->
<!--                                    </li>-->
<!--                                    <li><a href="#">Settings 2</a>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </li>-->
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <p class="text-muted font-13 m-b-30">
                        <p><?php echo($total_records); ?> result(s) was found in this query. Sort by <a href ="list.php?<?php echo trim_url("&sort="); ?>">[Default]</a> <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=name">[Name]</a> <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=color">[Color]</a>  <a href="list.php?<?php echo trim_url("&sort="); ?>&sort=category">[Category]</a></p>


                        <p style="position: relative;float: left">Page:
                            <?php
                            for ($i=1; $i<=$total_pages; $i++) {
                                echo "<a href='stock_counting_list.php?page=".$i.$urltag."'>".$i."</a> ";
                            };
                            ?>
                        </p>

                        <!--zz countingEvent ddl-->
                        <div id="div_c_event_ddl">
                            <label>Current counting event:&nbsp;</label>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <?php echo $selected_current_c_event_name;?> &nbsp;
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <?php
                                    foreach($array_c_events as $c_event){
                                        echo <<<temp
                                <li>
                                    <a href="stock_counting_list.php?selected_c_c_event_id={$c_event['c_event_id']}">
                                        {$c_event['c_event_name']}
                                    </a>
                                </li>
temp;
                                    }
                                    ?>
                                    <li role="separator" class="divider"></li>
                                    <li><button onclick="add_new_c_evnt()">+ Add New</button></li>
                                    <li><a href="stock_counting_list.php?select_all_c_event=1">
                                            Show all
                                        </a>
                                    </li>
                                </ul>
                                <style>
                                    #div_c_event_ddl {
                                        float: right;position: relative;
                                    }
                                    .dropdown-menu-right{
                                        right:0;
                                        left:auto;
                                    }
                                </style>
                            </div>
                        </div>
                        <!--zz /countingEvent ddl-->

                        <table id="datatable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Barcode</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>For</th>
                                    <th>Color</th>
                                    <th>InStock</th>
                                    <th>warning</th>
                                    <th>Location</th>
                                    <th>LastCountingEvent</th>
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
                                        <a class="a-underline-zz" href="stock_counting.php?barcode=<?php echo $row_1["barcode"]; ?>">
                                            <?php echo $row_1["barcode"]; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $row_1["name"]; ?></td>
                                    <td><?php echo $row_1["category"]; ?></td>
                                    <td><?php echo $row_1["sub_category"]; ?></td>
                                    <td><?php echo $row_1["color"]; ?></td>
                                    <td><?php echo $row_1["quantity"]; ?></td>
                                    <td><?php if($row_1["w_quantity"] =='0'){ echo "n/a";}else{ echo $row_1["w_quantity"];}; ?></td>
                                    <td><?php echo $row_1["l_zone"]."_".$row_1["l_column"]."_".$row_1["l_level"]; ?></td>
                                    <td><?php echo $c_event_name_tmp;?></td>
                                    <td>
                                        <a class="btn btn-primary btn-xs btn-inside-table"
                                           data-placement="top" data-toggle="tooltip" data-original-title="Edit"
                                           href="stock_counting.php?edit_mode=1&barcode=<?php echo $row_1["barcode"]; ?>">
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
                            .a-underline-zz {
                                text-decoration: underline;
                            }
                            .btn-inside-table {
                                margin: -5px 0px;
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
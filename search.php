<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: search.php
* This file performs search related functions
*/

//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');//zz path forwardSlash tempForMac
include('lib/user_lib.php');

check_user_cookie();

//Barcode Search handler
if ($_GET['do']=='barcode') {
    $barcode = $_POST[keyword];
    $table = get_table($barcode);
    if($table=="ew_car"){
        die('<meta http-equiv="refresh" content="0;URL=view_car.php?barcode='.$barcode.'">');
    }else{
        die('<meta http-equiv="refresh" content="0;URL=view_part.php?barcode='.$barcode.'">');
    }
}
//======================


//Page Seperator
$split_by = '20';
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page=1;
}
$start_from = ($page-1) * $split_by;

//================


//zz --main search handler, exact match search based on keyword and table:
if (isset($_GET["keyword"])) {
    $temp_key = $_GET[keyword];
} else {
    $temp_key = '';
}
if (isset($_GET["table"])) {
    $table = $_GET[table];
} else {
    $table = 'ew_part';
}

$highlight = str_replace(","," ",$temp_key);
$keyword = explode(',', $temp_key);
$sqltag = '';
foreach ($keyword as &$value) {
    if($sqltag ==''){
        $sqltag = "`xsearch` LIKE '%$value%' "; //zz xsearch应该是专为searchSuggestion功能弄的一个column，有全部各个域的value串成一长string，也被用于display part details
    }else{
        $sqltag = $sqltag."AND `xsearch` LIKE '%$value%' ";
    }
}

if($table == "ew_part"){
    $sql_code_1 = "SELECT * FROM `".$table."` WHERE (`w_quantity` != '-1') AND (".$sqltag.") ORDER BY `barcode` DESC LIMIT ".$start_from.",".$split_by;
    $sql_code_2 = "SELECT COUNT(barcode) FROM `".$table."` WHERE (`w_quantity` != '-1') AND (".$sqltag.")";
}else{
    $sql_code_1 = "SELECT * FROM `".$table."` WHERE (`quantity` > '0') AND (".$sqltag.") ORDER BY `barcode` DESC LIMIT ".$start_from.",".$split_by;
    $sql_code_2 = "SELECT COUNT(barcode) FROM `".$table."` WHERE (`quantity` > '0') AND (".$sqltag.")";
}
$result_info_1 = mysql_query($sql_code_1);


$result_info_2 = mysql_query($sql_code_2);
$row_2 = mysql_fetch_row($result_info_2);
$total_records = $row_2[0];
$total_pages = ceil($total_records / $split_by);

//$title_by_page = "Search";
//include('header.php');
include_template_header_css_sidebar_topbar("","Search","");
?>

<script type="text/javascript">
    function suggest(key)
    {
        var xmlhttp;
        var table = document.getElementById("db_table").value;
        var postdata = "keyword="+encodeURIComponent(key)+"&table="+table;
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

</script>

<style>
    .a-underline-zz {
        text-decoration: underline;
    }
    .auto-scroll-zz {
        overflow:auto;
    }
    .search-result .x_panel {
        overflow:auto;
        height: 110px;
    }
</style>

<!-- page content -->
<div class="right_col" role="main">

    <!--page-title-->
    <div class="page-title">
        <div class="title_left">
            <h2>Search</h2>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row search-result">

        <p>&nbsp;&nbsp;&nbsp;<?php echo($total_records); ?> result(s) was found in this query.</p>
        <p>Page:
            <?php
            for ($i=1; $i<=$total_pages; $i++) {
                if($i == $page)
                    echo "<a href='search.php?".trim_url("&page=")."&page=".$page.$urltag."' style='text-decoration: none;'>".$i."</a> ";
                else
                    echo "<a href='search.php?".trim_url("&page=")."&page=".$i.$urltag."' class='a-underline-zz'>".$i."</a> "; //zz 将原本的当前URL中“page=xx”及其之后的部分全抹去、挂上新的page
            };
            ?>
        </p>
        <!--zz x_panel tilts-->
        <?php
        while ($row_1 = mysql_fetch_assoc($result_info_1)) {//while start
        ?>
            <div class="col-md-3 col-sm-3 col-xs-6">
                <div class="x_panel">
                    <div class="x_content">
                        <a href="<?php echo get_view($table); ?>?barcode=<?php echo $row_1["barcode"]; ?>" class="a-underline-zz">
                            <?php echo $row_1["barcode"]; ?>
                        </a>
                        &nbsp;&nbsp;[Stock: <?php echo $row_1["quantity"]; ?>]
                        <br />
                        Name: <b><?php echo $row_1["name"]; ?></b><br/>
                        Part Number: <b><?php echo $row_1["part_num"]; ?></b><br/>
                        <?php if($row_1["des"]!=""){echo "Description: <b>";echo $row_1["des"]."</b>";}?>
                    </div>
                </div>
            </div>
        <?php
        }; //while end
        ?>

    </div>
</div><!-- page content -->

<?PHP
include('template_footer_scripts.php');
?>
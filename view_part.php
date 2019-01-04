<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: view_part.php
* This file displays part profile based on input barcode.
*/

//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('lib/sql.php');//zz path forwardSlash tempForMac
include('lib/user_lib.php');

check_user_cookie();

//load profile if barcode is given
if (isset($_GET['barcode'])) { 
	$barcode = $_GET['barcode'];
	if(check_data('ew_part','barcode',$barcode)){
		$sql_code = "select * from ew_part where barcode = '".$barcode."';";
		$result_info = mysql_query($sql_code);
		$a_check = mysql_fetch_array($result_info);
	}else{
		stop("Barcode not found!");
	}
    $c_event_name = get_c_event_by("c_event_id", $a_check['last_counting_event'])['c_event_name'];
    $c_event_name = ($c_event_name == false)?"N/A":$c_event_name;
}

//$load = " onload=\"load()\"";
//$title_by_page = "View Part";
//include('header.php');
include_template_header_css_sidebar_topbar(" onload=\"load()\"","View Part","");
?>

<script type="text/javascript">
	function loadXMLDoc()
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
		document.getElementById("attach_part").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/attach_part.php?option=view&main=<?php echo($a_check['barcode']); ?>",true);
	xmlhttp.send();
	}
	
   function load()
   {
      document.form_smart_search.keyword.focus();
	  loadXMLDoc();
   }

    function suggest(key)
    {
        document.getElementById("suggestion").style.display = "block";
        var xmlhttp;
        //var table = document.getElementById("db_table").value;
        var table = "ew_part";
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
    .table_view_part_page tbody th{
        /*border: 3px solid purple;*/
    }
    #operation_links_view_part {
        margin-top: 10px;
        text-align: center;
    }
    #div_view_part a img {
        display: block;
        margin: auto;
    }
</style>

    <!-- page content -->
    <div class="right_col" role="main">

        <!--zz x_panel single big-->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>View Parts<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!--zz view part-->
                        <div id="div_view_part" <?php /*echo $display_view_section;*/?>>
                            <div class="row">
                                <div class="col-md-4 col-xs-12"><!--left column-->
                                    <h4>Photo Preview</h4>
                                    <a href="<?php echo($a_check['photo_url']); ?>" target="_blank">
                                        <img style="width:auto;height:auto;object-fit: cover;overflow: hidden" class ="withborder" src="<?php echo get_thumb($a_check['photo_url']); ?>" />
                                        <!--    width="300" height="300" class="image_wrapper" -->
                                    </a>
                                    <div id="operation_links_view_part">
                                        <a class="btn btn-primary" href="edit_part.php?barcode=<?php echo $a_check["barcode"]; ?>"
                                           data-toggle="tooltip" data-original-title='Edit this part.' data-placement="bottom">
                                            Edit <i class="fa fa-pencil"></i>
                                        </a>
                                        <a class="btn btn-primary" href="edit_part.php?barcode=<?php echo $a_check["barcode"]; ?>"
                                           data-toggle="tooltip" data-original-title='Quick enter to inventory.' data-placement="bottom">
                                            Receiving <i class="fa fa-download"></i>
                                        </a>
                                        <a class="btn btn-primary" href="edit_part.php?barcode=<?php echo $a_check["barcode"]; ?>"
                                           data-toggle="tooltip" data-original-title='Quick depart from inventory' data-placement="bottom">
                                            Shipping <i class="fa fa-upload"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <table class="table table_view_part_page">
                                        <tbody>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right" style="border-top: 0px">
                                                Name
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left" style="border-top: 0px">
                                                <?php echo($a_check['name']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Barcode
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['barcode']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Part Number
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['part_num']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-5 col-sm-3 col-xs-12 text-right">
                                                Part Number (Eagle)
                                            </th>
                                            <td class="col-md-7 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['part_num_yigao']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Category
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['category']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Color
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['color']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Purchase Price
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['p_price']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Wholesale Price
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['w_price']); ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div><!--middle column-->
                                <div class="col-md-4 col-xs-12">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <th class="col-md-5 col-sm-3 col-xs-12 text-right" style="border-top: 0px">
                                                Retail Price
                                            </th>
                                            <td class="col-md-7 col-sm-9 col-xs-12 text-left" style="border-top: 0px">
                                                <?php echo($a_check['r_price']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-5 col-sm-3 col-xs-12 text-right">
                                                Quantity
                                            </th>
                                            <td class="col-md-7 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['quantity']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Stock Warning
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['w_quantity']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Location
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <a href="search.php?table=ew_part&keyword=<?php echo($a_check['l_zone']."_".$a_check['l_column']."_".$a_check['l_level']); ?>"><?php echo($a_check['l_zone']."_".$a_check['l_column']."_".$a_check['l_level']); ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Latest Update
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['date']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Flag Organizing1809
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['organizing201809']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Last Counting Event
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <?php echo($c_event_name); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                                Description
                                            </th>
                                            <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                                <?php echo($a_check['des']); ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div><!--right column-->
                            </div>

                            <h4>Associated Part</h4>
                            <div id="attach_part"></div>

                        </div>
                        <!--zz /view part-->
                    </div><!--x_content-->
                </div><!--x_panel-->
            </div><!--col-->
        </div><!--zz x_panel single big-->
    </div><!-- page content -->

<?PHP
include('template_footer_scripts.php');
?>
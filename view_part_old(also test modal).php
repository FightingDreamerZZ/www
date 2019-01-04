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

    <!-- page content -->
    <div class="right_col" role="main">

        <!--zz x_panel single big-->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Stack Counting<small></small></h2>

                        <div id="main">

                            <div class="content_box_top"></div>
                            <div class="content_box">

                                <h2>View Parts</h2>
                                <div class="cleaner"></div>

                                <form name="form_smart_search" method="get" action="search.php" >
                                    Smart Search for Parts:
                                    <input type="hidden" name="table" value="ew_part"/>
                                    <input type="text" id="keyword" name="keyword" class="input_field" value="<?php //echo $temp_key; ?>" autocomplete="off" onkeyup="suggest(this.value)"/>
                                    <input type="submit" class="submit_btn" value="Search"/>
                                </form>

                                <div id="suggestion" style="display: none"></div>

                                <!--    zz original barcode search bar (exactly match, link to same page):-->
                                <!--    <p>-->
                                <!--        <form name="form1" method="post" action="search.php?do=barcode">-->
                                <!--            Barcode Search:-->
                                <!--            <input type="text" name="keyword" autocomplete="off"/>-->
                                <!--            <input type="submit" name="submit" value="Go"/>-->
                                <!--        </form>-->
                                <!--    </p>-->

                                <div class="cleaner h30"></div>
                                <div class="col_w320 float_r">
                                    <ul class = "list">
                                        <li>Name: <?php echo($a_check['name']); ?></li>
                                        <li>Barcode: <?php echo($a_check['barcode']); ?></li>
                                        <li title="This part number is for AGT. They are older, more stable and referred on our product manuals.">Part Number: <?php echo($a_check['part_num']); ?></li>
                                        <li title="The newest part number on the domestic, Eagle side. It is useful when ordering parts from them.">Part Number (Eagle): <?php echo($a_check['part_num_yigao']); ?></li>
                                        <li>Category: <a href="search.php?table=ew_part&keyword=<?php echo($a_check['category']); ?>"><?php echo($a_check['category']); ?></a></li>
                                        <li>For: <a href="search.php?table=ew_part&keyword=<?php echo($a_check['sub_category']); ?>"><?php echo($a_check['sub_category']); ?></a></li>
                                        <li>Color: <a href="search.php?table=ew_part&keyword=<?php echo($a_check['color']); ?>"><?php echo($a_check['color']); ?></a></li>
                                        <li>Purchase Price: <?php echo($a_check['p_price']); ?></li>
                                        <li>Wholesale Price: <?php echo($a_check['w_price']); ?></li>
                                        <li>Retail Price: <?php echo($a_check['r_price']); ?></li>
                                        <li>Quantity: <?php echo($a_check['quantity']); ?></li>
                                        <li>Stock Warning: <?php echo($a_check['w_quantity']); ?></li>
                                        <li>Location: <a href="search.php?table=ew_part&keyword=<?php echo($a_check['l_zone']."_".$a_check['l_column']."_".$a_check['l_level']); ?>"><?php echo($a_check['l_zone']."_".$a_check['l_column']."_".$a_check['l_level']); ?></a></li>
                                        <li>Latest Update: <?php echo($a_check['date']); ?></li>

                                        <!--    zz temp for organizing1809-->
                                        <label>Flag Organizing1809: <?php echo($a_check['organizing201809']); ?></label>

                                        <li>Description: <?php echo($a_check['des']); ?></li>

                                    </ul>
                                </div>

                                <div class="col_w320 float_l">
                                    <h4>Photo Preview</h4>
                                    <a href="<?php echo($a_check['photo_url']); ?>" target="_blank">
                                        <img style="width:auto;height:auto;object-fit: cover;overflow: hidden" class ="withborder" src="<?php echo get_thumb($a_check['photo_url']); ?>" />

                                        <!--    width="300" height="300" class="image_wrapper" -->
                                    </a>
                                    <p>
                                        <a href="edit_part.php?barcode=<?php echo($a_check['barcode']); ?>">[Edit Profile]</a>
                                        <a href="enter.php?barcode=<?php echo($a_check['barcode']); ?>">[Quick Enter]</a>
                                        <a href="depart.php?barcode=<?php echo($a_check['barcode']); ?>">[Quick Depart]</a>
                                    </p>

                                </div>

                                <div class="cleaner h20"></div>

                                <h4>Associated Part</h4>
                                <div id="attach_part"></div>


                                <div class="cleaner h30"></div>
                                <div class="cleaner"></div>
                                <div class="cleaner"></div>
                            </div> <!-- end of a content box -->
                            <div class="content_box_bottom"></div>
                        </div> <!-- end of main -->

                        <!--testing img modal-->
                        <button id="testzz1">haha</button>

                        <div class="modal fade" id="imagemodal" style="/*width: 200%*/" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                            <span class="sr-only">Close</span>
                                        </button>
                                        <span><img src="" class="imagepreview1" style="width: 50%;display: inline" ></span>
                                        <span><img src="" class="imagepreview2" style="width: 50%;display: inline" ></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Google jQuery v3.2.1 -->
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                        <!-- Bootstrap v3.3.7 JavaScript -->
                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                        <!--    jqueryUI-->
                        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

                        <script>

                            //zz temp
                            $(function() {
                                $('#testzz1').on('click', function() {
                                    $('.imagepreview1').attr('src', "http://www.agtecars.com/img/agt_logo_white.jpg");
                                    $('.imagepreview2').attr('src', "http://www.agtecars.com/img/slide-bus.png");
                                    $('#imagemodal').modal('show');
                                });
                            });
                            $(document).ready(function () {
                                // $("#testzz1").click(function(){
                                //     $('#dialog').remove();
                                //     $('<div/>')
                                //         .attr({ title: "title yoyo", id: 'dialog'})
                                //         .html('<img src="http://www.agtecars.com/img/agt_logo_white.jpg">')
                                //         .appendTo('body');
                                //     $("#dialog").dialog({ //jueryUI's dialog
                                //         width: 600,
                                //         modal: true
                                //     });
                                // })
                            });
                            // /zz temp

                        </script>
                        <!--testing img modal finish-->

<?PHP
include('footer.php');
?>
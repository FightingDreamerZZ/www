<?php
/**
 * Created by PhpStorm.
 * User: AGT John
 * Date: 2018-12-07
 * Time: 9:54 PM
 */
if(!$_COOKIE['ew_user_name']){
    $logout ="&nbsp;";
}else{
    $logout ="Hi, ".$_COOKIE['ew_user_name']."~ Getting Tired? >> <a href=\"index.php?do=logout\">Logout</a>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="p_g_dash/production/images/favicon.ico" type="image/ico" />
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">

    <title><?php if(isset($title_by_page)){echo $title_by_page." - ";}?>AGT Warehouse Management System</title>

    <!-- Bootstrap -->
    <link href="p_g_dash/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="p_g_dash/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="p_g_dash/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="p_g_dash/vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="p_g_dash/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="p_g_dash/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="p_g_dash/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="p_g_dash/build/css/custom.min.css" rel="stylesheet">
    <link href="p_g_dash/build/css/custom.css" rel="stylesheet">
</head>

<style>
    /*.site_logo img{*/
        /*!*background: url("images/logo.png") no-repeat;*!*/
        /*display: block;*/
        /*!*margin: auto;*!*/
        /*margin-left: 9%;*/
        /*margin-top: 5%;*/
    /*}*/
    .site_logo_image_wrapper_md {
        background: url("images/logo_white.png") no-repeat;
        display: block;
        /*margin: auto;*/
        margin-left: 9%;
        margin-top: 5%;
        height:53px;
    }
    .site_logo_image_wrapper_sm {
        background: url("images/favicon-32x32.png") no-repeat center;
        height:32px;
    }
    /*.nav.side-menu>li.current-page:not(ul) {*/
        /*background: rgba(255, 255, 255, 0.5);*/
    /*}*/
    .nav.side-menu>li.active.current-page>a {
        background: rgba(255, 255, 255, 0.05);
    }
    .nav.side-menu>li>a:hover {
        background: rgba(255, 255, 255, 0.05);
    }
    .menu_section h5 {
        text-align: center;
        padding: auto;
        color: #fff;
        text-transform: uppercase;
        /*letter-spacing: .5px;*/
        font-weight: bold;
        font-size: 11px;
        margin-bottom: 0;
        margin-top: 0;
        text-shadow: 1px 1px #000
    }
    .top_nav_search {
        width: 40%;
        margin-bottom: 0px;
    }
    .top_nav_search .input-group {
        margin: 12.5px 0;
    }
    .top_nav_search .form-control {
        /*border-right: 0;*/
        box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0.075);
        /*border-radius: 25px 0px 0px 25px;*/
        /*padding-left: 20px;*/
        /*border: 1px solid rgba(221, 226, 232, 0.49)*/
    }
    .top_nav_search .form-control:focus {
        /*border: 1px solid rgba(221, 226, 232, 0.49);*/
        /*border-right: 0*/
    }
    .top_nav_search .input-group-btn .btn-top_nav_search{
        border-radius: 0px 25px 25px 0px;
        border: 1px solid rgba(221, 226, 232, 0.49);
        border-left: 0;
        box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0.075);
        color: #93A2B2;
        margin-bottom: 0 !important
    }
    #suggestion_top_nav {
        height:auto;
        margin-top: -5px;
    }
</style>

<script>
    //smartSearch
    function suggest_top_nav_search(key)
    {
        var xmlhttp;
        var table = "ew_part";
        var special = "<?php echo ($smart_search_special)?$smart_search_special:""?>";
        var postdata = "keyword="+encodeURIComponent(key)+"&table="+table+"&special="+special;
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
                document.getElementById("suggestion_top_nav").innerHTML=xmlhttp.responseText;
            }
        };

        xmlhttp.open("POST","ajax/search_suggestion.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.setRequestHeader("Content-length", postdata.length);
        xmlhttp.send(postdata);
    }
</script>

<body class="nav-md" <?php echo($load); ?>>
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <!--logo & site_title-->
                <div class="navbar nav_title" style="border: 0;">
                    <a href="index.php" class="site_logo">
                        <div id="site_logo_image_wrapper" class="site_logo_image_wrapper_md"></div>
<!--                        <img src="images/logo_white.png"/>-->
                    </a>
<!--                    <a href="index.php" class="site_title"><i class="fa fa-paw"></i> <span>Gentelella Alela!</span></a>-->
                </div>
                <!--/logo & site_title-->

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
<!--                <div class="profile clearfix">-->
<!--                    <div class="profile_pic">-->
<!--                        <img src="p_g_dash/production/images/img.jpg" alt="..." class="img-circle profile_img">-->
<!--                    </div>-->
<!--                    <div class="profile_info">-->
<!--                        <span>Welcome,</span>-->
<!--                        <h2>John Doe</h2>-->
<!--                    </div>-->
<!--                </div>-->
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h5>Warehouse Management System</h5>
                        <ul class="nav side-menu">
                            <li><a href="search.php"><i class="fa fa-search"></i> Search </a></li>
                            <li><a href="enter.php"><i class="fa fa-download"></i> Receiving </a></li>
                            <li><a href="depart.php"><i class="fa fa-upload"></i> Shipping </a></li>
                            <li><a href="list.php?check=inventory"><i class="fa fa-cubes"></i> Inventory <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="list.php?check=short">Shortage</a></li>
                                    <li><a href="list.php?check=out">Empty</a></li>
                                    <li><a href="list.php?check=bin">Disabled Parts</a></li>
                                </ul>
                            </li>
                            <li><a href="cart.php"><i class="fa fa-shopping-cart"></i> My Cart </a></li>
                            <li><a href="tran_list.php"><i class="fa fa-clock-o"></i> Transaction History </a></li>
                            <li><a href="images/map.gif" target="_blank"><i class="fa fa-map-marker"></i> Map </a></li>
                            <li><a href="stats.php"><i class="fa fa-line-chart"></i> Stats </a></li>
                            <li><a href="new_part.php"><i class="fa fa-plus-square"></i> New Part </a></li>
                            <li><a href="stock_counting.php"><i class="fa fa-check-square-o"></i> Stock-Counting <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="stock_counting_list.php">Stock-Counting List</a></li>
                                </ul>
                            </li>
                            <li><a href="cart.php"><i class="fa fa-shopping-cart"></i> My Cart </a></li>


                            <li><a><i class="fa fa-edit"></i> Forms <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="form.html">General Form</a></li>
                                    <li><a href="form_advanced.html">Advanced Components</a></li>
                                    <li><a href="form_validation.html">Form Validation</a></li>
                                    <li><a href="form_wizards.html">Form Wizard</a></li>
                                    <li><a href="form_upload.html">Form Upload</a></li>
                                    <li><a href="form_buttons.html">Form Buttons</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-desktop"></i> UI Elements <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="general_elements.html">General Elements</a></li>
                                    <li><a href="media_gallery.html">Media Gallery</a></li>
                                    <li><a href="typography.html">Typography</a></li>
                                    <li><a href="icons.html">Icons</a></li>
                                    <li><a href="glyphicons.html">Glyphicons</a></li>
                                    <li><a href="widgets.html">Widgets</a></li>
                                    <li><a href="invoice.html">Invoice</a></li>
                                    <li><a href="inbox.html">Inbox</a></li>
                                    <li><a href="calendar.html">Calendar</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-table"></i> Tables <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="tables.html">Tables</a></li>
                                    <li><a href="tables_dynamic.html">Table Dynamic</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-bar-chart-o"></i> Data Presentation <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="chartjs.html">Chart JS</a></li>
                                    <li><a href="chartjs2.html">Chart JS2</a></li>
                                    <li><a href="morisjs.html">Moris JS</a></li>
                                    <li><a href="echarts.html">ECharts</a></li>
                                    <li><a href="other_charts.html">Other Charts</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-clone"></i>Layouts <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="fixed_sidebar.html">Fixed Sidebar</a></li>
                                    <li><a href="fixed_footer.html">Fixed Footer</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="menu_section">
                        <h3>Live On</h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-bug"></i> Additional Pages <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="e_commerce.html">E-commerce</a></li>
                                    <li><a href="projects.html">Projects</a></li>
                                    <li><a href="project_detail.html">Project Detail</a></li>
                                    <li><a href="contacts.html">Contacts</a></li>
                                    <li><a href="profile.html">Profile</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-windows"></i> Extras <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="page_403.html">403 Error</a></li>
                                    <li><a href="page_404.html">404 Error</a></li>
                                    <li><a href="page_500.html">500 Error</a></li>
                                    <li><a href="plain_page.html">Plain Page</a></li>
                                    <li><a href="login.html">Login Page</a></li>
                                    <li><a href="pricing_tables.html">Pricing Tables</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="#level1_1">Level One</a>
                                    <li><a>Level One<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li class="sub_menu"><a href="level2.html">Level Two</a>
                                            </li>
                                            <li><a href="#level2_1">Level Two</a>
                                            </li>
                                            <li><a href="#level2_2">Level Two</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="#level1_2">Level One</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Landing Page <span class="label label-success pull-right">Coming Soon</span></a></li>
                        </ul>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <!--<img src="p_g_dash/production/images/img.jpg" alt="">-->
                                Hi, <?php echo ($_COOKIE['ew_user_name'])?$_COOKIE['ew_user_name']:"";?>
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="javascript:;">Profile</a></li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="badge bg-red pull-right">50%</span>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li><a href="javascript:;">Help</a></li>
                                <li><a href="index.php?do=logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                            </ul>
                        </li>

                        <!--smart search-->
                        <li class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search top_nav_search">
<!--                                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search top_nav_search">-->
                            <form name="form_smart_search" method="get" action="search.php">
                            <div class="input-group">
                                <input type="text" id="keyword" name="keyword" onkeyup="suggest_top_nav_search(this.value)" class="form-control" placeholder="Search for...">
                                <input type="hidden" name="table" value="ew_part" hidden/>
                                <span class="input-group-btn">
                                    <input type="submit" name="submit_smart_search" class="btn btn-default btn-top_nav_search" type="button" value="Go!">
                                </span>
                            </div>
                            </form>
                            <div id="suggestion_top_nav"></div>
<!--                                </div>-->
                        </li>
                        <!--/smart search-->

<!--                        <li role="presentation" class="dropdown">-->
<!--                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">-->
<!--                                <i class="fa fa-envelope-o"></i>-->
<!--                                <span class="badge bg-green">6</span>-->
<!--                            </a>-->
<!--                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">-->
<!--                                <li>-->
<!--                                    <a>-->
<!--                                        <span class="image"><img src="p_g_dash/production/images/img.jpg" alt="Profile Image" /></span>-->
<!--                                        <span>-->
<!--                          <span>John Smith</span>-->
<!--                          <span class="time">3 mins ago</span>-->
<!--                        </span>-->
<!--                                        <span class="message">-->
<!--                          Film festivals used to be do-or-die moments for movie makers. They were where...-->
<!--                        </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a>-->
<!--                                        <span class="image"><img src="p_g_dash/production/images/img.jpg" alt="Profile Image" /></span>-->
<!--                                        <span>-->
<!--                          <span>John Smith</span>-->
<!--                          <span class="time">3 mins ago</span>-->
<!--                        </span>-->
<!--                                        <span class="message">-->
<!--                          Film festivals used to be do-or-die moments for movie makers. They were where...-->
<!--                        </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a>-->
<!--                                        <span class="image"><img src="p_g_dash/production/images/img.jpg" alt="Profile Image" /></span>-->
<!--                                        <span>-->
<!--                          <span>John Smith</span>-->
<!--                          <span class="time">3 mins ago</span>-->
<!--                        </span>-->
<!--                                        <span class="message">-->
<!--                          Film festivals used to be do-or-die moments for movie makers. They were where...-->
<!--                        </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a>-->
<!--                                        <span class="image"><img src="p_g_dash/production/images/img.jpg" alt="Profile Image" /></span>-->
<!--                                        <span>-->
<!--                          <span>John Smith</span>-->
<!--                          <span class="time">3 mins ago</span>-->
<!--                        </span>-->
<!--                                        <span class="message">-->
<!--                          Film festivals used to be do-or-die moments for movie makers. They were where...-->
<!--                        </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <div class="text-center">-->
<!--                                        <a>-->
<!--                                            <strong>See All Alerts</strong>-->
<!--                                            <i class="fa fa-angle-right"></i>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </li>-->
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

<?php
/**
 * Created by PhpStorm.
 * Date: 2018-11-27
 * Time: 12:00 PM
 */

require_once "../Database.php";

session_start();

$str_today_with_time = date("Y-m-d H:i:s");
$place_holder_today = date("Ymd");
$today = date("Ymd");
$the_day_30days_before_today = date("Ymd", strtotime("-30 days"));
$the_day_1week_before_today = date("Ymd", strtotime("-7 days"));

$db = new Database();
$conn = $db->get_db_connection();


if(isset($_GET['radio_payment_method'])){
    $payment_method = ($_GET['radio_payment_method']=="cash")?"CASH":"CREDIT CARD";
}
if(isset($_GET['start_date'])){
    $start_date = $_GET['start_date'];
}
if(isset($_GET['end_date'])){
    $end_date = $_GET['end_date'];
}

if(isset($_GET['submit_total_sale'])){
    $submitted = 1;
//    $query = "SELECT SUM(subtotal+tax+delivery_fee)
//FROM orders as o
//WHERE timestamp>=UNIX_TIMESTAMP(STR_TO_DATE('".$start_date." 00:00:00', '%Y%m%d %H:%i:%s'))
//AND timestamp<=UNIX_TIMESTAMP(STR_TO_DATE('".$end_date." 00:00:00', '%Y%m%d %H:%i:%s'))
//AND payment_method='".$payment_method."';";
}
elseif(isset($_GET['submit_last_week'])){
    $start_date = $the_day_1week_before_today;
    $end_date = $today;
    $submitted = 1;
//    $query = "SELECT SUM(subtotal+tax+delivery_fee)
//FROM orders as o
//WHERE timestamp>=UNIX_TIMESTAMP(STR_TO_DATE('".$the_day_1week_before_today." 00:00:00', '%Y%m%d %H:%i:%s'))
//AND timestamp<=UNIX_TIMESTAMP(STR_TO_DATE('".$today." 00:00:00', '%Y%m%d %H:%i:%s'))
//AND payment_method='".$payment_method."';";
}
elseif(isset($_GET['submit_last_30days'])){
    $start_date = $the_day_30days_before_today;
    $end_date = $today;
    $submitted = 1;
//    $query = "SELECT SUM(subtotal+tax+delivery_fee)
//FROM orders as o
//WHERE timestamp>=UNIX_TIMESTAMP(STR_TO_DATE('".$the_day_30days_before_today." 00:00:00', '%Y%m%d %H:%i:%s'))
//AND timestamp<=UNIX_TIMESTAMP(STR_TO_DATE('".$today." 00:00:00', '%Y%m%d %H:%i:%s'))
//AND payment_method='".$payment_method."';";
}
try{
    if(isset($submitted)){
        $array_result_set = array();
        $datetime_start = new DateTime($start_date);
        $aaa = new DateTime($start_date);
        $datetime_end = new DateTime($end_date);
        $datetime_enter_minus_4 = new DateTime("20190310");
        $datetime_enter_minus_5 = new DateTime("20191103");
        $pointer_datetime = $datetime_start;
        while($pointer_datetime < $datetime_end){
            $pointer_datetime_plus1 = clone $pointer_datetime;
            $pointer_datetime_plus1->add(new DateInterval("P1D"));
            if($pointer_datetime>=$datetime_enter_minus_4 && $pointer_datetime<$datetime_enter_minus_5){
                $offset="-04:00";
            }
            else{
                $offset="-05:00";
            }
            $index_of_day_in_week = $pointer_datetime->format("N");
            if($index_of_day_in_week == "5" || $index_of_day_in_week == "6"){
                $query_kitchen_id_1 = "SELECT SUM(subtotal+tax+delivery_fee)
FROM orders as o
WHERE timestamp>=UNIX_TIMESTAMP(CONVERT_TZ(STR_TO_DATE('".$pointer_datetime->format("Ymd")." 00:00:00', '%Y%m%d %H:%i:%s'),'".$offset."','GMT'))
AND timestamp<=UNIX_TIMESTAMP(CONVERT_TZ(STR_TO_DATE('".$pointer_datetime_plus1->format("Ymd")." 01:00:00', '%Y%m%d %H:%i:%s'),'".$offset."','GMT'))
AND payment_method='".$payment_method."'
AND kitchen_id=1;";
            }
            else {
                $query_kitchen_id_1 = "SELECT SUM(subtotal+tax+delivery_fee)
FROM orders as o
WHERE timestamp>=UNIX_TIMESTAMP(CONVERT_TZ(STR_TO_DATE('".$pointer_datetime->format("Ymd")." 00:00:00', '%Y%m%d %H:%i:%s'),'".$offset."','GMT'))
AND timestamp<=UNIX_TIMESTAMP(CONVERT_TZ(STR_TO_DATE('".$pointer_datetime_plus1->format("Ymd")." 00:00:00', '%Y%m%d %H:%i:%s'),'".$offset."','GMT'))
AND payment_method='".$payment_method."'
AND kitchen_id=1;";
            }
            $query_kitchen_id_2 = "SELECT SUM(subtotal+tax+delivery_fee)
FROM orders as o
WHERE timestamp>=UNIX_TIMESTAMP(CONVERT_TZ(STR_TO_DATE('".$pointer_datetime->format("Ymd")." 00:00:00', '%Y%m%d %H:%i:%s'),'".$offset."','GMT'))
AND timestamp<=UNIX_TIMESTAMP(CONVERT_TZ(STR_TO_DATE('".$pointer_datetime_plus1->format("Ymd")." 00:00:00', '%Y%m%d %H:%i:%s'),'".$offset."','GMT'))
AND payment_method='".$payment_method."'
AND kitchen_id=2;";
//            $query = "SELECT SUM(subtotal+tax+delivery_fee)
//FROM orders as o
//WHERE timestamp>=UNIX_TIMESTAMP(STR_TO_DATE('".$pointer_datetime->format("Ymd")." 00:00:00', '%Y%m%d %H:%i:%s'))
//AND timestamp<=UNIX_TIMESTAMP(STR_TO_DATE('".$pointer_datetime_plus1->format("Ymd")." 00:00:00', '%Y%m%d %H:%i:%s'))
//AND payment_method='".$payment_method."';";
            $result_set = mysqli_query($conn,$query_kitchen_id_1) or die(mysql_error());
            $row = $result_set -> fetch_array();
            $total_kitchen_id_1 = $row[0];
            $result_set1 = mysqli_query($conn,$query_kitchen_id_2) or die(mysql_error());
            $row1 = $result_set1 -> fetch_array();
            $total_kitchen_id_2 = $row1[0];
            $array_result_set[] = array("date"=>$pointer_datetime->format('Y-m-d'),
                "total_kitchen_id_1"=>$total_kitchen_id_1,
                "total_kitchen_id_2"=>$total_kitchen_id_2);
//            ,
//            "query"=>$query_kitchen_id_1
//            $array_result_set[$pointer_datetime->format('Y-m-d')] = $row[0];
            $pointer_datetime->add(new DateInterval("P1D"));
        }
$a=1;

////	$query = "SELECT * FROM coupon ORDER BY time_generated DESC;";
//        $result_set = mysqli_query($conn,$query) or die(mysql_error());
//
////$sum = array();
//        $row = $result_set -> fetch_array();
//        $sum = $row[0];
//
//
//        $a=1;
    }
}catch (Exception $e){
    $a=1;
}

//if(isset($_POST['new_status'])){
//    $query1 = ' UPDATE coupon SET status="'.$_POST['new_status'].'" WHERE coupon_code="'.$_POST['coupon_code'].'"';
//
//    if (mysqli_query($conn, $query1)) {
//        echo "<script>alert('The status of this coupon has successfully updated to:  ".$_POST['new_status']." !');</script>" ;
//    } else {
//        echo "<script>console.log('Error updating record: ".mysqli_error($conn)."');</script>" ;
//    }
//
//}elseif(isset($_POST['new_type'])){
//    $query1 = ' UPDATE coupon SET `type`="'.$_POST['new_type'].'" WHERE coupon_code="'.$_POST['coupon_code'].'"';
//
//    if (mysqli_query($conn, $query1)) {
//        echo "<script>alert('The type of this coupon has successfully updated to:  ".$_POST['new_type']." !');</script>" ;
//    } else {
//        echo "<script>console.log('Error updating record: ".mysqli_error($conn)."');</script>" ;
//    }
//}
//
//if(isset($_GET['txt_coupon_code'])&&(($_GET['txt_coupon_code'])!="")){
//    $query = "SELECT * FROM coupon WHERE coupon_code='".$_GET['txt_coupon_code']."';";
//}
//else {
//    $query = "SELECT SUM(subtotal+tax+delivery_fee)
//FROM orders as o
//WHERE timestamp>=UNIX_TIMESTAMP(STR_TO_DATE('".$month." ".$day." ".$year." 00:00:00', '%m %d %Y %H:%i:%s'))
//
//AND payment_method='".$payment_method."';";
//}
//
//
//// First we will check the user name
////	$query = "SELECT * FROM coupon ORDER BY time_generated DESC;";
//$result_set = mysqli_query($conn,$query) or die(mysql_error());
//
//$coupons = array();
//while($row = $result_set -> fetch_assoc()){
//    $coupons[] = $row;
//}


$db->close_db_connection();

?>

<!DOCTYPE HTML>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accounting | Foodfun Utilities</title>

    <!-- Bootstrap v3.3.7 Stylesheet -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Google OpenFont -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <!-- FontAwesome v4.7.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

    <!-- Slick Slider CSS -->
<!--    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />-->
<!--    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />-->
    <!-- Custom Stylesheet -->
    <link href="../css/system_coupon_promo_backend.css" type="text/css" rel="stylesheet" />
</head>
<body class="fadein subpage">

<script>

    function edit_status(coupon_code,status) {
        let xmlhttp;
        let postdata = "coupon_code="+coupon_code+"&status="+status;
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
                document.getElementById("status_"+coupon_code).innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open("POST","ajax-coupon_promo_backend.php?",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.setRequestHeader("Content-length", postdata.length);
        xmlhttp.send(postdata);
    }

    function edit_type(coupon_code,type){
        let xmlhttp;
        let postdata = "coupon_code="+coupon_code+"&type="+type;
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
                document.getElementById("type_"+coupon_code).innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open("POST","ajax-coupon_promo_backend.php?",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.setRequestHeader("Content-length", postdata.length);
        xmlhttp.send(postdata);
    }

    function copyTotalsToClipboard(){
        
    }

</script>
<style>
    .margin-top-10px {
        margin-top: 10px;
        /*padding-bottom: 10px;*/
    }
</style>

<section id="content">
    <div class="container">

        <h3 style="margin-top: 10px">Get Total Sales per Day  <small>(Subtotal + Tax + Delivery fee)</small></h3>

        <form name="form_total_sale" method="get" id="form_total_sale" action="index.php">
            <?php echo <<<temp
            <table>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="radio" name="radio_payment_method" value="cash" id="radio_cash" checked
                               title=""/>
                        <label for="radio_cash"
                               title=""  class="margin-top-10px">Cash & Wechat</label>
                        <br/>
                        <input type="radio" name="radio_payment_method" value="credit_card" id="radio_credit_card" style=""
                               title=""/>
                        <label for="radio_credit_card"
                               title="">Credit card</label>
                    </td>
                </tr>
                <!--<tr>-->
                    <!--<td><br/></td>-->
                <!--</tr>-->
                <tr>
                    <td>
                        <label class="margin-top-10px">From: &nbsp;</label>
                    </td>
                    <td>
                        <input type="text" name="start_date" placeholder="$place_holder_today" value = ""
                                class="margin-top-10px"/>
                    </td>
                </tr>
                <!--<tr>-->
                    <!--<td><br/></td>-->
                <!--</tr>-->
                <tr>
                    <td>
                        <label class="margin-top-10px">To:</label>
                    </td>
                    <td>
                        <input type="text" name="end_date" placeholder="$place_holder_today" value = ""
                                class="margin-top-10px"/>
                    </td>
                </tr>
                <!--<tr>-->
                    <!--<td><br/></td>-->
                <!--</tr>-->
                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="submit" name="submit_total_sale" value="Submit" class="margin-top-10px">
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="submit" name="submit_last_week" value="Last Week" class="margin-top-10px">
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="submit" name="submit_last_30days" value="Last 30 days" class="margin-top-10px">
                    </td>
                </tr>
            </table>
temp;
?>
        </form>

        <hr>
        <h3>Search Result</h3>

        <table id="table_list_parts" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Date
                    </th>
                    <th>Total (Hamilton)
                    </th>
                    <th>Total (St.Catherine)
                </tr>
            </thead>
            <tbody>
            <?php
            if(isset($array_result_set)){
                foreach ($array_result_set as $row) {
                    echo <<<temp
                <tr>
                    <td>
                        $row[date]
                    </td>
                    <td>$row[total_kitchen_id_1]</td>
                    <td>$row[total_kitchen_id_2]</td>
                    
                </tr>
temp;

                }
            }
            ?><!--<td>$row[query]</td>-->
            </tbody>
        </table>
        <P>Note: The breakpoints for each day are set to 00:00 - 23:59. However for the Hamilton restaurant,
            on Friday and Saturday the closing time is set to 01:00 (next day).
            <br/>Below is the hours for both restaurant as a reference:</P>
        <P>St. Catharine：
            <br/>Monday to Friday 11:00-22:00
            <br/>Saturday, Sunday 15:00-21:00
<!--            <br/>-->
            <br/>Hamilton：
            <br/>Friday and Saturday 12:00-01:00(next day)
            <br/>Sunday to thursday 12:00-23:00</P>

<!--            <th>PartNumber-->
<!--                <a href="list.php?--><?php //echo $q_s_sort_part_num;?><!--" class="--><?php //echo $icon_class_sort_part_num;?><!-- href_sort"  aria-hidden="true">-->
<!--            </th>-->
<!--            <th>Category</th>-->
<!--            <th>InStock</th>-->
<!--            <th>warning</th>-->
<!--            <th>Location-->
<!--                <a href="list.php?--><?php //echo $q_s_sort_location;?><!--" class="--><?php //echo $icon_class_sort_location;?><!-- href_sort"  aria-hidden="true">-->
<!--            </th>-->
<!--            <th>Organizing201809-->
<!--                <a href="list.php?--><?php //echo $q_s_sort_organizing201809;?><!--" class="--><?php //echo $icon_class_sort_organizing201809;?><!-- href_sort"  aria-hidden="true">-->
<!--            </th>-->
<!--            <th>Action</th>-->



<!--        <div class="row">-->
<!--            <div class="col-sm-12">-->
<!--                <div class="text-center">-->
<!--                    <img src="../login/assets/images/logo.png" alt="AGT Electric Car Logo" />-->
<!--                    <span class="version">version: alpha 1.0.1</span>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <form name="form_search" method="get" action="" >-->
<!--            <div class="row form-group">-->
<!--                <div class="col-sm-1">-->
<!--                    <h4>Search:</h4>-->
<!--                </div>-->
<!--                <div class="col-sm-9">-->
<!--                    <input type="text" id="txt_coupon_code" name="txt_coupon_code" class="form-control" value="--><?php ///*echo $temp_key;*/ ?><!--" autocomplete="off" onkeyup=""-->
<!--                           placeholder="Enter a coupon code to see if it exists..."-->
<!--                    />-->
<!--                </div>-->
<!--                <div class="col-sm-2">-->
<!--                    <input type="submit" class="form-control" value="Search"/>-->
<!--                </div>-->
<!--            </div>-->
<!--        </form>-->
<!---->
<!--        <hr />-->
<!--        <div class="row">-->
<!--            <div class="col-lg-12">-->
<!--                <table class="table table-bordered table-striped" id="client-table">-->
<!--                    <tbody>-->
<!--                    <tr>-->
<!--                        <th>Coupon Code</th>-->
<!--                        <th>Email</th>-->
<!--                        <th>Status</th>-->
<!--                        <th>Type</th>-->
<!--                        <th>Time Generated</th>-->
<!--                        <th>Email Consent</th>-->
<!--                    </tr>-->
<!---->
<!--                    --><?php
//
//                    foreach($coupons as $coupon){
//
//                        $coupon_code = $coupon['coupon_code'];
//                        $email = $coupon['email'];
//                        $status = $coupon['status'];
//                        $type = $coupon['type'];
//                        $time_generated = $coupon['time_generated'];
//                        $consent = $coupon['email_consent'];
//                        if($consent == true){
//                            $consent_display = "<span><i class=\"fa fa-check fa-fw\"></i></span>";
//                        } else {
//                            $consent_display = "<span><i class=\"fa fa-times fa-fw\"></i></span>";
//                        }
//
//
//
//                        echo <<<TEMP
//									<tr>
//										<td>{$coupon_code}</td>
//										<td>{$email}</td>
//										<td id="status_{$coupon_code}"
//                                                ondblclick="edit_status('{$coupon_code}','{$status}')"
//                                                <!--onblur="changed()-->">
//                                            {$status}
//                                        </td>
//										<td id="type_{$coupon_code}" ondblclick="edit_type('{$coupon_code}','{$type}')">
//                                            {$type}
//                                        </td>
//										<td>{$time_generated}</td>
//										<td>{$consent_display}</td>
//									</tr>
//TEMP;
//                    }
//                    ?>
<!---->
<!---->
<!--                    </tbody>-->
<!--                </table>-->
<!--                <h4 id="lbl_msg" style="color: white;--><?php //echo (count($coupons)!=0)?'display:none':''?><!--">Zero result returned...</h4>-->
<!--                <p>-->
<!--                    Note: Double click on cells in "Status" column or "Type" column to activate editing.-->
<!--                </p>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</section>




<!-- Google jQuery v3.2.1 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Bootstrap v3.3.7 JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Slick JavaScript -->
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

</body>

</html>
<?php
//    session_start();
//    if (isset($_SESSION["nextFirst"]))
//       echo $_SESSION["nextFirst"];
    
    //$date=date_create();
    //echo date_format($date,"Y/m/d")."<br/>";
    //date_add($date,date_interval_create_from_date_string("1 days"));
    //echo date_format($date,"Y-m-d")."<br/>";
    //echo date_format($date,"w")."<br/>";
    //date_sub($date,date_interval_create_from_date_string("1 days"));
    //echo date_format($date,"Y-m-d")."<br/>";
    //echo date_format($date,"w")."<br/>";
//echo date_format($dateOfFirst,"y/m/d")."<br/>";
//print_r($arrayOfDates);
    
    
//    $nextFirst=date_format(
//                    date_add(
//                        date_create_from_format("Y/m/d", $arrayOfDates[4]), 
//                        date_interval_create_from_date_string("3 days")), 
//               "Y/m/d");
//    $_SESSION["nextFirst"] = $nextFirst;
//    
//    $previousLast=date_format(
//                    date_sub(
//                        date_create_from_format("Y/m/d", $arrayOfDates[0]), 
//                        date_interval_create_from_date_string("3 days")), 
//               "Y/m/d");
//    $_SESSION["previousLast"] = $previousLast;
//        window.close();


//            <tr>
//                <td>1</td>
//                <td><a href="bookingMeetingRoom2">F309</a></td>
//                <td>30</td>
//                <td>whiteboard</td>
//                <td>123-456-789</td>
//            </tr>
//            <tr>
//                <td>2</td>
//                <td><a href="bookingMeetingRoom2">E412</a></td>
//                <td>50</td>
//                <td>whiteboard, projector</td>
//                <td>123-456-789</td>
//            </tr>
//            <tr>
//                <td>3</td>
//                <td><a href="bookingMeetingRoom2">L119</a></td>
//                <td>100</td>
//                <td>whiteboard, projector, mic&amp;speaker</td>
//                <td>123-456-789</td>
//            </tr>
//            <tr>
//                <td>4</td>
//                <td><a href="bookingMeetingRoom2">J129</a></td>
//                <td>250</td>
//                <td>whiteboard, projector, mic&amp;speaker</td>
//                <td>123-456-789</td>
//            </tr>
//if (!isset($_SESSION['mr_id']))
//        echo "Connection timeout. Please go back to home page.";
//Route::get('roomPicker', function () {
//    return view('bookingMeetingRoom.roomPicker');
//});

if(!empty($mr_id))
        echo $mr_id;

//<input type="button" name="" id="" value="Book!" /></td>
//                    <td style="background-color: #DDDDDD"><input type="button" name="" id="" value="Book!" /></td>
//                    <td style="background-color: #DDDDDD"><input type="button" name="" id="" value="Book!" /></td>
//                    <td style="background-color: #DDDDDD"><a style="color:red" >Booked</a></td>
//                    <td style="background-color: #DDDDDD"><a style="color:red" >Booked</a></td>
//                    <td style="background-color: #DDDDDD"><a style="color:red" >Booked</a>


//    $today=date_create();
//    $today=date_format($today,"Y/m/d");
    $today=new DateTime();
    $today=date_format($today,"Y/m/d");
    $bookingBuffer=array();
    $ele1=["id"=>"1","date"=>"01/01"];
    $ele2=["id"=>"2","date"=>"01/02"];
    $bookingBuffer[0]=$ele1;
    $bookingBuffer[1]=$ele2;
    
    print_r($bookingBuffer);
    echo $bookingBuffer[1]["date"];
//    <input type=\"button\" "
//                                    . "name=\"\" id=\"$date1 1\" "
//                                        . "value=\"Book!\" "
//                                            . "onclick=\"selected('$date1 1')\"/>
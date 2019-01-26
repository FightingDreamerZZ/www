@extends('layouts.mainNoFooter')

@section('content')
<div id="main" > 
    <?php
    
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }  
    //daishan
        if(isset($_SESSION['bookingBuffer'])){
            print_r($_SESSION['bookingBuffer']);           
        }
    //echo $_SESSION['mr_id'];
    //echo $_SESSION['meetingRoomBookings'];   
        
    if (empty($mr_id)&&!isset($_SESSION['mr_id'])){
        echo "Connection timeout. Please go back to previous page.";       
        return;
    }
    else if(!isset($_SESSION['mr_id'])){
        $_SESSION['mr_id']=$mr_id;
    }
    else if(empty($mr_id)){
        $mr_id=$_SESSION['mr_id'];
    }
        
    
    if (empty($meetingRoomBookings)&&!isset($_SESSION['meetingRoomBookings'])){
        echo "Connection timeout. Please go back to previous page.";
        return;
    }
    else if(!isset($_SESSION['meetingRoomBookings'])){
        $_SESSION['meetingRoomBookings']=$meetingRoomBookings;        
    }
    else if(empty($meetingRoomBookings)){
        $meetingRoomBookings=$_SESSION['meetingRoomBookings'];
    }
    
    if(!isset($_SESSION['selectedCount'])){
        $selectedCount=0;
    }
    else
        $selectedCount=$_SESSION['selectedCount'];
            
    
    if (!empty($nextFirst)){
        $dateOfFirst = date_create_from_format("Y_m_d", $nextFirst);
    }
    elseif (!empty($previousFirst)) {
        $dateOfFirst = date_create_from_format("Y_m_d", $previousFirst);
    }
    else{
        $date=date_create();
        $dayOfWeek=  date_format($date, "w");
        $dateOfFirst=$date;
        date_sub($dateOfFirst, date_interval_create_from_date_string(($dayOfWeek-1)." days"));                
    }
    
    date_add($dateOfFirst, date_interval_create_from_date_string("7 days"));
    $nextFirst=  date_format($dateOfFirst, "Y_m_d");
    date_sub($dateOfFirst, date_interval_create_from_date_string("14 days"));
    $previousFirst=  date_format($dateOfFirst, "Y_m_d");
    date_add($dateOfFirst, date_interval_create_from_date_string("7 days"));
    $currentFirst=date_format($dateOfFirst, "Y_m_d");
    
    $arrayOfDates=array();
    for($i=0;$i<5;$i++){
        
        $arrayOfDates[$i]=date_format($dateOfFirst,"Y/m/d");
        date_add($dateOfFirst, date_interval_create_from_date_string("1 days"));
    }
    function preparePara($session,$currentFirst,$date1){
        
        $date1 = str_replace('/', '_', $date1);
        return $session.$currentFirst.$date1;
    }
?> 
    
<h1>Booking Meeting Room &nbsp;&nbsp;<small>Date and Time Selection</small></h1>
        <div >
        <span style="float:left"><a href="{{ url('/dateTimePicker', $previousFirst) }}">&lt;&lt;Previous Week</a></span>
        <!--<span style="margin:auto; padding-left:300px;"><a href="">Date Picker</a></span>-->
        <span style="float:right"><a href="{{ url('/dateTimePicker', $nextFirst) }}">Next Week&gt;&gt;</a></span>
        </div>
        <br />
        <br />
        <div id="displaytable">
        <form action="" method="post">
            <table  class="table table-hover">
                <thead>
                    <tr>
                    <th>Time</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    </tr>
                </thead>
                <tbody class="table-hover">
                <tr>
                    <td>&nbsp;</td>
                    <?php foreach($arrayOfDates as $date1):?>
                    <td><?php echo $date1?></td>
                    
                    <?php endforeach;?>
                </tr>
                <tr>
                    <td style="background-color: #DDDDDD">08:00-10:00</td>
                    @foreach($arrayOfDates as $date1)
                    <td style="background-color: #DDDDDD">
                        <?php  $para=preparePara(1, $currentFirst,$date1);
                        $para=url('/dateTimePickerSave', $para);
                        if(App\Http\Controllers\MeetingRoomController::
                                hasExpired($date1)){echo "<a style=\"color:red\" >Expired...</a>";}
                                else if(App\Http\Controllers\MeetingRoomController::
                                dateTimePickerCheckBooked($mr_id, $date1, "1")
                                ){echo "<a style=\"color:red\" >Booked</a>";}
                                else{echo "<a href=\"$para\">"
                                    . "<input type=\"button\" "
                                    . "name=\"\" id=\"$date1 1\" "
                                        . "value=\"Book!\" "
                                           . "onclick=\"selected('$date1 1')\"/>"
                                        . "</a>";}
                                            
                        ?>

                        <p style="color: green; display: none;" id="{{$date1}} 1txt">Selected</p>
                        
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <td>10:00-12:00</td>
                    @foreach($arrayOfDates as $date1)
                    <td>
                        <?php if(App\Http\Controllers\MeetingRoomController::
                                hasExpired($date1)){echo "<a style=\"color:red\" >Expired...</a>";}
                                else if(App\Http\Controllers\MeetingRoomController::
                                dateTimePickerCheckBooked($mr_id, $date1, "2")
                                ){echo "<a style=\"color:red\" >Booked</a>";}
                                else{echo "<input type=\"button\" "
                                    . "name=\"\" id=\"$date1 2\" "
                                        . "value=\"Book!\" "
                                            . "onclick=\"selected('$date1 2')\"/>";}
                        ?>
                        <p style="color: green; display: none;" id="{{$date1}} 2txt">Selected</p>
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <td style="background-color: #DDDDDD">13:00-15:00</td>
                    @foreach($arrayOfDates as $date1)
                    <td style="background-color: #DDDDDD">
                        <?php if(App\Http\Controllers\MeetingRoomController::
                                hasExpired($date1)){echo "<a style=\"color:red\" >Expired...</a>";}
                                else if(App\Http\Controllers\MeetingRoomController::
                                dateTimePickerCheckBooked($mr_id, $date1, "3")
                                ){echo "<a style=\"color:red\" >Booked</a>";}
                                else{echo "<input type=\"button\" "
                                    . "name=\"\" id=\"$date1 3\" "
                                        . "value=\"Book!\" "
                                            . "onclick=\"selected('$date1 3')\"/>";}
                        ?>
                        <p style="color: green; display: none;" id="{{$date1}} 3txt">Selected</p>
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <td>15:00-17:00</td>
                    @foreach($arrayOfDates as $date1)
                    <td>
                        <?php if(App\Http\Controllers\MeetingRoomController::
                                hasExpired($date1)){echo "<a style=\"color:red\" >Expired...</a>";}
                                else if(App\Http\Controllers\MeetingRoomController::
                                dateTimePickerCheckBooked($mr_id, $date1, "4")
                                ){echo "<a style=\"color:red\" >Booked</a>";}
                                else{echo "<input type=\"button\" "
                                    . "name=\"\" id=\"$date1 4\" "
                                        . "value=\"Book!\" "
                                            . "onclick=\"selected('$date1 4')\"/>";}
                        ?>
                        <p style="color: green; display: none;" id="{{$date1}} 4txt">Selected</p>
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <td class="auto-style1">&nbsp;</td>
                    <td class="auto-style1">&nbsp;</td>
                    <td class="auto-style1">&nbsp;</td>
                    <td class="auto-style1">&nbsp;</td>
                    <td class="auto-style1">&nbsp;</td>
                    <td class="auto-style1">&nbsp;</td>
                </tr>
                <tr>
                    <td class="auto-style1">&nbsp;</td>
                    <td class="auto-style1">&nbsp;</td>
                    <td class="auto-style1">&nbsp;</td>
                    <td class="auto-style1">&nbsp;</td>
                    <td class="auto-style1">&nbsp;</td>
                    <td class="auto-style1">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3">{{$selectedCount}} slot has been selected</td>
                    <td colspan="3" style="text-align:right;white-space:nowrap"><a href="{{url('/bookingDetailsIndex')}}" >Next Step&gt;&gt;</a></td>
                   
                </tr>
                </tbody>
            </table>
        </form>
        </div>
        
        
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#toggleForm").click(function(){
        $("#addNEditForm").toggle();
        
    });
});
function selected(id) {
    document.getElementById(id).style.display = 'none';
    document.getElementById(id+"txt").style.display = 'inline';
}
</script>
@endsection
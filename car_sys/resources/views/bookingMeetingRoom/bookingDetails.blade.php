@extends('layouts.main')

@section('content')
<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    if(!isset($_SESSION['bookingCP'])){
        $currentPage=1;        
    }
    else{
        $currentPage=$_SESSION['bookingCP'];
    }
    if(!isset($_SESSION['bookingTP'])){
        $totalPage=$bookingArrCount;       
    }
    else{
        $totalPage=$_SESSION['bookingTP'];
    }
    
    $bookingDataSet=$_SESSION['bookingBuffer'];
    
    \App\Http\Controllers\MeetingRoomController::SessVariIni('bookingCP', $currentPage);
    \App\Http\Controllers\MeetingRoomController::SessVariIni('bookingTP', $totalPage);
    
    
    if((int)$totalPage>=2){
        $nD=  \App\Http\Controllers\MeetingRoomController::InitOAlign('bookingND', ' ');//btnnextDisableAttri
    }
    else{$nD=  \App\Http\Controllers\MeetingRoomController::InitOAlign('bookingND', 'disabled');}
    
    $pD=  \App\Http\Controllers\MeetingRoomController::InitOAlign('bookingPD', 'disabled');
    $sD=' ';//btnSubmitDisableAttri
    \App\Http\Controllers\MeetingRoomController::SessVariIni('bookingPD',$pD);
    \App\Http\Controllers\MeetingRoomController::SessVariIni('bookingND',$nD);
    \App\Http\Controllers\MeetingRoomController::SessVariIni('bookingSD',$sD);
    
    echo $currentPage." ";
    echo $pD." ";
    echo $nD." ";
    echo $totalPage." ";
    $bookingDPrev=url('/bookingDPrev');
    $bookingDNext=url('/bookingDNext');
?>
<div id="main" >

<h1>Booking Meeting Room &nbsp;&nbsp;<small>Booking Details:</small></h1>
<div >
    <span style="float:left">
        <form method="get" action="<?php echo $bookingDPrev;?>">
            <input id='btnP' type='submit' value='&lt;&lt;Previous Slot' <?php echo $pD;?>/></form></span>
        
    <span style="float:right">
        <form method="get" action="<?php echo $bookingDNext;?>">    
                <input id='btnN' type='submit' value='Next Slot&gt;&gt;' <?php echo $nD;?>/></form></span>
</div><br />
<div id="searchform">        
        <form class="form-horizontal" method="get" action="mngMRsPopupSubmit">
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Room Number</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="room_number" id="mr_id" placeholder="" disabled="true">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Time</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="capacity" id="time_session" placeholder="" disabled="true">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Date</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="description" id="date" placeholder="" disabled="true">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Booked by</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="phone" id="empid" placeholder="" disabled="true">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Purpose Caption</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="phone" id="purpose_cap" placeholder="" >
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Purpose Description</label>
                <div class="col-xs-5">
                    <textarea class="form-control" name="phone" id="purpose_desc" placeholder="" ></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label for="btnInsert" class="control-label col-xs-4"></label>
                <div class="col-xs-5">
                    <input type="submit" class="btn btn-default btn-block" name="submit"value="Submit">            
                </div>
            </div>
            
            
        </form>
    </div>
  

</div>

@endsection
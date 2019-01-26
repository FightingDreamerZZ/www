@extends('layouts.main')

@section('content')
<?php

session_start();
if(!isset($_SESSION['empid'])){
        $_SESSION['empid']=1;
    }
    unset($_SESSION['bookingBuffer']);
    unset($_SESSION['bookingCP']);
    unset($_SESSION['bookingTP']);
    unset($_SESSION['bookingND']);
    unset($_SESSION['bookingPD']);
    ?>
<div id="main" >    
    <h1>Booking Meeting Room &nbsp;&nbsp;<small>Meeting Room Selection</small></h1>
    <div id="displaytable">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Room Number</th>                
                <th>Capacity</th>
                <th>Description</th>
                <th>Phone</th>
            </tr>
            </thead>
            <tbody class="table-hover">
            @foreach($meetingRooms as $meetingRoom)    
            <tr>
                <td>{{$meetingRoom->mr_id}}</td>
                <td><a href="{{ url('/dateTimePickerIndex', $meetingRoom->mr_id) }}">
                        {{$meetingRoom->room_number}}</a></td>
                <td>{{$meetingRoom->capacity}}</td>
                <td>{{$meetingRoom->description}}</td>
                <td>{{$meetingRoom->phone}}</td>
                
            </tr>
            @endforeach
            
            </tbody>
        </table>
    </div>
</div>

@endsection
<?php
    $date=date_format(date_create(),"l F jS Y");
    $userName='John Smith';
?>
@extends('layouts.mainNoFooter')

@section('content')
<div id="main" >    
    <h1>Announcements &nbsp;&nbsp;<small></small></h1>
    <div id="displaytable">
        @foreach($anncs as $annc)
        <table style="width:80%;">
        <tr>
            <td style="width:75%;"><h4>{{$annc->title}}</h4></td>
            <td style="width:15%;border-left:solid 1px gainsboro;padding-left:10px;"><small>Posted by: {{$annc->posted_by}}</small></td>
            <td><a href="{{ url('mngAnncEdit', $annc->annc_id) }}">EDIT</a></td>
            
        </tr>
        <tr>
            <td style="width:75%;"><small>Posted on: {{$annc->date}}<br /><br /></small></td>
            <td style="width:15%;border-left:solid 1px gainsboro;padding-left:10px;"><small>*posted to: ...</small></td>
            <td><a href="{{ url('mngAnncDelete', $annc->annc_id) }}">DELETE</a></td>
        </tr>
        <tr>
            <td style="width:75%;padding-right:20px;">{{$annc->content}}</td>
            <td style="width:15%;border-left:solid 1px gainsboro;">&nbsp;</td>
            
        </tr>
        </table>
        <hr />
        @endforeach      
    </div>
    <button id="toggleForm" class="btn btn-default" >Click here to add a new meeting room</button>
                    
        
    
    <div id="addNEditForm" style="display: none">
        <h2 id="empsearchtitle"><small>Insert/Update Announcements</small></h2>
        <form class="form-horizontal" method="get" action="mngAnncSubmit">
            <div class="form-group">
                <label for="" class="control-label col-xs-4">Date</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="date" id="date" 
                           placeholder="" readonly value="{{$date}}">
                </div>
            </div>
            <div class="form-group" style="display: none;">
                <label for="name" class="control-label col-xs-4">Posted by</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="posted_by" id="posted_by" 
                           placeholder="" readonly  value="1">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Posted by</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="" id="" 
                           placeholder="" diabled  value="{{$userName}}">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Title</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="title" id="title" placeholder="" >
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Content</label>
                <div class="col-xs-5">
                    <textarea class="form-control" rows="4"  name="content" id="content" ></textarea>
                    
                </div>
            </div>
            
            <div class="form-group">
                <label for="btnInsert" class="control-label col-xs-4"></label>
                <div class="col-xs-5">
                    <input type="submit" class="btn btn-default btn-block" name="submit"value="Submit">            
                </div>
            </div>
        </form>
    </div><br /><hr /><br />
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#toggleForm").click(function(){
        $("#addNEditForm").toggle();
    });
});   
</script>

@endsection
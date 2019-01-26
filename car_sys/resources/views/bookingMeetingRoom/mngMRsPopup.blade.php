@extends('layouts.popup')

@section('content')
<div id="searchform">
        <h2 id="empsearchtitle"><small>Insert/Update Meeting Room</small></h2>
        <form class="form-horizontal" method="get" action="mngMRsPopupSubmit">
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Room Number</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="room_number" id="room_number" placeholder="" >
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Capacity</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="capacity" id="capacity" placeholder="" >
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Description</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="description" id="description" placeholder="" >
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Phone</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="" >
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
    
    @endsection


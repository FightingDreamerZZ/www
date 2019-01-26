@extends('layouts.main')

@section('content')
    <div id="main" >
        <h1>Managing Parts &nbsp;&nbsp;<small></small></h1>
        <div id="displaytable">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Barcode</th>
                    <th>Name</th>
                    <th>Part Number</th>
                    <th>Quantity</th>
                    <th>Photo URL</th>
                    <th></th>
                    <th><img src="favicon-16x16.png"></th>
                </tr>
                </thead>
                <tbody class="table-hover">
                @foreach($allParts as $part)
                    <tr>
                        <td>{{$part->barcode}}</td>
                        <td>{{$part->name}}</td>
                        <td>{{$part->part_num}}</td>
                        <td>{{$part->quantity}}</td>
                        <td>{{$part->photo_url}}</td>
                        <td><a href="">EDIT</a></td>
                        <td><a href="{{ url('mngMRsDelete', $part->mr_id) }}">DELETE</a></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="7" style="text-align:center;white-space:nowrap">
                        <h4><a href="javascript:void(0);" NAME="My Window Name"  title=" My title here "
                               onClick=window.open("mngMRsPopup","Ratting","width=800,height=400,left=250,top=100,status=0,scrollbars=1");>
                                Click here to add a new meeting room
                            </a></h4>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!--    <div id="searchform">
                <h2 id="empsearchtitle"><small>Insert New Meeting Room</small></h2>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-4">Room Number</label>
                        <div class="col-xs-5">
                            <input type="text" class="form-control" id="room_number" placeholder="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-4">Capacity</label>
                        <div class="col-xs-5">
                            <input type="text" class="form-control" id="capacity" placeholder="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-4">Description</label>
                        <div class="col-xs-5">
                            <input type="text" class="form-control" id="description" placeholder="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-4">Phone</label>
                        <div class="col-xs-5">
                            <input type="text" class="form-control" id="phone" placeholder="" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="btnInsert" class="control-label col-xs-4"></label>
                        <div class="col-xs-5">
                            <button type="button" class="btn btn-default btn-block" NAME="My Window Name"  title=" My title here "
                                    onClick=window.open("mngMRsPopup","Ratting","width=800,height=400,left=250,top=100,status=0,scrollbars=1");>Insert</button>
                        </div>
                    </div>
                </form>
            </div><br /><hr /><br />-->
    </div>

@endsection


@extends('layouts.main')

@section('content')
<div id="main">
    <div id="searchform">
        <h2 id="empsearchtitle">Search  <small>Employee Detail</small></h2>
        <form class="form-horizontal">
            <div class="form-group">
                <label for="name" class="control-label col-xs-4">Employee Name</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" id="name" placeholder="Search By Name">
                </div>
            </div>
            <div class="form-group">
                <label for="empid" class="control-label col-xs-4">Employee ID</label>
                <div class="col-xs-5">
                <select class="form-control" id="empid">
                    <option>--Search By Id--</option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-xs-4">Email Address</label>
                <div class="col-xs-5">
                    <input type="text" class="form-control" id="email" placeholder="Search By Email">
                </div>
            </div>
             <div class="form-group">
                <label for="depid" class="control-label col-xs-4">Department ID</label>
                <div class="col-xs-5">
                <select class="form-control" id="depid">
                    <option>--Search By Department--</option>
                    <option>IT</option>
                    <option>Sales</option>
                    <option>Management</option>
                    <option>HR</option>
                </select>
                </div>
            </div>
            <div class="form-group">
                <label for="btnSearch" class="control-label col-xs-4"></label>
                <div class="col-xs-5">
                    <button type="button" class="btn btn-default btn-block" onclick="location.href='searchEmployeeResult'">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
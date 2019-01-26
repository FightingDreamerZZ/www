@extends('layouts.signin')

@section('content')

<div id="mainlogin"> 
    <form id="loginform" action="searchEmployee" class="form-horizontal">
        <div class="form-group">
            <label id="lblusername" for="username" class="control-label col-xs-3">User Name</label>
            <div class="col-xs-6">
                <input type="text" class="form-control" id="username" placeholder="Enter Username">
            </div>
        </div>
        <div class="form-group">
            <label id="lblpassword" for="password" class="control-label col-xs-3">Password</label>
            <div class="col-xs-6">
                <input type="password" class="form-control" id="inputPassword" placeholder="Enter Password">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="button" class="btn btn-link">Forget password</button>
                <button type="button" class="btn btn-link" onclick="location.href='signup'">Sign Up</button>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </div>
    </form>    
</div>
@endsection
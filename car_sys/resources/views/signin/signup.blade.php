@extends('layouts.signin')

@section('content')

<div id="mainsignup">
    <form id="signupform" class="form-horizontal" method="POST">
        <div class="form-group">
            <div class="col-xs-5 col-xs-offset-3">
                <h3 id="signupformtitle"> Register Form </h3>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4">First Name</label>
            <div class="col-xs-4">
                <input type="text" class="form-control" id="first_name" name="fname" placeholder="Enter First Name"
                       value="{!!Form::old('fname')!!}">
            </div>
            <span class="help-block">{!! $errors->first('fname') !!}</span>    
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4">Last Name</label>
            <div class="col-xs-4">
                <input type="text" class="form-control" id="last_name" name="lname" placeholder="Enter Last Name"
                       value="{!! Form::old('lname'); !!}">
            </div>
            <span class="help-block">{!! $errors->first('lname') !!}</span> 
        </div>
        <div class="form-group">
           <label class="control-label col-xs-4">Gender</label>
            <div class="col-xs-4">               
                <label class="radio-inline"><input type="radio" name="gender" value="Male" 
                    <?php if(Input::old('gender') == "Male") echo "checked"; ?> >Male</label>
                <label class="radio-inline"><input type="radio" name="gender" value="Female"
                    <?php if(Input::old('gender') == "Female") echo "checked"; ?> >Female</label>
            </div>
            <span class="help-block">{!! $errors->first('gender') !!}</span> 
        </div>
        <div class="form-group">
           <label for="dob" class="control-label col-xs-4">Date of Birth</label>
            <div class="col-xs-4">
                <div class="input-group date">
                    <input type="text" id="dobpicker" class="form-control" name="dob" 
                             value="{!! Form::old('dob'); !!}">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>     
            </div>
            <span class="help-block">{!! $errors->first('dob') !!}</span>
        </div>
        <div class="form-group">
           <label class="control-label col-xs-4">Department</label>
           <div class="col-xs-4">
                 <select class="form-control" name="department">
                     <option value="Select One" <?php if(Form::old('department') == "Select One") echo "selected"; ?>>Select One</option>
                     <?php foreach ($departmentlist as $depitem){ ?>
                        <option value="<?php echo $depitem; ?>" <?php if(Form::old('department') == $depitem) echo "selected"; ?> ><?php echo $depitem; ?></option>
                     <?php } ?>
                </select>
           </div>
           <span class="help-block">{!! $errors->first('department') !!}</span>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4">Phone</label>
            <div class="col-xs-4">
                <input type="text" class="form-control" name="phone" placeholder="Use xxx-xxx-xxxx format" value="{!! Form::old('phone'); !!}">
            </div>
            <span class="help-block">{!! $errors->first('phone') !!}</span>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4">Office</label>
            <div class="col-xs-4">
                 <select class="form-control" name="office">
                   <option value="Select One"   <?php if(Form::old('office') == "Select One") echo "Selected"; ?>>Select One</option>
                   <?php foreach ($officesites as $officeitem){ ?>
                   <option value="<?php echo $officeitem; ?>" <?php if(Illuminate\Support\Facades\Input::old('office') == $officeitem) echo "selected"; ?> ><?php echo $officeitem; ?></option>
                   <?php } ?>
                </select>
            </div>
            <span class="help-block">{!! $errors->first('office') !!}</span>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-4">Email</label>
            <div class="col-xs-4">
                <input type="email" class="form-control" name="email" value="{!! Form::old('email'); !!}">
            </div>
            <span class="help-block">{!! $errors->first('email') !!}</span>
        </div>
        <div class="form-group">
            <div id="btnRegister" class="col-xs-4 col-xs-offset-4">
                <button type="submit" name="action" class="btn btn-default" value="Register">Register</button>
                <button type="submit" name="action" class="btn btn-default" value="Cancel" style="float:right;">Cancel</button>
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>    
</div>

@endsection
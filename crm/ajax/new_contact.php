<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: cart.php
* This file handles clear() and proceed() request.
*/

//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

//echo $_GET["cid"];
//echo $_COOKIE['ec_user_name'];

?>

<div class="post-item">

<div id="newcus_form">

<form name="form_contact" method="post" enctype="multipart/form-data">
<input type="text" name="cid" style="display:none;" value="<?php echo $_GET["cid"]; ?>"/>
<label>Name:</label><input type="text" name="name_contact" class="input_field_w w280" value =""/><br />
<label>Title:</label><input type="text" name="title_contact" class="input_field_w w280" value =""/><br />
<label>Phone:</label><input type="text" name="phone_contact" class="input_field_w w280" value =""/><br />
<label>Email:</label><input type="text" name="email_contact" class="input_field_w w280" value =""/><br />
<input type="submit" name="submit_contact" class="submit_btn float_l" value="Submit"/>
&nbsp;&nbsp;<button type="button" class="submit_btn" onclick="close_add()">Close</button>
</form>
                
</div> 

</div>




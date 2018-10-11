<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: cart.php
* This file handles clear() and proceed() request.
*/

error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

include('..\lib\sql.php');
include('..\lib\user_lib.php');

check_user_cookie();

//echo $_GET["cid"];
//echo $_COOKIE['ec_user_name'];
//echo date("Y-m-d H:i:s");

?>

<div class="post-item">

<div id="newcus_form">

<form name="form" method="post" enctype="multipart/form-data">
<input type="text" name="cid" style="display:none;" value="<?php echo $_GET["cid"]; ?>"/>
<label>Title:</label><input type="text" name="title" class="input_field_w w280" value =""/><br /><br />
<label>Date:</label><input type="date" id="DateInputField" name="date" class="input_field_w w280" value ="<?php echo date("Y-m-d"); ?>"/><br /><br />
<label>Time:</label><input type="time" id="DateInputField" name="time" class="input_field_w w280" value ="<?php echo date("H:i"); ?>"/><br /><br />
<label>Location:</label><input type="text" name="location" class="input_field_w w280" value =""/><br /><br />
<label>Picture:</label><input type="file" name="file" ><br />
<br/><label>Content: </label><label id="d_counter">[0/60000]</label><br/><br/>
<textarea name="content" onkeyup="counter(this)" class="w500"></textarea><br/>
<input type="submit" name="submit" class="submit_btn float_l" value="Submit"/>
&nbsp;&nbsp;<button type="button" class="submit_btn" onclick="close_add()">Close</button>
</form>
                
</div> 

</div>



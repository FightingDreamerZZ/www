<?PHP
/*
* Copyright © 2013 Elaine CRM
* File: index.php
* This file display a user panel to access all frequently used functions.
*/
error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');
check_user_cookie();

$sql_code_info = "select * from `ec_company`";
$result_info = mysql_query($sql_code_info);
$com_check = mysql_fetch_array($result_info);

$sql_code_user = "select distinct `user` from `ec_commlog`";
$result_user = mysql_query($sql_code_user);


$load = " onload=\"load()\"";
include('header.php');
//print_r($_COOKIE);
?>
<script type="text/javascript">
function load_recent()
	{
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("load_customer").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/customer.php?get=recent",true);
	xmlhttp.send();
	}
	
	function load_potential()
	{
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("load_customer").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/customer.php?get=potential",true);
	xmlhttp.send();
	}
	
	function load_exsiting()
	{
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("load_customer").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/customer.php?get=existing",true);
	xmlhttp.send();
	}
	
	function load()
   {
	  load_recent();
   }
</script>
    
    <div id="service_bar">
	<a href="javascript:load_exsiting();">
    	<div class="col_3">
        	<h2>Existing Customer</h2>
            <p>Maintain excellent relationships with existing customers.</p>
        </div>
	</a>
	<a href="javascript:load_potential();">
        <div class="col_3" onclick="load_potential()">
        	<h2>Potential Customer</h2>
            <p> Any person or business that may be interested in your product. </p>
        </div>
	</a>
		<a href ="new_customer.php">
        <div class="col_3 no_mrib">
	        <h2>New Customer Profile</h2>
            <p>Create a new customer profile with related information.</p>
        </div>    
		</a>
    </div> <!-- END of service bar -->
    
    <div id="main"><span class="mf mft"></span><span class="mf mfb"></span>
    	<div id="load_customer">
       
		</div>
        
		
        <div class="clear"></div>
    </div> <!-- END of main -->
    
    <div id="bottom"><span class="bf bft"></span><span class="bf bfb"></span>
    	<div class="col col_3">
        	<h4>Links</h4>
            <ul class="nobullet social">
            	<li><a href="https://www.facebook.com/" class="facebook" target="_blank">Facebook</a></li>
                <li><a href="https://www.twitter.com/" class="twitter" target="_blank">Twitter</a></li>
                <li><a href="http://www.weibo.com/" class="weibo" target="_blank">Weibo</a></li>
                <li><a href="https://plus.google.com/" class="google" target="_blank">Google+</a></li>
                <li><a href="https://www.linkedin.com/nhome/" class="linkedin" target="_blank">Linked In</a></li>
                <li><a href="https://www.myspace.com/" class="myspace" target="_blank">My Space</a></li>
                <li><a href="../index.php" class="eware">Warehouse</a></li>
                <li><a href="../account/index.php" class="eacc">Account</a></li>
            </ul>
        </div>
        <div class="col col_3">
        	<h4>Overview</h4>
      <ul class="nobullet twitter">
                <li><a href="calendar.php?display=all">[Company Calendar]</a> -Displays Everyone's Events on one Calendar.</li>
                <li><a href="commlog.php?display=all">[Recent Commlogs]</a> -Displays Everyone's Recent Commlogs for All Customers.</li>
                <li><span style="color: #e38d00; text-decoration: none">[Commlog Lookup]</span>
				<p><form name="form" method="get" action="commlog.php">
				<select name="display_user" class="select_field w180">				
				<?php while ($log_row = mysql_fetch_assoc($result_user)) { ?> 
				<option value="<?php echo $log_row["user"]; ?>")><?php echo $log_row["user"]; ?></option>
				<?php }; ?> 
				</select><br />
				<input type="submit" class="submit_btn float_l" value="Commlog Lookup"/><br />
				</form></p></li>
            </ul>
        </div>
        <div class="col col_3">
			<h4>About</h4>
			<img src="<?php echo($com_check['logo_url']); ?>"/><br /><br />
        	<h5><?php echo($com_check['name']); ?></h5>

			<p><?php echo($com_check['address1']); ?><br />
                <?php echo($com_check['address2']); ?><br /></p>
                <p>
                <strong>Tax #:</strong> <?php echo($com_check['tax_number']); ?><br />
                <strong>Phone:</strong> <?php echo($com_check['phone']); ?><br />
                <strong>Fax:</strong> <?php echo($com_check['fax']); ?><br />
                <strong>Email:</strong> <a href="mailto:<?php echo($com_check['email']); ?>"><?php echo($com_check['email']); ?> </a><br />
                <strong>Web:</strong> <a href="<?php echo($com_check['web']); ?>"><?php echo($com_check['web']); ?> </a>
				</p>
            
        </div>
        
        <div class="clear"></div>
        
    </div> <!-- END of bottom -->
    

<?PHP
include('footer.php');
?>
<?PHP
/*
* Copyright © 2013 Elaine CRM
* File: index.php
* This file display a user panel to access all frequently used functions.
*/
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('lib/sql.php');
include('lib/user_lib.php');
check_user_cookie();

//$load = " onload=\"load()\"";
include('header.php');

?>
<script type="text/javascript">
</script>

    
    <div id="main"><span class="mf mft"></span><span class="mf mfb"></span>
    	<div id="load_customer">
       
		</div>
        
		
        <div class="clear"></div>
    </div> <!-- END of main -->
    
 
    

<?PHP
include('footer.php');
?>
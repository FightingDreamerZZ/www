<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: login.php
* This file provides a login portal for user.
*/
//zz haha1
error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');
$path1 = 'lib\sql.php';
echo "$path1";
$user = posix_getpwuid(posix_geteuid());

//var_dump($user);
//phpinfo();
echo ini_get("allow_url_include");
//var_dump(LIB_PATH.DS.'lib\user_lib.php');
//handle login request
if($_GET['do']=='login'){
	$user = $_POST["user"];
	$pass = $_POST["pass"];
	$sql_code = "select * from ew_user where user = '".$user."';";
	$result_info = mysql_query($sql_code);
	$a_check = mysql_fetch_array($result_info);
	$ew_verified = $a_check['pass'];
	if($ew_verified == $pass){
		//super user
		if($a_check['type'] == 1){
			setcookie('ew_user_name',$user,time()+7200);
			setcookie('ew_user_verified',$ew_verified,time()+7200);
			setcookie('ea_user_name',$user,time()+7200);
			setcookie('ea_user_verified',$ew_verified,time()+7200);
			setcookie('ec_user_name',$user,time()+7200);
			setcookie('ec_user_verified',$ew_verified,time()+7200);
			sys_log($user,"login to the system.");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}
		//warehouse user
		if($a_check['type'] == 2){
			setcookie('ew_user_name',$user,time()+7200);
			setcookie('ew_user_verified',$ew_verified,time()+7200);
			sys_log($user,"login to the system.");
			die('<meta http-equiv="refresh" content="0;URL=index.php">');
		}
		
		//account user
		if($a_check['type'] == 3){
			setcookie('ea_user_name',$user,time()+7200);
			setcookie('ea_user_verified',$ew_verified,time()+7200);
			sys_log($user,"login to the system.");
			die('<meta http-equiv="refresh" content="0;URL=account/index.php">');
		}
		
		//CRM user
		if($a_check['type'] == 4){
			setcookie('ec_user_name',$user,time()+7200);
			setcookie('ec_user_verified',$ew_verified,time()+7200);
			sys_log($user,"login to the system.");
			die('<meta http-equiv="refresh" content="0;URL=crm/index.php">');
		}
		
	}else{
	echo("<script>window.alert('Verification Failed!');</script>");
	die('<meta http-equiv="refresh" content="0;URL=login.php">');
	}
	
}

if($_COOKIE['ew_user_name'] || $_COOKIE['ew_user_verified']){
		die('<meta http-equiv="refresh" content="0;URL=index.php">');
	}


$title_by_page = "Login";
include('header.php');

//print_r($_COOKIE);
?>


    
     <div id="main">
     
        <div class="content_box_top"></div>
        <div class="content_box">
            <h2>System Login</h2>
            <div class="cleaner"></div>
            <p>Please Login to continue. You should be able to obtain an account from your supervisor. For further inquiries, please contact AGT electric Cars. If you are the administrator, please <a href="admin/" target="_blank">click here</a> to enter Admin Panel.
			
			
			</p>
			
            <div class="cleaner h30"></div>
            <div class="col_w320 float_l">
                <h4>Account Login</h4>
				<br />
                <div id="contact_form">
                    <form name="form" method="post" action="login.php?do=login">
                        
                            <label for="author">Name:</label> <input type="text" name="user" class="input_field"/>
                            <div class="cleaner h10"></div>
                                                        
                            <label for="email">Password:</label> <input type="password" name="pass" class="input_field"/>
                            <div class="cleaner h10"></div>		
                                                    
                            <input type="submit" value="Login" id="submit" name="submit" class="submit_btn float_l" />
                            <input type="reset" value="Reset" id="reset" name="reset" class="submit_btn float_r" />
                            
                    </form> 
                
                </div> 
            </div>
            
            <div class="col_w320 float_r">              
                
                <div id="map"><img src="images/companylogo.png"/></div>    
                                  
                <div class="cleaner h20"></div>
                
                <h4>AGT Electric Cars</h4>
                1185 Corporate Drive, Unit #2 <br />
                Burlington, Ontario, Canada L7L 5V5<br /><br />
                
                <strong>Phone:</strong> 1-905-331-0491<br />
                <strong>Fax:</strong> 1-905-331-3504<br />
                <strong>Email:</strong> <a href="mailto:info@agtecars.com">info@agtecars.com </a>
            </div>    	
            
            <div class="cleaner h30"></div>
            <div class="cleaner"></div>
            <div class="cleaner"></div>
        </div> <!-- end of a content box -->
        <div class="content_box_bottom"></div>
        
	</div> <!-- end of main -->
    
<?PHP
include('footer.php');
?>

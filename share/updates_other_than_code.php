<?php
/**
 * Created by PhpStorm.
 * Date: 2018-12-05
 * Time: 9:59 AM
 *
 * updates_other_than_code.php: mainly modifications on configurations
 */
?>

<!--php upload file size 2MB limit:-->
<!--not only change the $_FILES["file"]["size"] < 10000000 code-->
<!--but also change these 2 lines in php.ini:-->
; Maximum allowed size for uploaded files.
upload_max_filesize = 40M

; Must be greater than or equal to upload_max_filesize
post_max_size = 40M



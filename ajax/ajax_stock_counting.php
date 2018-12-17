<?php
/**
 * Created by PhpStorm.
 * Date: 2018-12-15
 * Time: 6:54 PM
 */
if(isset($_POST['action'])){
    switch ($_POST['action']) {
        case "add_new_c_evnt":
            $result["html_code"] = "
                <form method=\"get\">
                    <input type=\"text\" name=\"txt_new_c_event\" placeholder=\"Enter a new counting event name...\"/>
                    <input type=\"submit\" style=\"\" name=\"submit_new_c_event\" value=\"Submit\"/>
                </form>
            ";
            echo json_encode($result);
            exit;
            break;
    }
}
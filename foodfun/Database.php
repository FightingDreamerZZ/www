<?php
/**
 * Created by PhpStorm.
 * Date: 2018-11-27
 * Time: 5:23 PM
 */

class Database
{
    public $conn;

    public function __construct()
    {
        // Open connection to database
        $servername = "foodfun-production.cuysrwg9ill4.ca-central-1.rds.amazonaws.com";
        $username = "stats";
        $password = "foodfun123";
        $dbname = "foodfun";

        // Create connection
        $this->conn = new mysqli($servername,$username,$password,$dbname);
        // Check connection
        if($this->conn -> connect_error){
            $result['message'] = "There was an error while submitting your message, please try again soon. If this proble persists, please contact us via 1-905-597-6227.";
            $result['type'] = "error";

            echo json_encode($result);
            exit();
        }
    }

    public function get_db_connection(){
        return $this->conn;
    }
    public function close_db_connection(){
        mysqli_close($this->conn);
    }

}
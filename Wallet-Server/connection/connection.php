<?php
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = null;
    $db_name = "digital_wallet";
    $con = new mysqli($db_host,$db_user,$db_pass,$db_name);
    if($con->connect_error){
        die("fail to connect to database");
    }
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods:*");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

?>
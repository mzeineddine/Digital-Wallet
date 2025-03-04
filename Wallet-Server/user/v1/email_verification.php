<?php
    include("../../connection/connection.php");
    include("../../models/user.php");
    include("../../utils.php");
    if($con->connect_error){
        return;
    }
    $response=[];
    if(isset($_GET['id'])&&$_GET['id']!=""){
        if(user::update_user_validation(0,isset($_GET['id']))){
            echo json_encode(["result"=>""]);
            echo json_encode(["message"=>"Email is Verified"]);
            return;
        }
    }
?>
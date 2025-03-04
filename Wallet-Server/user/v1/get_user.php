<?php
    include("../../models/user.php");
    include("../../connection/connection.php");
    include("../../utils.php");
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }
    if(data_utils::missing_parm(1,$data, ["id"])){
        $user = user::get_user_by_id($data["id"]);
        if($user){
            echo json_encode(["result"=>$user]);
            echo json_encode(["message"=>"user found"]);
            return;
        }else{
            echo json_encode(["result"=>""]);
            echo json_encode(["message"=>"user not found"]);
            return;
        }
    }
?>
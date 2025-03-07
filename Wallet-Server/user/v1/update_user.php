<?php
include("../../models/user.php");
include("../../connection/connection.php");
include("../../utils.php");

if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $data = json_decode(file_get_contents('php://input'), true);
} else {
    $data = $_POST;
}
if(data_utils::missing_parm(4,$data, ["phone_nb", "full_name","address", "id"])){
    $full_name = $data['full_name'];
    $pass = $data['pass'];
    $phone_nb = $data['phone_nb'];
    $address = $data['address'];
    $id = $data['id'];
    $pass = hash("sha3-256", $pass);
    if(isset($data['pass']) && $data['pass'] != ""){
        if(user::update_user_with_pass($full_name, $pass, $phone_nb,$address,$id,$con)){
            echo json_encode(["message"=>"User Updated"]);
            echo json_encode(["result"=>"true"]);
            return;
        }else{
            echo json_encode(["message"=>"User Not Updated"]);
            echo json_encode(["result"=>"false"]);
            return;
        }
    } else{
        if(user::update_user_without_pass($full_name, $phone_nb,$address,$id,$con)){
            echo json_encode(["message"=>"User Updated"]);
            echo json_encode(["result"=>"true"]);
            return;
        } else{
            echo json_encode(["message"=>"User Not Updated"]);
            echo json_encode(["result"=>"false"]);
            return;
        }
    }
}
?>
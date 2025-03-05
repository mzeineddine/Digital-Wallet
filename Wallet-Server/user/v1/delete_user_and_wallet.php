<?php
    include("../../connection/connection.php");
    include("../../models/user.php");
    include("../../models/wallet.php");
    include("../../utils.php");
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    if(data_utils::missing_parm(1,$data, ["id"])){
        $wallet = wallet::delete_wallet($data["id"],$con);
        if($wallet){
            $user = user::delete_user($data["id"],$con);
            if($user){
                echo json_encode(["result"=>true]);
                echo json_encode(["message"=>"wallet and user deleted"]);
                return;
            }else{
                echo json_encode(["result"=>false]);
                echo json_encode(["message"=>"wallet deleted but user not deleted"]);
                return;
            }
        }else{
            echo json_encode(["result"=>false]);
            echo json_encode(["message"=>"user and wallet not deleted"]);
            return;
        }
    }


        

?>
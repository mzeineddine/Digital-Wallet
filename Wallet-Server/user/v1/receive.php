<?php
    include("../../models/wallet.php");
    include("../../connection/connection.php");
    include("../../utils.php");
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    if(data_utils::missing_parm(2,$data, ["id","transaction_code"])){
        $user_wallet = wallet::get_wallet_by_id($data["id"],$con);
        if($user_wallet){
            if(wallet::update_wallet_transaction_code( $user_wallet->id, $data["transaction_code"],$con)){
                echo json_encode(["result"=>true]);
                echo json_encode(["message"=>"transaction code is updated"]);
                return;
            }
            echo json_encode(["result"=>false]);
            echo json_encode(["message"=>"transaction code is not updated"]);
            return;
        }
    }
?>
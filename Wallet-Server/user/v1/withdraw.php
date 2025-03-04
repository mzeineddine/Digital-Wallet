<?php
    include("../../models/wallet.php");
    include("../../connection/connection.php");
    include("../../utils.php");
    if($con->connect_error){
        return;
    }
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }
    if(data_utils::missing_parm(2,$data, ["id","amount"])){
        // $user_wallet= get_wallet($con,$data);
        $user_wallet = wallet::get_wallet_by_id($data["id"]);
        if($user_wallet){   
            if(wallet::update_wallet_balance($user_wallet->id, $balance)){    
                $balance = (double)($user_wallet->balance);
                $balance -= (double)($data["amount"]);
                if(wallet::update_wallet_balance($user_wallet->id, $balance)){
                    echo json_encode(["result"=>true]);
                    echo json_encode(["message"=>"send successfully"]);
                    return;
                }
            }else{
                echo json_encode(["result"=>false]);
                echo json_encode(["message"=>"fail to send"]);
                return;
            }
        }
    }
?>
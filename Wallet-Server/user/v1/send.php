<?php
    include("../../models/wallet.php");
    include("../../utils.php");

    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    if(data_utils::missing_parm(3,$data, ["id","transaction_code","amount"])){
        $user_wallet = wallet::get_wallet_by_id($data['id']);
        $receiver_wallet = wallet::get_wallet_by_transaction_code($data["transaction_code"]);
        $balance = (double)($user_wallet->balance);
        $balance -= (double)($data["amount"]);
        if($balance<0){
            echo json_encode(["result"=>false]);
            echo json_encode(["message"=>"Insufficient amount in balance"]);
            return;
        } else{
            if($receiver_wallet){   
                if(wallet::update_wallet_balance($user_wallet->id, $balance)){    
                    $balance = (double)($receiver_wallet->balance);
                    $balance += (double)($data["amount"]);
                    if(wallet::update_wallet_balance($receiver_wallet->id, $balance)){
                        echo json_encode(["result"=>true]);
                        echo json_encode(["message"=>"send successfully"]);
                        return;
                    }
                }else{
                    echo json_encode(["result"=>false]);
                    echo json_encode(["message"=>"fail to send"]);
                    return;
                }
            } else{
                echo json_encode(["result"=>false]);
                echo json_encode(["message"=>"invalid code"]);
                return;
            }
        }
    }
?>
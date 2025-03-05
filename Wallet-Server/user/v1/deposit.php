<?php
    include("../../models/wallet.php");
    include("../../connection/connection.php");
    include("../../utils.php");
    $base = "13.38.107.39";

    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    if(data_utils::missing_parm(2,$data, ["id","amount"])){
        $user_wallet = wallet::get_wallet_by_id($data["id"],$con);
        if($user_wallet){   
            $balance = (double)($user_wallet->balance);
            $balance += (double)($data["amount"]);
            if(wallet::update_wallet_balance($user_wallet->id, $balance,$con)){ 
                // calling add_transaction api "sender_id","receiver_id","amount"
                $post_data=array("sender_id"=> "0","receiver_id" => $data["id"], "amount"=>$data["amount"]);
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($post_data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
                curl_setopt($curl, CURLOPT_URL, $base."Digital-Wallet/Wallet-Server/user/v1/add_transaction.php");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($curl);
                // end calling api   
                    echo json_encode(["result"=>true]);
                    echo json_encode(["message"=>"deposit succeed"]);
                    return;
            }else{
                echo json_encode(["result"=>false]);
                echo json_encode(["message"=>"deposit failed"]);
                return;
            }
        }
    }
?>
<?php
    include("../../connection/connection.php");
    include("../../models/wallet.php");
    include("../../models/user.php");
    include("../../utils.php");
    $base = "http://13.38.107.39/";

    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    if(data_utils::missing_parm(3,$data, ["id","receiver_email","amount"])){
        $receiver_id = user::get_user_id_by_email($data["receiver_email"],$con);
        $user_wallet = wallet::get_wallet_by_id($data['id'],$con);
        $receiver_wallet = wallet::get_wallet_by_id($receiver_id,$con);
        $balance = (double)($user_wallet->balance);
        $balance -= (double)($data["amount"]);
        if($balance<0){
            echo json_encode(["result"=>false]);
            echo json_encode(["message"=>"Insufficient amount in balance"]);
            return;
        } else{
            if($receiver_wallet){   
                if(wallet::update_wallet_balance($user_wallet->id, $balance,$con)){    
                    $balance = (double)($receiver_wallet->balance);
                    $balance += (double)($data["amount"]);
                    if(wallet::update_wallet_balance($receiver_wallet->id, $balance,$con)){
                $url = $base."Digital-Wallet/Wallet-Server/user/v1/add_transaction.php";
                $data = ["sender_id" => $user_wallet->user_id, "receiver_id"=> $receiver_wallet->user_id, "amount"=>$data["amount"]];
                $options = [
                    'http' => [
                        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method' => 'POST',
                        'content' => http_build_query($data),
                    ],
                ];
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                if ($result === false) {
                    /* Handle error */
                    echo json_encode(["result"=>false]);
                    echo json_encode(["message"=>"something went wrong"]);
                    return;
                }
                
                // end calling api
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
                echo json_encode(["message"=>"can not found receiver"]);
                return;
            }
        }
    }
?>
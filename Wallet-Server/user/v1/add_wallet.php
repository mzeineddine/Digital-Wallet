<?php
    include("../../connection/connection.php");
    include("../../utils.php");
    include("../../models/wallet.php");
    $base1 = "http://localhost/Projects";
    $base = $base1."/Digital-Wallet/Wallet-Server/user/v1";
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    if(data_utils::missing_parm(1,$data, ["id"])){
        $wallet = wallet::create_wallet($data["id"]);
        if($wallet){
            echo json_encode(["result"=>$wallet]);
            echo json_encode(["message"=>"wallet created"]);
            return;
        } else{
            echo json_encode(["result"=>""]);
            echo json_encode(["message"=>"wallet not created"]);
            return;
        }
    }
?>
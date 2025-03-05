<?php
    include("../../connection/connection.php");
    include("../../utils.php");
    include("../../models/wallet.php");
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    if(data_utils::missing_parm(2,$data, ["id","transaction_code"])){
        $wallet = wallet::create_wallet($data["id"], $data["transaction_code"],$con);
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
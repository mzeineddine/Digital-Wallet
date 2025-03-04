<?php
    include("../../connection/connection.php");
    include("../../models/wallet.php");
    include("../../utils.php");
    if($con->connect_error){
        return;
    }
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }
    if(data_utils::missing_parm(1,$data, ["id"])){
        $id=$data['id'];
        $wallet = wallet::get_wallet_by_id($id);
        if($wallet){
            echo json_encode(["result"=>$wallet]);
            echo json_encode(["message"=>"Wallet found"]);
            return;
        } else{
            echo json_encode(["result"=>""]);
            echo json_encode(["message"=>"Wallet not found add one found"]);
            return;
        }
    }
?>
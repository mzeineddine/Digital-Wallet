<?php
    include("../../connection/connection.php");
    include("../../models/transaction.php");
    include("../../utils.php");
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }
    if(data_utils::missing_parm(3,$data, ["sender_id","receiver_id","amount"])){
        $time = date("Y-m-d H:i:s");
        $transaction = transaction::create_transaction($data["sender_id"],$data["receiver_id"],$time,$data["amount"]);
        if($transaction){
            echo json_encode(["result"=>$transaction]);
            echo json_encode(["message"=>"Transaction Created"]);
            return;
        } else{
            echo json_encode(["result"=>""]);
            echo json_encode(["message"=>"Transaction Not Created"]);
            return;
        }
    }
?>
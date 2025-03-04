<?php
    include("../../connection/connection.php");
    include("../../models/transaction.php");
    include("../../utils.php");
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }
    if(data_utils::missing_parm(1,$data, ["id"])){
        $time = date("Y-m-d H:i:s");
        $transactions = transaction::get_transactions_by_user_id_sorted_by_time($data["id"],);
        $result = [];
        foreach($transactions as $tran){
            $result[]=$tran;
        }
        if(sizeof($result)>0){
            echo json_encode(["result"=>$transactions]);
            echo json_encode(["message"=>"Transactions Found"]);
            return;
        } else{
            echo json_encode(["result"=>""]);
            echo json_encode(["message"=>"No Transactions Found"]);
            return;
        }

    }
?>
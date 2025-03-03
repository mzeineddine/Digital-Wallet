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
    if(data_utils::missing_parm(1,$data, ["id"])){
        $id=$data['id'];
        $query = $con->prepare("SELECT * FROM wallets WHERE user_id=?");
        if(sql_utils::query_execution($query,"i", [$id])){
            $result = $query->get_result();
            $wallet=new wallet(-1 ,-1,-1 );
            while($wallet_db = mysqli_fetch_assoc($result)){
                $wallet = new wallet($wallet_db["id"], $wallet_db["user_id"],$wallet_db["balance"], $wallet_db["transaction_code"]);
            }
            if($wallet->id!=-1){
                echo json_encode($wallet);
                return;
            }else{
                echo json_encode(["message"=>"no wallet fount"]);
                return;
            }
        }
    }
?>
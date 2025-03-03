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

    function get_wallet($con,$data){
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
                    return $wallet;
                }else{
                    return false;
                }
            }
        }
    }

    if(data_utils::missing_parm(2,$data, ["id","amount"])){
        // $user_wallet= get_wallet($con,$data);
        $user_wallet = get_wallet($con,$data);
        $balance = (double)($user_wallet->balance);
        $balance -= (double)($data["amount"]);
        if($balance<0){
            echo "false";
            return json_encode(["result"=>false]);
        } else{$query = $con->prepare("UPDATE wallets SET `balance` = ? WHERE id = ?;");
            if(sql_utils::query_execution($query,"di", [$balance, $user_wallet->id])){
                $affectedRows = $query->affected_rows;
                if ($affectedRows > 0) {
                    echo "Successfully updated $affectedRows row(s).";
                    //add to transaction history
                    return json_encode(["result"=>true]);;
                } else {
                    echo "No rows were updated.";
                    return json_encode(["result"=>true]);
                }
            }
        }
    }
?>
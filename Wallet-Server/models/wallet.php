<?php
    class wallet{
        public $id;
        public $user_id;
        public $balance;
        public $transaction_code;
        function __construct($id, $user_id, $balance=0, $transaction_code=null){
            $this->id=$id;
            $this->user_id=$user_id;
            $this->balance=$balance;
            $this->transaction_code=$transaction_code;
        }

        static function create_wallet($id, $code,$con){
            $query = $con->prepare("INSERT INTO wallets (user_id, transaction_code) VALUES (?,?);");
            if(sql_utils::query_execution($query,"id", [$id, $code])){
                $wallet_id = $con->insert_id;
                $wallet = new wallet($wallet_id,$id, 0);
                return $wallet;
            }
        }

        static function get_wallet_by_id($id,$con){
            $query = $con->prepare("SELECT * FROM wallets WHERE user_id=?;");
            if(sql_utils::query_execution($query,"i", [$id])){
                $result = $query->get_result();
                $wallet=null;
                while($wallet_db = mysqli_fetch_assoc($result)){
                    $wallet = new wallet($wallet_db["id"], $wallet_db["user_id"],$wallet_db["balance"], $wallet_db["transaction_code"]);
                }
                return $wallet;
            }
            return false;
        }

        static function get_wallet_by_transaction_code($transaction_code, $con){
            $query = $con->prepare("SELECT * FROM wallets WHERE transaction_code=?;");
            if(sql_utils::query_execution($query,"s", [$transaction_code])){
                $result = $query->get_result();
                $wallet=new wallet(-1 ,-1,-1 );
                while($wallet_db = mysqli_fetch_assoc($result)){
                    $wallet = new wallet($wallet_db["id"], $wallet_db["user_id"],$wallet_db["balance"], $wallet_db["transaction_code"]);
                }
                if($wallet->id!=-1){
                    return $wallet;
                } return null;
            }
        }

        static function update_wallet_balance($id,$balance,$con){
            $query = $con->prepare("UPDATE wallets SET `balance` = ? WHERE id = ?;");
            if(sql_utils::query_execution($query,"di", [$balance, $id])){
                $affected_rows = $query->affected_rows;
                if ($affected_rows > 0) {
                    return true;
                } 
            }
            return false;
        }

        static function update_wallet_transaction_code($id,$code,$con){
            $query = $con->prepare("UPDATE wallets SET `transaction_code` = ? WHERE id = ?;");
            if(sql_utils::query_execution($query,"si", [$code, $id])){
                $affected_rows = $query->affected_rows;
                if ($affected_rows > 0) {
                    return true;
                } 
            }
            return false;
        }

        static function delete_wallet($user_id,$con){
            $query = $con->prepare("DELETE FROM wallets WHERE user_id = ?;");
            if(sql_utils::query_execution($query,"i", [$user_id])){
                $affected_rows = $query->affected_rows;
                if ($affected_rows > 0) {
                    return true;
                } 
            }
            return false;
        }
    }
?>
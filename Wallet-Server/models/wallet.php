<?php
    class wallet{
        public $id;
        public $user_id;
        public $balance;
        public $transaction_code;
        function __construct($id, $user_id, $balance, $transaction_code=null){
            $this->id=$id;
            $this->user_id=$user_id;
            $this->balance=$balance;
            $this->transaction_code=$transaction_code;
        }

        static function get_wallet_by_id($id){
            require __DIR__ . '/../connection/connection.php';
            $query = $con->prepare("SELECT * FROM wallets WHERE user_id=?");
            if(sql_utils::query_execution($query,"i", [$id])){
                $result = $query->get_result();
                $wallet=null;
                while($wallet_db = mysqli_fetch_assoc($result)){
                    $wallet = new wallet($wallet_db["id"], $wallet_db["user_id"],$wallet_db["balance"], $wallet_db["transaction_code"]);
                }
                return $wallet;
            }
        }

        static function get_wallet_by_transaction_code($transaction_code){
            require __DIR__ . '/../connection/connection.php';
            $query = $con->prepare("SELECT * FROM wallets WHERE transaction_code=?");
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

        static function update_wallet_balance($id,$balance){
            require __DIR__ . '/../connection/connection.php';
            $query = $con->prepare("UPDATE wallets SET `balance` = ? WHERE id = ?;");
            if(sql_utils::query_execution($query,"di", [$balance, $id])){
                $affected_rows = $query->affected_rows;
                if ($affected_rows > 0) {
                    return true;
                } 
            }
            return false;
        }

        static function update_wallet_transaction_code($id,$code){
            require __DIR__ . '/../connection/connection.php';
            $query = $con->prepare("UPDATE wallets SET `transaction_code` = ? WHERE id = ?;");
            if(sql_utils::query_execution($query,"si", [$code, $id])){
                $affected_rows = $query->affected_rows;
                if ($affected_rows > 0) {
                    return true;
                } 
            }
            return false;
        }
    }
?>
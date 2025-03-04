<?php
    // id,sender_id,receiver_id,transaction_date,amount	
    class transaction{
        public $id;
        public $sender_id;
        public $receiver_id;
        public $transaction_date;
        public $amount;
        function __construct($id,$sender_id,$receiver_id,$transaction_date,$amount){
            $this->id=$id;
            $this->sender_id=$sender_id;
            $this->receiver_id=$receiver_id;
            $this->transaction_date=$transaction_date;
            $this->amount=$amount;
        }

        static function create_transaction($sender_id,$receiver_id,$transaction_date,$amount){
            require __DIR__ . '/../connection/connection.php';
            $query = $con->prepare("INSERT INTO transactions (sender_id,receiver_id,transaction_date,amount) VALUES (?,?,?,?);");
            if(sql_utils::query_execution($query,"iisi", [$sender_id,$receiver_id,$transaction_date,$amount])){
                $id = $con->insert_id;
                $transaction = new transaction($id,$sender_id,$receiver_id,$transaction_date,$amount);
                return $transaction;
            }
        }

        static function get_transactions_by_user_id_sorted_by_time($user_id){
            require __DIR__ . '/../connection/connection.php';
            require __DIR__ . '/user.php';
            $query = $con->prepare("SELECT * FROM transactions WHERE sender_id=? or receiver_id=? ORDER BY transaction_date DESC;");
            if(sql_utils::query_execution($query,"ii", [$user_id,$user_id])){
                $result = $query->get_result();
                $transactions = [];
                while($trans_db = mysqli_fetch_assoc($result)){
                    // $transaction = new transaction($trans_db["id"],$trans_db["sender_id"],$trans_db["receiver_id"],$trans_db["transaction_date"],$trans_db["amount"]);
                    
                    $sender = user::get_user_by_id($trans_db["sender_id"]);
                    $receiver = user::get_user_by_id($trans_db["receiver_id"]);

                    if($sender && $receiver){
                        $transaction_name = new transaction_name($sender->name, $receiver->name,$trans_db["transaction_date"],$trans_db["amount"]);
                        $transactions[]=$transaction_name;
                    } else if($sender && $trans_db["receiver_id"]==0){
                        $transaction_name = new transaction_name($sender->name, "Withdraw",$trans_db["transaction_date"],$trans_db["amount"]);
                        $transactions[]=$transaction_name;
                    } else if($trans_db["sender_id"]==0 && $receiver){
                        $transaction_name = new transaction_name("Deposit", $receiver->name,$trans_db["transaction_date"],$trans_db["amount"]);                        
                        $transactions[]=$transaction_name;
                    } else if(!$sender && $receiver){
                            $transaction_name = new transaction_name("Deleted Account", $receiver->name,$trans_db["transaction_date"],$trans_db["amount"]);                        
                            $transactions[]=$transaction_name;
                    }else if($sender && !$receiver){
                        $transaction_name = new transaction_name($sender->name, "Deleted Account",$trans_db["transaction_date"],$trans_db["amount"]);                        
                        $transactions[]=$transaction_name;
                    }
                }
                if(sizeof($transactions)>0){
                    return $transactions;
                }else{
                    return null;
                }
            }
        }
    }

    class transaction_name{
        public $sender_name;
        public $receiver_name;
        public $transaction_date;
        public $amount;
        function __construct($sender_name,$receiver_name,$transaction_date,$amount){
            $this->sender_name=$sender_name;
            $this->receiver_name=$receiver_name;
            $this->transaction_date=$transaction_date;
            $this->amount=$amount;
        }

    }

?>
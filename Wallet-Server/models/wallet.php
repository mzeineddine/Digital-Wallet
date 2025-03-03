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
    }
?>
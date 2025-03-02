<?php
    class user{
        public $id;
        public $name;
        public $email;
        public $validation_level;
        public $registration_date;
        public $address;
        public $phone_nb;
        function __construct($id, $name, $email, $validation, $registration_date,$address=null,$phone_nb=null){
            $this->id=$id;
            $this->name=$name;
            $this->email=$email;
            $this->validation_level=$validation;
            $this->registration_date=$registration_date;
            $this->address=$address;
            $this->phone_nb=$phone_nb;
        }
    }
?>
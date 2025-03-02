<?php
    class user{
        public $id;
        public $name;
        public $email;
        public $validation_level;
        function __construct($id, $name, $email, $validation){
            $this->id=$id;
            $this->name=$name;
            $this->email=$email;
            $this->validation_level=$validation;
        }
    }
?>
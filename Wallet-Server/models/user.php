<?php
class user{
        public $id;
        public $name;
        public $email;
        public $pass;
        public $validation_level;
        public $registration_date;
        public $address;
        public $phone_nb;
        function __construct($id, $name, $email,$pass, $registration_date, $validation=-1,$address=null,$phone_nb=null){
            $this->id=$id;
            $this->name=$name;
            $this->email=$email;
            $this->pass=$pass;
            $this->validation_level=$validation;
            $this->registration_date=$registration_date;
            $this->address=$address;
            $this->phone_nb=$phone_nb;
        }
        static function create_user($email, $pass,$full_name,$time,$con){
            $query = $con->prepare("INSERT INTO users (email,pass,name,registration_date) VALUES (?,?,?,?)");
            if(sql_utils::query_execution($query,"ssss", [$email, $pass,$full_name,$time])){
                $id = $con->insert_id;
                $user = new user($id,$full_name, $email,$pass,$time);
                return $user;
            }
        } 
        
        static function create_full_user($id, $full_name,$email, $pass,$tier, $time,$address,$phone_nb, $con){
            $query = $con->prepare("INSERT INTO users (id,`name`,email,pass,validation_level,registration_date,`address`,phone_nb) 
                                                VALUES (?,?,?,?,?,?,?,?)");
            if(sql_utils::query_execution($query,"isssisss", [$id, $full_name,$email, $pass,$tier, $time,$address,$phone_nb])){
                $id = $con->insert_id;
                $user = new user($id,$full_name,$email, $pass,$tier,$time);
                return $user;
            }
        } 
        static function check_email_usage($email,$con){
            $query = $con->prepare("SELECT * FROM users WHERE email=?");
            if(sql_utils::query_execution($query,"s", [$email])){
                $result = $query->get_result();
                return $result->num_rows>0;
            }
        }

        static function get_user_by_id($id,$con){
            $query = $con->prepare("SELECT * FROM users WHERE id=?");
            if(sql_utils::query_execution($query,"s", [$id])){
                $result = $query->get_result();
                $user = null;
                while($user_db = mysqli_fetch_assoc($result)){
                    $user = new user($user_db['id'],$user_db['name'], $user_db['email'], $user_db['pass'],$user_db['registration_date'],$user_db['validation_level'],$user_db['address'], $user_db['phone_nb']);
                }
                if($user){
                    return $user;
                }else{
                    return null;
                }
            }
        }

        static function get_user_by_email_pass($email,$pass,$con){
            $query = $con->prepare("SELECT * FROM users WHERE email=? and pass =?;");
            if(sql_utils::query_execution($query,"ss", [$email,$pass])){
                $result = $query->get_result();
                $user= null;
                while($user_db = mysqli_fetch_assoc($result)){
                    $user = new user($user_db['id'],$user_db['name'], $user_db['email'], $user_db['pass'],$user_db['registration_date'],$user_db['validation_level'],$user_db['address'], $user_db['phone_nb']);
                }
                return $user;
            }
        }

        static function update_user_with_pass($full_name, $pass, $phone_nb,$address,$id,$con){
            $query = $con->prepare("UPDATE users SET `name` = ?, pass=?, phone_nb = ?, `address` = ? WHERE id=?;");
            if(sql_utils::query_execution($query,"ssssi", [$full_name, $pass, $phone_nb,$address,$id])){
                $affectedRows = $query->affected_rows;
                if ($affectedRows > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        static function update_user_without_pass($full_name, $phone_nb,$address,$id,$con){
            $query = $con->prepare("UPDATE users SET `name` = ?, phone_nb = ?, `address` = ? WHERE id = ?;");
            if(sql_utils::query_execution($query,"sssi", [$full_name, $phone_nb,$address,$id])){
                $affectedRows = $query->affected_rows;
                if ($affectedRows > 0) {
                    echo "Successfully updated $affectedRows row(s).";
                } else {
                    echo "No rows were updated.";
                }
            }
        }

        static function update_user_validation($validation_level,$id,$con){
            $query = $con->prepare("UPDATE users set validation_level=0 WHERE id=?");
            if(sql_utils::query_execution($query,"i", [$id])){
                $result = $query->get_result();
                if($query->affected_rows==1){
                    return true;
                }
                return false;
            }
        }

        static function delete_user($id,$con){
            $query = $con->prepare("DELETE FROM users WHERE id=?");
            if(sql_utils::query_execution($query,"i", [$id])){
                $result = $query->get_result();
                if($query->affected_rows==1){
                    return true;
                }
                return false;
            }
        }
    }
?>
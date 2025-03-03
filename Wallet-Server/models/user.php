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
        static function create_user($email, $pass,$full_name,$time){
            require __DIR__ . '/../connection/connection.php';
            $query = $con->prepare("INSERT INTO users (email,pass,name,registration_date) VALUES (?,?,?,?)");
            if(sql_utils::query_execution($query,"ssss", [$email, $pass,$full_name,$time])){
                $id = $con->insert_id;
                $user = new user($id,$full_name, $email,$pass,$time);
                return $user;
            }
        } 
        
        static function create_full_user($id, $full_name,$email, $pass,$tier, $time,$address,$phone_nb){
            require __DIR__ . '/../connection/connection.php';
            $query = $con->prepare("INSERT INTO users (id,`name`,email,pass,validation_level,registration_date,`address`,phone_nb) 
                                                VALUES (?,?,?,?,?,?,?,?)");
            if(sql_utils::query_execution($query,"isssisss", [$id, $full_name,$email, $pass,$tier, $time,$address,$phone_nb])){
                $id = $con->insert_id;
                $user = new user($id,$full_name,$email, $pass,$tier,$time);
                return $user;
            }
        } 
        static function check_email_usage($email){
            require __DIR__ . '/../connection/connection.php';
            $query = $con->prepare("SELECT * FROM users WHERE email=?");
            if(sql_utils::query_execution($query,"s", [$email])){
                $result = $query->get_result();
                return $result->num_rows>0;
            }
        }

        static function get_user_by_id($id){
            require __DIR__ . '/../connection/connection.php';
            $query = $con->prepare("SELECT * FROM users WHERE id=?");
            if(sql_utils::query_execution($query,"s", [$id])){
                $result = $query->get_result();
                $user = null;
                while($user_db = mysqli_fetch_assoc($result)){
                    $user = new user($user_db['id'],$user_db['name'], $user_db['email'], $user_db['validation_level'],$user_db['registration_date'],$user_db['address'], $user_db['phone_nb']);
                }
                if($user){
                    echo json_encode($user);
                }else{
                    echo json_encode(["message"=>"user not found"]);
                }
            }
        }
    }
?>
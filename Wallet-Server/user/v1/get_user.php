<?php
    include("../../models/user.php");

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
        $query = $con->prepare("SELECT * FROM users WHERE id=?");
        if(sql_utils::query_execution($query,"i", [$id])){
            $result = $query->get_result();
            $user=null;
            while($user_db = mysqli_fetch_assoc($result)){
                $user = new user($user_db['id'],$user_db['name'], $user_db['email'], $user_db['validation_level'],$user_db['registration_date'],$user_db['address'], $user_db['phone_nb']);
            }
            if($user){
                echo json_encode($user);
                return;
            }else{
                echo json_encode(["message"=>"no user fount"]);
                return;
            }
        }
    }
?>
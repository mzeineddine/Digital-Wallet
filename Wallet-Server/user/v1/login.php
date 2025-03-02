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
    
    if(data_utils::missing_parm(2,$data, ["email", "pass"])){
        $email = $data['email'];
        $pass = $data['pass'];
        $pass = hash("sha3-256", $pass);
        $query = $con->prepare("SELECT * FROM users WHERE email=? and pass =?;");
        if(sql_utils::query_execution($query,"ss", [$email,$pass])){
            $result = $query->get_result();
            $user;
            while($user_db = mysqli_fetch_assoc($result)){
                $user = new user($user_db['id'],$user_db['name'], $user_db['email'], $user_db['validation_level']);
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
    // if(isset($data['email']) && isset($data['pass'])){
    //     $email = $data['email'];
    //     $pass = $data['pass'];
    //     $pass = hash("sha3-256", $pass);
    //     $query = $con->prepare("SELECT * FROM users WHERE email=? and pass =?;");
    //     if($query){
    //         $query->bind_param("ss", $email, $pass);
    //         if ($query->execute()) {
    //             $result = $query->get_result();
    //             $response=[];
    //             $counter=0;
    //             while($user = mysqli_fetch_assoc($result)){
    //                 $response += $user;
    //             }
    //             echo json_encode($response);
    //         } else {
    //             echo "Error: not executed";
    //         }
    //     } else{
    //         echo "Error: not prepared";
    //     }
    // }else{
    //     echo "messing parameter";
    // }
?>
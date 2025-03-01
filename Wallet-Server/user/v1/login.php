<?php
    include("../../connection/connection.php");
    if($con->connect_error){
        die();
    }
    $data = json_decode(file_get_contents('php://input'), true);
    print_r($data);
    if(isset($data['email']) && isset($data['pass'])){
        $email = $data['email'];
        $pass = $data['pass'];
        $pass = hash("sha3-256", $pass);

        $query = $con->prepare("SELECT * FROM users WHERE email=? and pass =?;");

        if($query){
            $query->bind_param("ss", $email, $pass);

            if ($query->execute()) {

                $result = $query->get_result();

                // if($result->num_rows>0){
                //     echo "User found";
                // }else{
                //     echo "Check input email and pass miss match";
                // }

                $response=[];
                $counter=0;
                while($user = mysqli_fetch_assoc($result)){
                    $response += $user;
                }
                echo json_encode($response);
            } else {
                echo "Error: not executed";
            }

        } else{
            echo "Error: not prepared";
        }

    }else{
        echo "messing parameter";
    }
?>
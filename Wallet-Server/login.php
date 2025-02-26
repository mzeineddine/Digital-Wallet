<?php
    include("./connection.php");
    if($con->connect_error){
        die();
    }

    if(isset($_POST['email']) && isset($_POST['pass'])){

        $email = $_POST['email'];
        $pass = $_POST['pass'];
        // $pass = hash("sha3-256", $pass);

        $query = $con->prepare("SELECT * FROM users WHERE email=? and pass =?;");

        if($query){
            $query->bind_param("ss", $email, $pass);

            if ($query->execute()) {

                $result = $query->get_result();

                if($result->num_rows>0){
                    echo "User found";
                }else{
                    echo "Check input email and pass miss match";
                }

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
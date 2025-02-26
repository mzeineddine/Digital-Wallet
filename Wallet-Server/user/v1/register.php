<?php
    include("./connection.php");
    if($con->connect_error){
        die();
    }

    if((isset($_POST['email']) && isset($_POST['pass'])) && ($_POST['email'] != "" && $_POST['pass'] != "" )){

        $email = $_POST['email'];
        $pass = $_POST['pass'];
        // $pass = hash("sha3-256", $pass);

        $query = $con->prepare("INSERT INTO users (email,pass) VALUES (?,?)");
        if($query){
            $query->bind_param("ss", $email, $pass);

            if ($query->execute()) {

                if($query->affected_rows>0){
                    echo "User Added";
                }else{
                    echo "Something Went Wrong  ";
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
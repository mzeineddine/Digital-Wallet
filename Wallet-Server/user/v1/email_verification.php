<?php
    include("../../connection/connection.php");
    if($con->connect_error){
        die();
    }

    if(isset($_GET['id'])){
        $query = $con->prepare("UPDATE users set validation_level=0 WHERE id=?");
        if($query){
            $query->bind_param("s", $_GET['id']);
            if ($query->execute()) {
                echo "Your email is verified";
            } else {
                echo "Error: not executed";
            }

        } else{
            echo "Error: not prepared";
        }
    } else{
        echo "messing parameter";
    }
?>
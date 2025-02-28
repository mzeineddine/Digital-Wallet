<?php
    include("../../connection/connection.php");
    if($con->connect_error){
        die();
    }
    $base = "http://localhost/Projects/Digital-Wallet/";
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    if((isset($data['full_name']) && isset($data['email']) && isset($data['pass'])) && ($data['full_name'] != "" && $data['email'] != "" && $data['pass'] != "")){
        $full_name = $data['full_name'];
        $email = $data['email'];
        $pass = $data['pass'];
        $pass = hash("sha3-256", $pass);
        $is_addable = true;
        $query = $con->prepare("SELECT * FROM users WHERE email=?");
        if($query){
            $query->bind_param("s", $email);
            if ($query->execute()) {
                $result = $query->get_result();
                if($result->num_rows>0){
                    $is_addable = false;
                }
            }
        }

        if($is_addable){
            $query = $con->prepare("INSERT INTO users (email,pass,name) VALUES (?,?,?)");
            if($query){
                $query->bind_param("sss", $email, $pass, $full_name);

                if ($query->execute()) {

                    if($query->affected_rows>0){
                        $id = $con->insert_id;

                        $message = "Verify your email";
                        $to=$email;
                        $subject="Email Verification For Digital Wallet";
                        $from = 'mohammad.zeineddine50@gmail.com';
                        $body='Please Click On This link <a href="'.$base.'/email_verification.php">Verify.php?id='.$id.'</a>to activate your account.';
                        $headers = "From:".$from."\r\n";
                        if(mail($to, $subject, $body, $headers)){
                            echo "Email sent successfully.";
                        } else {
                            echo "Failed to send the email.";
                            $error = error_get_last();
                            echo "Error details: " . print_r($error, true);
                        }
                    }else{
                        echo "Something Went Wrong  ";
                    }

                } else {
                    echo "Error: not executed";
                }

            } else{
                echo "Error: not prepared";
            }
        } else{
            echo "Email Already Used";
        }
    }else{
        echo "messing parameter";
    }
?>
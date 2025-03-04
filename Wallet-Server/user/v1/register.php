<?php
    include("../../connection/connection.php");
    include("../../utils.php");
    include("../../models/user.php");
    $base1 = "http://localhost/Projects";
    $base = $base1."/Digital-Wallet/Wallet-Server/user/v1";
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    if(data_utils::missing_parm(3,$data, ["email","pass","full_name"])){
        $full_name = $data['full_name'];
        $email = $data['email'];
        $pass = $data['pass'];
        $pass = hash("sha3-256", $pass);
        $query = $con->prepare("SELECT * FROM users WHERE email=?");
        
        if(user::check_email_usage($email)){
            echo json_encode(["result"=>""]);
            echo json_encode(["message"=>"Email Already Used"]);
            return;
        } else{
            $time = date("Y-m-d H:i:s");
            $user = user::create_user($email,$pass,$full_name,$time);
            if($user->id!=-1){
                    $message = "Verify your email";
                    $to=$email;
                    $subject="Email Verification For Digital Wallet";
                    $from = 'mohammad.zeineddine50@gmail.com';
                    $body='Please Click On This link '.$base.'/email_verification.php?id='.$user->id.'"to activate your account.';
                    $headers = "From:".$from."\r\n";
                    mail($to, $subject, $body, $headers);
                    echo json_encode(["result"=>$user]);
                    echo json_encode(["message"=>"user registered"]);
                    return;
            } else{
                echo json_encode(["result"=>"none"]);
                echo json_encode(["message"=>"user not found"]);
                return;
            }
        }
        

    }
//v1
    // if((isset($data['full_name']) && isset($data['email']) && isset($data['pass'])) && ($data['full_name'] != "" && $data['email'] != "" && $data['pass'] != "")){
    //     $full_name = $data['full_name'];
    //     $email = $data['email'];
    //     $pass = $data['pass'];
    //     $pass = hash("sha3-256", $pass);
    //     $is_addable = true;
    //     $query = $con->prepare("SELECT * FROM users WHERE email=?");
    //     if($query){
    //         $query->bind_param("s", $email);
    //         if ($query->execute()) {
    //             $result = $query->get_result();
    //             if($result->num_rows>0){
    //                 $is_addable = false;
    //             }
    //         }
    //     }
    //     if($is_addable){
    //         $query = $con->prepare("INSERT INTO users (email,pass,name) VALUES (?,?,?)");
    //         if($query){
    //             $query->bind_param("sss", $email, $pass, $full_name);

    //             if ($query->execute()) {

    //                 if($query->affected_rows>0){
    //                     $id = $con->insert_id;

    //                     $message = "Verify your email";
    //                     $to=$email;
    //                     $subject="Email Verification For Digital Wallet";
    //                     $from = 'mohammad.zeineddine50@gmail.com';
    //                     $body='Please Click On This link <a href="'.$base.'/email_verification.php?id='.$id.'">Verify</a>to activate your account.';
    //                     $headers = "From:".$from."\r\n";
    //                     if(mail($to, $subject, $body, $headers)){
    //                         echo "Email sent successfully.";
    //                     } else {
    //                         echo "Failed to send the email.";
    //                         $error = error_get_last();
    //                         echo "Error details: " . print_r($error, true);
    //                     }
    //                 }else{
    //                     echo "Entry not added";
    //                 }

    //             } else {
    //                 echo "Error: not executed";
    //             }

    //         } else{
    //             echo "Error: not prepared";
    //         }
    //     } else{
    //         echo "Email Already Used";
    //     }
    // }else{
    //     echo "messing parameter";
    // }

//v2
    // if(data_utils::missing_parm(3,$data, ["email","pass","full_name"])){
    //     $full_name = $data['full_name'];
    //     $email = $data['email'];
    //     $pass = $data['pass'];
    //     $pass = hash("sha3-256", $pass);
    //     $query = $con->prepare("SELECT * FROM users WHERE email=?");
    //     if($query){
    //         $query->bind_param("s", $email);
    //         if ($query->execute()) {
    //             $result = $query->get_result();
    //             if($result->num_rows>0){
    //                 echo "Email Already Used";
    //                 return;
    //             }
    //         }
    //     }
    //     $query = $con->prepare("INSERT INTO users (email,pass,name) VALUES (?,?,?)");
    //     if($query){
    //         $query->bind_param("sss", $email, $pass, $full_name);
    //         if ($query->execute()) {
    //             if($query->affected_rows>0){
    //                 $id = $con->insert_id;
    //                 $message = "Verify your email";
    //                 $to=$email;
    //                 $subject="Email Verification For Digital Wallet";
    //                 $from = 'mohammad.zeineddine50@gmail.com';
    //                 $body='Please Click On This link <a href="'.$base.'/email_verification.php?id='.$id.'">Verify</a>to activate your account.';
    //                 $headers = "From:".$from."\r\n";
    //                 if(mail($to, $subject, $body, $headers)){
    //                     echo "Email sent successfully.";
    //                 } else {
    //                     echo "Failed to send the email.";
    //                     $error = error_get_last();
    //                     echo "Error details: " . print_r($error, true);
    //                 }
    //             }else{
    //                 echo "Entry not added";
    //                 return;
    //             }

    //         }else {
    //             echo "Error: not executed";
    //             return;
    //         }
    //     } else{
    //         echo "Error: not prepared";
    //         return;
    //     }
    // } else{
    //     echo "messing parameter";
    //     return;
    // }
?>
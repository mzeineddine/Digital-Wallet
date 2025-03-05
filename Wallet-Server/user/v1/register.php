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
?>
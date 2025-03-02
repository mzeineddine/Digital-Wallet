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
print_r($data);

// full_name: name,
// pass: pass,
// phone_nb: phone_nb,
// address: address,
// id:sessionStorage.getItem("user_id")

if(data_utils::missing_parm(4,$data, ["phone_nb", "full_name","address", "id"])){
    $full_name = $data['full_name'];
    $pass = $data['pass'];
    $phone_nb = $data['phone_nb'];
    $address = $data['address'];
    $id = $data['id'];
    $pass = hash("sha3-256", $pass);
    if(isset($data['pass']) && $data['pass'] != ""){
        $query = $con->prepare("UPDATE users SET `name` = ?, pass=?, phone_nb = ?, `address` = ? WHERE id=?;");
        if(sql_utils::query_execution($query,"ssssi", [$full_name, $pass, $phone_nb,$address,$id])){
            $affectedRows = $query->affected_rows;

        if ($affectedRows > 0) {
            echo "Successfully updated $affectedRows row(s).";
        } else {
            echo "No rows were updated.";
        }
            // get_result();
            // $user;
            // while($user_db = mysqli_fetch_assoc($result)){
            //     $user = new user($user_db['id'],$user_db['name'], $user_db['email'], $user_db['validation_level']);
            // }
            // if($user){
            //     echo json_encode($user);
            // }else{
            //     echo json_encode(["message"=>"no user fount"]);
            // }
        }
    } else{
        $query = $con->prepare("UPDATE users SET `name` = ?, phone_nb = ?, `address` = ? WHERE id = ?;");
        if(sql_utils::query_execution($query,"sssi", [$full_name, $phone_nb,$address,$id])){
            $result = $query->affected_rows;
            if($result){
                echo json_encode($result);
            }else{
                echo json_encode(["message"=>"no user fount"]);
            }
        }
    }
}
?>
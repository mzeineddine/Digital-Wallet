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
        }
    } else{
        $query = $con->prepare("UPDATE users SET `name` = ?, phone_nb = ?, `address` = ? WHERE id = ?;");
        if(sql_utils::query_execution($query,"sssi", [$full_name, $phone_nb,$address,$id])){
            $affectedRows = $query->affected_rows;
            if ($affectedRows > 0) {
                echo "Successfully updated $affectedRows row(s).";
            } else {
                echo "No rows were updated.";
            }
        }
    }
}
?>
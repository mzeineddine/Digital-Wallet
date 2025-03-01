<?php
    include("../../connection/connection.php");
    include("../../utils.php");
    if($con->connect_error){
        return;
    }
    $response=[];
    if(isset($_GET['id'])){
        $query = $con->prepare("UPDATE users set validation_level=0 WHERE id=?");
        if(sql_utils::query_execution($query,"i", [$_GET['id']])){
            $result = $query->get_result();
            if($query->affected_rows==1){
                $response = "Your Email IS Verified";
            }
            echo json_encode($response);
        }
    }
?>
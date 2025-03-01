<?php
class sql_utils{
    static function query_execution($query, $format, $args){
        if($query){
            // $query->bind_param($format,$args);
            $params = array_merge([$format], $args);

            $refParams = [];
        
            foreach ($params as $key => &$value) {
                $refParams[$key] = &$value;
            }

            call_user_func_array([$query,'bind_param'], $refParams);
            if ($query->execute()) {
                return true;
            }else {
                echo "Query is not executed";
                return;
            }
        } else{
            echo "Query is not prepared";
            return;
        }
    }
}

// class email_utils{
//     static send_email(){

//     }
// }
class data_utils{
    static function missing_parm($nb, $data, $args){
        $no_missing = true;
        for($i = 0; $i<$nb; $i++){
            if(!isset($data[$args[$i]])){
                echo "missing " . $args[$i];
                return false;
            }
            if($data[$args[$i]]==""){
                echo "missing " . $args[$i];
                return false;
            }
        }
        return $no_missing;
    }
}
?>
<?php

include_once '../Utils.php';

if(isset($_POST['action'])){
    $action = $_POST['action'];
    switch($action){
        // save items
        case('save-list'):
            if(isset($_POST['data'])){
                $data = json_decode($_POST['data'], true);
                if($data !== null && $data !== false){
                    $result = Utils::save_future_list($data);
                    if(count($result) > 0 ){
                        echo json_encode($result);
                    }
                    else{
                        echo json_encode(array('status' => 'saved'));
                    }
                }else send_error('cannot parse json data');
            }else{ send_error('data param does not set'); }
            break;
        case('load-list'):
            break;
    }
}

function send_error($error_text){
    $arr['error'] = $error_text;
    echo json_encode($arr);

}
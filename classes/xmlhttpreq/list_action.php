<?php

include_once '../Utils.php';
include_once '../../ConfigReader.php';

if(isset($_POST['action'])){
    $action = $_POST['action'];
    switch($action){
        // save items
        case('save-list'):
            if(isset($_POST['data'])){
                $data = json_decode($_POST['data'], true);
                if($data !== null && $data !== false){
                    $result = Utils::save_future_list($data);

                    //error
                    if(count($result) > 0 ){
                        echo json_encode($result);
                    }
                    else{
                        if(isset($_POST['action-type'])){
                            $action_type = $_POST['action-type'];
                            if($action_type == 'save-all'){
                                ob_start();
                                update_and_supress_output($data);
                                ob_end_clean();
                            }
                            if($action_type == 'new'){
                                //do nothing
                            }
                        }

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

function update_and_supress_output($data){
    $cr = new ConfigReader();
    $cr->udate_pending_items_count(-count($data));
    return true;
}
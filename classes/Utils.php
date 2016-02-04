<?php

class Utils
{
    private static $future_list_file = '/tmp/rutracker_future_list.txt';

    static function preOut($var){
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
        echo "<br><br>";
    }

    static function print_encode_to_utf8($str, $from='cp1251'){
        self::preOut(self::encode_to_utf8($str, $from='cp1251'));
    }

    static function encode_to_utf8($str, $from='cp1251'){
        return iconv($from, 'UTF-8', $str);
    }

    public static function read_from_file(){
        $file = self::get_parent_dir() . self::$future_list_file;
        $array_items = array();
        $errors = array();

        if(file_exists($file)){
            if(!is_readable($file)){
                $errors['error'] = "Future list file is not readable <code>" . $file . "</code>";
            }else{
                if(filesize($file) > 0){
                    $file_content = file_get_contents($file, true);
                    if($file_content === false){
                        return $errors['error'] = 'Cannot read form future list file <code>' . $file . "</code>";
                    }

                    $items = explode("\n", $file_content);
                    for($i = 0; $i < count($items)-1; $i++){
                        $ar = explode(':::', $items[$i]);
                        $array_items[] = array(
                            'name' => $ar[0],
                            'link' => trim($ar[1])
                        );
                    }
                }else{
                    $errors['error'] = "future list file is empty <code>" . $file . "</code>";
                }
            }
        }else{
            $errors['error'] = 'Future list file does not exists';
        }

        if(count($errors) > 0){
            return $errors;
        }else{
            return $array_items;
        }

    }
    public static function save_future_list($list, $save_type=FILE_APPEND){
        $file = self::get_parent_dir() . self::$future_list_file;
        $data_to_write = '';
        $errors = array();

        if (!file_exists($file)) {
            $f = fopen($file, 'w');
            if($f === false){
                $errors['error'] = "Cannot crete future list file <code>" . $file . "</code>";
            }else{
                fclose($f);
            }
        }

        foreach($list as $el){
            $data_to_write .= trim($el['name']) . " ::: " .$el['link'] . "\n";
        }

        if(is_writable($file)){
            if(file_put_contents($file, $data_to_write, $save_type) == false){
                $errors['error'] = 'Cannot write to file <code>' . $file . "</code>";
            }
        }else{
            $errors['error'] = 'File is not writable <code>' . $file . "</code>";
        }

        return $errors;
    }


    /**
     * @param $files_to_remove array with items to remove
     * @return array
     */
    public static function remove_from_future_list($files_to_remove){
        $file_items = self::read_from_file();
        $count = count($file_items);
        $isFound = array();

        foreach($files_to_remove as $item_to_rem){
            for($i =0 ; $i < $count; $i++){
                //will do not check on torrents name, cause they can be changed
                if($item_to_rem['link'] == $file_items[$i]['link']){
                    $isFound[] = $file_items[$i];
                    unset($file_items[$i]);
                }
            }
        }

        self::save_future_list($file_items, null);
        return $isFound;
    }

    /**
     * @return string
     */
    public static function getFutureListFile()
    {
        return self::$future_list_file;
    }

    public static function get_parent_dir(){
        return $_SERVER['DOCUMENT_ROOT'] . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '/', 1));
    }
}
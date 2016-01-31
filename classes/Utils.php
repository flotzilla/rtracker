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
        $file = dirname(__DIR__) . self::$future_list_file;
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
    public static function save_future_list($list){
        $file = dirname(__DIR__) . self::$future_list_file;
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
            $data_to_write .= $el['name'] . " ::: " .$el['link'] . "\n";
        }

        if(is_writable($file)){
            if(file_put_contents($file, $data_to_write, FILE_APPEND) == false){
                $errors['error'] = 'cannot write to file <code>' . $file . "</code>";
            }
        }else{
            $errors['error'] = 'file is not writable <code>' . $file . "</code>";
        }

        return $errors;
    }

    /**
     * @return string
     */
    public static function getFutureListFile()
    {
        return self::$future_list_file;
    }
}
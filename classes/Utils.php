<?php

class Utils
{
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

}
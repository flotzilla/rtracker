<?php

class Utils
{
    static function preOut($var){
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
        echo "<br><br>";
    }

    static function encode_to_utf8($str, $from='cp1251'){
        self::preOut(iconv($from, 'UTF-8', $str));
    }

}
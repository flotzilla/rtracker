<?php

/**
 * Created by PhpStorm.
 * User: bitybite
 * Date: 1/21/16
 * Time: 5:02 AM
 */
class ConfigReader
{
    private static $default_conf_file;
    private $config;

    function __construct()
    {
        self::$default_conf_file = getcwd() . '/config.json';
        $this->config= array();
        $this->read_config();
    }

    public function read_config(){
        $jsonIterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator(
                json_decode(
                    file_get_contents(self::$default_conf_file), TRUE)),
            RecursiveIteratorIterator::SELF_FIRST);

        $last_key = 0;
        foreach ($jsonIterator as $key => $val) {
            if(is_array($val)) {
                if(isset($key)){
                    $this->config[$key];
                    $last_key = $key;
                }
            } else {
                $this->config[$last_key][$key] = $val;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }
}
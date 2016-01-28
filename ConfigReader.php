<?php


class ConfigReader
{
    private static $default_conf_file;
    private $config;

    function __construct()
    {
        self::$default_conf_file = getcwd() . '/config/config.json';
        $this->config= array();
        $this->read_config();
    }

    private function read_config(){
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
<?php


class ConfigReader
{
    private static $default_conf_file;
    private $config;

    function __construct()
    {
        self::$default_conf_file = Utils::get_parent_dir() . '/config/config.json';
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

    private function save_config_to_file(){
        $result = file_put_contents(self::$default_conf_file, json_encode($this->config));
        if($result == false){
            return 'Error. Cannot save config.json data';
        }else{
            return 'Successfully save config.json file';
        }
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function save_param($param, $key, $value){
        if(array_key_exists($param, $this->config)){
            if(array_key_exists($key, $this->config[$param])){
                $this->config[$param][$key] = $value;
                return $this->save_config_to_file();
            }
        }

        return 'Error. Cannot apply new key value';
    }

    public function udate_pending_items_count($value){
        if(is_numeric($value)) {
            if ($value < 0) {
                $this->config['future-file']['pending-to-save'] += $value;
                if($this->config['future-file']['pending-to-save'] < 0){
                    $this->config['future-file']['pending-to-save'] = 0;
                }
            }else{
                $this->config['future-file']['pending-to-save'] = $value;
            }
            return $this->save_config_to_file();
        }
        return 'Error. Cannot update pending value';
    }
}
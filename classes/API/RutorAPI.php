<?php

include_once getcwd() . '/classes/Utils.php';
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);

class RutorAPI
{
    private static $home_link = 'http://new-rutor.org/';
    private static $search_page = "http://new-rutor.org/search/";
    private static $user_agent = 'Mozilla/5.0 (X11; Linux x86_64; rv:43.0) Gecko/20100101 Firefox/43.0';
    private static $rutor_pager = "/0/000/0/"; // 1/0/000/0/ - e.x. second page
    private static $cookie;

    /**
     * RutorAPI constructor.
     */
    public function __construct()
    {
        self::$cookie = getcwd() . '/tmp/rutor_cookie.txt';
    }


    public function search($search_str)
    {
        $curl = curl_init(self::$search_page . $search_str);

        curl_setopt($curl, CURLOPT_COOKIEJAR, realpath(self::$cookie));
        curl_setopt($curl, CURLOPT_COOKIEFILE, realpath(self::$cookie));
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, self::$user_agent);

        if(!$resp = curl_exec($curl)){
            trigger_error(curl_error($curl));
            Utils::print_encode_to_utf8(curl_error($curl));
        }

        curl_close($curl);
        $torrents = $this->parse_search($resp);

        if($torrents != 0){
            if($torrents['total'] > 100){
                $mod = $torrents['total'] % 100;
                $pages = ($torrents['total'] - $mod) / 100;
                for($i = 1; $i <= $pages; $i++){
                    $results = $this->search_in_second_page($search_str, $i);
                    $torrents = array_merge($torrents, $results);
                }
                return $torrents;
            }else{
                return $torrents;
            }
        }else{
            return array('error' => 'nothing found');
        }

    }

    private function search_in_second_page($search_str, $page){

        $curl = curl_init(self::$search_page . $page . self::$rutor_pager .$search_str);

        curl_setopt($curl, CURLOPT_COOKIEJAR, realpath(self::$cookie));
        curl_setopt($curl, CURLOPT_COOKIEFILE, realpath(self::$cookie));
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, self::$user_agent);

        if(!$resp = curl_exec($curl)){
            trigger_error(curl_error($curl));
            Utils::print_encode_to_utf8(curl_error($curl));
        }

        curl_close($curl);
        return $this->parse_search($resp);
    }

    private function parse_search($serch_result){

        $dom = new DOMDocument();
        $dom->loadHTML($serch_result);
        $dom->preserveWhiteSpace = false;

        $xpath = new DOMXPath($dom);
        $parsed = $xpath->query("//div[@id='index']");

        if($parsed->length == 0){
            return array();
        }

        $found = trim($parsed->item(0)->childNodes->item(2)->textContent);
        $found = (int) substr($found, 36, strlen($found) - 48);

        $torrents = array();
        if($found != 0){
            $table = $parsed->item(0)->childNodes->item(3)->childNodes;
            $table_length = $table->length;

            for ($i = 1; $i < $table_length; $i++){
                $torrents[$i] = array(
                    'torrent_text' => $table->item($i)->childNodes->item(1)->textContent,
                    'torrent_view_link' => self::$home_link . $table->item($i)->childNodes->item(1)
                            ->childNodes->item(1)->attributes->getNamedItem('href')->textContent,
                    'size' => $table->item($i)->childNodes->item(4)->textContent,
                    'torrent_link' => self::$home_link . $table->item($i)->childNodes->item(1)->childNodes->item(0)->attributes->getNamedItem('href')->textContent,
                    'seeders' => trim($table->item($i)->childNodes->item(5)->childNodes->item(0)->textContent),
                    'leeches' => trim($table->item($i)->childNodes->item(5)->childNodes->item(3)->textContent),
                    'added' => $table->item($i)->childNodes->item(0)->textContent
                );
            }
            $torrents['items'] = $table_length - 1;
            $torrents['total'] = $found;
        }

        return $torrents;
    }

}
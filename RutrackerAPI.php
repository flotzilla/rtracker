<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);

class RutrackerAPI
{
    private static $login_page = 'http://login.rutracker.org/forum/login.php';
    private static $future_list_page = 'http://rutracker.org/forum/search.php?dlw=1&dlu=';
    private static $profile_page = 'http://rutracker.org/forum/profile.php?mode=viewprofile&u=';
    private static $main_page = 'http://rutracker.org/forum/';
    //save cookies in tempo directory
    private static $coockies;
    private static $user_agent = 'Mozilla/5.0 (X11; Linux x86_64; rv:43.0) Gecko/20100101 Firefox/43.0';

    //for recursive handling search queries
    private static $is_search_first_run = true;

    private $user_id;
    private $user;
    private $future_list = array();

    function __construct()
    {
        self::$coockies = getcwd() . '/tmp/rt_cookie.txt';
    }

    public function init_action($user, $password){
        if (self::login($user, $password)) {
            $this->user = $user;
            //will receive user_id
            $this->parse_user_params();
            $this->getFutureList();
        } else {
            echo "cannot login";
        }
    }


    private function login($user, $password)
    {
        $is_success = false;

        if (!file_exists(self::$coockies)) {
            echo 'Cookie file missing.';
            return $is_success;
        } else {
            if (!is_writable(self::$coockies)) {
                echo "cannot write to file";
                return $is_success;
            }
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION, 1,
            CURLOPT_USERAGENT, self::$user_agent,
            CURLOPT_URL => self::$login_page,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FOLLOWLOCATION, true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_ENCODING => 'en-US,en;q=0.7,ru;q=0.3',
            CURLOPT_COOKIESESSION, 1,
            CURLOPT_POSTFIELDS => array(
                "login_username" => $user,
                "login_password" => $password,
                "login" => "Вход"
            )
        ));

        //cannot set this params in `curl_setopt_array`
        curl_setopt($curl, CURLOPT_COOKIEJAR, realpath(self::$coockies));
        curl_setopt($curl, CURLOPT_COOKIEFILE, realpath(self::$coockies));

        $resp = curl_exec($curl);
        if (!$resp) {
            var_dump(curl_error($curl));
            $is_success = false;
        } else {
            $is_success = true;
        }
        curl_close($curl);
        return $is_success;
    }

    public function parse_user_params()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$profile_page . $this->user,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION, true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_HEADER => false,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_ENCODING => 'en-US,en;q=0.7,ru;q=0.3',
        ));

        curl_setopt($curl, CURLOPT_COOKIEJAR, realpath(self::$coockies));
        curl_setopt($curl, CURLOPT_COOKIEFILE, realpath(self::$coockies));

        if(!$resp = curl_exec($curl)){
            echo "<br> <h2 class='align-center'>Неправильный запрос</h2> <br>";
            trigger_error(curl_error($curl));
            exit;
        }
        curl_close($curl);


        $dom = new DOMDocument();
        $dom->loadHTML($resp);
        $dom->preserveWhiteSpace = false;

        $xpath = new DOMXpath($dom);
        $this->isRutrackerOff($xpath);

        //TODO read more params

        foreach ($xpath->query('//a[@class="logged-in-as-uname"]') as $item) {
            $attr = $item->getAttribute('href');
            $this->user = $item->nodeValue;
            $this->user_id = substr($attr, strpos($attr, "&u=") + 3);
        }
    }

    public function search($search_string, $options=array()){

        $search_string = trim($search_string);;

        //post type for first search, get type for subsequent
        $curl = curl_init();
        if(self::$is_search_first_run){
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FOLLOWLOCATION, 1,
                CURLOPT_USERAGENT, self::$user_agent,
                CURLOPT_URL => self::$main_page . "tracker.php",
                CURLOPT_POST => true,
                CURLOPT_HEADER => false,
                CURLOPT_AUTOREFERER => true,
                CURLOPT_CONNECTTIMEOUT => 120,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_ENCODING => 'en-US,en;q=0.7,ru;q=0.3',
                CURLOPT_COOKIESESSION, 1,
            ));
        }else{
            $params = "?search_id=" .$options[0]['search_id']. "&start=" . $options[0]['start'] . "&nm=" . $options[0]['nm'];
            curl_setopt_array($curl, array(
                CURLOPT_URL => self::$main_page . "tracker.php" .$params,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION, true,
                CURLOPT_AUTOREFERER => true,
                CURLOPT_CONNECTTIMEOUT => 120,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_HEADER => false,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_ENCODING => 'en-US,en;q=0.7,ru;q=0.3',
            ));
        }

        curl_setopt($curl, CURLOPT_COOKIEJAR, realpath(self::$coockies));
        curl_setopt($curl, CURLOPT_COOKIEFILE, realpath(self::$coockies));

        //if exist any search options
        if(empty($options)){
            curl_setopt($curl, CURLOPT_POSTFIELDS,
                array(
                    "nm" => $search_string,
                )
            );
        }elseif(self::$is_search_first_run){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $options);
        }

        if(!$resp = curl_exec($curl)){
            echo "<br> <h2 class='align-center'>Неправильный запрос</h2> <br>";
            trigger_error(curl_error($curl));
            exit;
        }
        curl_close($curl);

        if(self::$is_search_first_run){
            $search_result =  $this->parse_search_result($resp, $search_string);
            if(isset($search_result['pages']) && isset ($search_result['search_id'])){
                self::$is_search_first_run = false;
                $id = $search_result['search_id'];
                $pages = $search_result['pages'];
                for ($i = 1; $i < $pages; $i++){
                    $arr = $this->search($search_string, array(
                        array(
                            "nm" => $search_string,
                            "start" => $i * 50,
                            "search_id" => $id
                        )
                    ));
                    $search_result = array_merge($search_result, $arr);
                }
                self::$is_search_first_run = true;
                return $search_result;
            }else{
                return $search_result;
            }
        }else{
            return $this->parse_search_result($resp, $search_string);
        }
    }

    private function parse_search_result($html, $search_string){

        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $dom->preserveWhiteSpace = false;

        $xpath = new DOMXpath($dom);

        $this->isRutrackerOff($xpath);

        $torrents = array();
        $iterator = 0;
        foreach ($xpath->query("//tr[@class='tCenter hl-tr']") as $item) {

            $status = $item->childNodes->item(2)->attributes->getNamedItem('title')->textContent;

            if($status == 'поглощено') continue;

            $section_link_search = $item->childNodes->item(4)
                ->firstChild->firstChild->attributes->getNamedItem('href')->textContent;
            $section = $item->childNodes->item(4)->textContent;
            $torrent_text = trim(trim($item->childNodes->item(6)->textContent));
            $torrent_view_link = self::$main_page . $item->childNodes->item(6)
                    ->childNodes->item(1)->childNodes->item(1)->attributes->getNamedItem('href')->textContent;
            $author_link = $item->childNodes->item(8)->childNodes->item(0)->childNodes->item(0)->attributes->getNamedItem('href')->textContent;
            $author_link = substr($author_link, 16);
            $author = $item->childNodes->item(8)->textContent;

            $size = $item->childNodes->item(10)->childNodes->item(3)->textContent;
            $size = substr($size, 0 , strlen($size)-4);

            if(method_exists($item->childNodes->item(10)->childNodes->item(3)->attributes, 'getNamedItem')){
                $torrent_link = $item->childNodes->item(10)->childNodes->item(3)->attributes->getNamedItem('href')->textContent;
            }else{
                $torrent_link = 'no_link';
            }

            $seeds= $item->childNodes->item(12)->childNodes->item(0)->textContent;
            if(strpos($seeds, "-") !== false){
                $seeds = substr($seeds, 1, strlen($seeds)) . " days";
            }
            $leeches= $item->childNodes->item(14)->textContent;
            $downloads_count= $item->childNodes->item(16)->textContent;
            $added= $item->childNodes->item(18)->childNodes->item(3)->textContent
                . " " . $item->childNodes->item(18)->childNodes->item(5)->nodeValue;

            $torrents[$iterator] = array(
                'status' => $status,
                'section' => $section,
                'section_link_search' => $section_link_search,
                'torrent_text' => $torrent_text,
                'torrent_view_link' => $torrent_view_link,
                'author' => $author,
                'author_link' => $author_link,
                'size' => $size,
                'torrent_link' => $torrent_link,
                'seeds' => $seeds,
                'leeches' => $leeches,
                'downloads_count' => $downloads_count,
                'added' => $added
            );
            $iterator++;
        }

        // parse pagesize value if search found more than 50 results
        $info = $xpath->query("//*[@class='bottom_info']");
        $pages = $info->item(0)->childNodes->item(1)->childNodes->item(1)->childNodes->item(3)->nodeValue;
        if(isset($pages)){
            $pages = (int) $pages;
            $torrents['pages'] = $pages;
        }

        // parse search_id for multiple page searching (results > 50)
        if(method_exists($search_id = $info->item(0)->childNodes->item(1)->childNodes->item(3)->childNodes->item(3)
            ->attributes, 'getNamedItem')) {
            $search_id = $info->item(0)->childNodes->item(1)->childNodes->item(3)->childNodes->item(3)
                ->attributes->getNamedItem('href')->textContent;
            $torrents['search_id'] = substr($search_id, strpos($search_id, 'id=') + 3, 12);
        }

        return $torrents;
    }

    private function isRutrackerOff(DOMXPath $xpath){
        $isOff = false;
        foreach($xpath->query("//p") as $er){
            if( strpos($er->nodeValue, "Форум временно отключен на профилактические работы") !== false){
                echo "<p>$er->nodeValue</p>";
                exit;
            }else{
                $isOff = false;
            }
        }
        return $isOff;
    }

    private function get_future_page()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$future_list_page . $this->user_id,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION, 1,
            CURLOPT_HEADER => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_ENCODING => 'en-US,en;q=0.7,ru;q=0.3',
        ));

        curl_setopt($curl, CURLOPT_COOKIEJAR, realpath(self::$coockies));
        curl_setopt($curl, CURLOPT_COOKIEFILE, realpath(self::$coockies));

        if(!$resp = curl_exec($curl)){
            echo "<br> <h2 class='align-center'>Неправильный запрос</h2> <br>";
            trigger_error(curl_error($curl));
            exit;
        }
        curl_close($curl);

        $dom = new DOMDocument();
        $dom->loadHTML($resp);
        $dom->preserveWhiteSpace = false;

        $xpath = new DOMXpath($dom);
        $items = array();

        $this->isRutrackerOff($xpath);

        $iterator = 0;

        foreach($xpath->query("//tr[@class='tCenter']") as $item){
            $topic_link = self::$main_page . $item->childNodes->item(2)->childNodes->item(0)->attributes->getNamedItem('href')->textContent;
            $topic_name = $item->childNodes->item(2)->textContent;
            $torrent_name = trim($item->childNodes->item(4)->textContent);
            $torrent_link = self::$main_page . $item->childNodes->item(4)->childNodes->item(1)->childNodes->item(1)->attributes->getNamedItem('href')->textContent;
            if(strpos($torrent_link, '&view=newest#newest') !== false){
                $torrent_link = substr($torrent_link, 0, strlen($torrent_link) - strlen('&view=newest#newest'));
            }

            if(method_exists( $item->childNodes->item(6)->childNodes->item(1)->childNodes, 'item')){
                $seeds = $item->childNodes->item(6)->childNodes->item(1)->childNodes->item(1)->childNodes->item(0)->childNodes->item(0)->textContent;
                $leeches = $item->childNodes->item(6)->childNodes->item(1)->childNodes->item(1)->childNodes->item(2)->childNodes->item(0)->textContent;
            }else{
                $seeds = 'none';
                $leeches = 'none';
            }

            $items[$iterator] = [
                'link' => $torrent_link,
                'name' => $torrent_name,
                'topic_name' => $topic_name,
                'topic_link' => $topic_link,
                'seeds' => $seeds,
                'leeches' => $leeches
            ];

            $iterator++;
        }

        usort($items, function($a, $b){
           return strcmp($a['topic_name'], $b['topic_name']);
        });

        $this->future_list = $items;
        return $items;
    }

    /**
     * @return bool|string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param bool|string $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return String
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Array
     */
    public function getFutureList()
    {
        if(empty($this->future_list)){
            $this->future_list = $this->get_future_page();
        }
        return $this->future_list;
    }

    /**
     * @return int
     */
    public function getFutureListSize(){
        return count($this->future_list);
    }

    /**
     * @return string
     */
    public static function getProfilePage()
    {
        return self::$profile_page;
    }

    /**
     * @return string
     */
    public static function getFutureListPage()
    {
        return self::$future_list_page;
    }

    /**
     * @return string
     */
    public static function getMainPage()
    {
        return self::$main_page;
    }


}
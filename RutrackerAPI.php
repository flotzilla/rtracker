<?php
use Sunra\PhpSimple\HtmlDomParser;

/**
 * Created by PhpStorm.
 * User: bitybite
 * Date: 1/20/16
 * Time: 6:12 PM
 */
class RutrackerAPI
{
    private static $login_page = 'http://login.rutracker.org/forum/login.php';
    private static $future_list_page = 'http://rutracker.org/forum/search.php?dlw=1&dlu=';
    private static $profile_page = 'http://rutracker.org/forum/profile.php?mode=viewprofile&u=';

    private static $coockies;
    private static $user_agent = 'Mozilla/5.0 (X11; Linux x86_64; rv:43.0) Gecko/20100101 Firefox/43.0';

    private $user_id;
    private $user;

    function __construct($user, $password)
    {
        self::$coockies = getcwd() . '/rt_cookie.txt';

        if(self::login($user, $password)){
            $this->user = $user;
            //will receive user_id
            $this->parse_user_params();
        }else{
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

    private function parse_user_params()
    {
        $id = false;

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

        $resp = curl_exec($curl);

        curl_close($curl);

        if (!$resp) {
            echo curl_error($curl);
        } else {
            $dom = new DOMDocument();
            $dom->loadHTML($resp);
            $dom->preserveWhiteSpace = false;

            $xpath = new DOMXpath($dom);

            foreach($xpath->query('//a[@class="logged-in-as-uname"]') as $item) {
                $attr = $item->getAttribute('href');
                $this->user_id = substr($attr, strpos($attr, "&u=")+3);
            }

        }
    }

    public function get_future_page()
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

        $resp = curl_exec($curl);

        curl_close($curl);

        if (!$resp) {
            return curl_error($curl);
        } else {
            $dom = new DOMDocument();
            $dom->loadHTML($resp);
            $dom->preserveWhiteSpace = false;

            $xpath = new DOMXpath($dom);
            $futurepage = array();
                //TODO implement future list
            return $futurepage;
        }

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
     * @return mixed
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


}
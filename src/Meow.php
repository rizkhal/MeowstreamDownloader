<?php

namespace Meow;

use Exception;

final class Meow {

    /**
     * @var object
     */
    private $out;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $result = [];

    /**
     * @var filename
     */
    private $filename;

    /**
     * @param string
     */
    public function __construct($url)
    {
        header('Content-type: video/mp4');
        echo "works!\n";
        $this->url = $url;
    }

    /**
     * @param pattern $pattern
     * @param content $data
     */
    private function regex($patern, $data)
    {
        preg_match_all($patern, $data, $matches);

        return $matches[1];
    }

    /**
     * @var executor
     * @return void
     */
    public function exec()
    {
        return $this->run();
    }

    /**
     * @method run to download
     */
    private function run()
    {
        $url = "https://meowstream.com/".implode("", $this->filter())."/";
        $data = $this->curls($url);
        if(!empty($data)) {
            $r = $this->regex('!<meta itemprop=".*" content="(.*?)">!', $data);
            
            $data2 = $this->curls($r[0]);
            $resp  = $this->regex('!meow\([\'^]{"file":"(.*?)".*!', stripslashes($data2));
            
            return shell_exec("curl -O ".$resp[0]);
        }
    }
    
    /**
     * @method filter url
     */
    private function filter()
    {
        $result = [];
        $data = $this->curls($this->url);
        if(!empty($data)) {
            $r = $this->regex('!<a href="\/(.*?)\/" title=".*">.*<\/a>!', $data);

            $result = $r;
        }

        return $result;
    }

    /**
     * @param string
     * @method run curl
     */
    private function curls($url)
    {
        $ch = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_REFERER => "https://meowstream.com",
            CURLOPT_POST => true,
            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_AUTOREFERER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:65.0) Gecko/20100101 Firefox/65.0",
        ];
        
        curl_setopt_array($ch, $options);

        $this->out = curl_exec($ch);

        $error = curl_error($ch);
        $errno = curl_errno($ch);
        
        curl_close($ch);

        if($error) {
            goto curl_error;
        }

        return $this->out;

        curl_error: {
            throw new Exception("Failed to run curl: {$errno} : {$error}");
        }
    }
    
}
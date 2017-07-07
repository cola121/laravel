<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/7/5
 * Time: 15:06
 */
namespace App\Lib;

class CommonUtils {

    static function requestUrl($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    static function test() {
        return 'dddooo';
    }

}
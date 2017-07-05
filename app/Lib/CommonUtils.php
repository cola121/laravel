<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/7/5
 * Time: 15:06
 */
namespace App\Http\Lib;

class CommonUtils {

    static function requestUrl($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1000);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        $resData = curl_exec($curl);
        curl_close($curl);
        return $resData;
    }

    static function test() {
        return 'dddooo';
    }

}
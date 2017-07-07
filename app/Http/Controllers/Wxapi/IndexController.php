<?php

namespace App\Http\Controllers\Wxapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Lib\CommonUtils;

class IndexController extends Controller
{
    public function test() {
        $url = 'https://api.douban.com/v2/movie/top250?start=0';
        //$url = 'http://www.douban.com/service/auth2/auth';
        //$url = 'https://movie.douban.com/top250?start=0&filter=';
        //$result = CommonUtils::test();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        curl_close($curl);
//        return $resData;

        echo "<pre>";print_r(json_decode($result, true));
    }

}

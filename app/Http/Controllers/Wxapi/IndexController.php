<?php

namespace App\Http\Controllers\Wxapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Lib\CommonUtils;

class IndexController extends Controller
{
    public function test() {
        $url = 'https://api.douban.com/v2/movie/top250?start=0';
        $result = CommonUtils::test();
        echo "<pre>";print_r($result);
    }

}

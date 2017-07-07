<?php

namespace App\Http\Controllers\Wxapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Lib\CommonUtils;
use App\Lib\VideoTypes;
class IndexController extends Controller
{
    public function test(Request $request) {
        set_time_limit(0);
        //$this->save250(0);
        $this->updateInfo();
//        $result = Video::all();
//        echo "<pre>";print_r($result);
    }

    public function save250($start)
    {
        $url = 'https://api.douban.com/v2/movie/top250?start='.$start;
        $result = CommonUtils::requestUrl($url);
        //echo "<pre>";print_r(json_decode($result, true));
        $result = json_decode($result, true);

        foreach ($result['subjects'] as $row) {
            $video = new Video();
            $video->title = $row['title']; //电影名
            $video->db_id = $row['id']; //豆瓣ID
            $video->title_en = $row['original_title']; //原名
            $video->channel = $row['subtype']; //频道
            $video->year = strtotime($row['year']); //年代

            $video->pic_large  = $row['images']['large']; //大图
            $video->pic_small  = $row['images']['small']; //小图
            $video->pic_medium   = $row['images']['medium']; //中图
            $video->points = $row['rating']['average']; //评分
            $video->summary = '';
            $video->actors  = $this->arrToStr($row['casts'], 'id'); //演员
            $video->directors  = $this->arrToStr($row['directors'], 'id');; //导演
            $video->types  = $this->returnTypes($row['genres']); //类型
            $video->save();
        }
        if ($start < 240) {
            $start = intval($start) + 20;
            return $this->save250($start);
        } else {
            echo "ok";
        }
    }

    public function updateInfo()
    {
        $videos = Video::where('video_id', '>', 101)->get();
        foreach ($videos as $video)
        {
            $dbID = $video->db_id;
            $id = $video->video_id;
            $url = 'https://api.douban.com/v2/movie/subject/'.$dbID;
            $result = CommonUtils::requestUrl($url);
            $result = json_decode($result, true);
            if (!isset($result['code'])) {
                $video->countries = $this->arrToStr($result['countries']);
                $video->summary = $result['summary'];
                $video->aka = $this->arrToStr($result['aka']);
                $video->actors  = $this->arrToStr($result['casts'], 'id'); //演员
                $video->directors  = $this->arrToStr($result['directors'], 'id'); //导演
                $video->types  = $this->returnTypes($result['genres']); //类型
                $video->save();
            }

        }
        echo 'ok';
    }

    public function arrToStr($arr, $key='')
    {
        $str = '';
        $i = 0;
        foreach ($arr as $row) {
            if ($key) {
                $str .= $i == 0 ? $row[$key] : ';'.$row[$key];
            } else {
                $str .= $i == 0 ? $row : ';'.$row;
            }
            $i ++ ;
        }

        return $str;
    }

    public function returnTypes($arr)
    {
        $str = '';
        $i = 0;
        foreach ($arr as $row) {
            if ($type = VideoTypes::returnTypeKey($row)) {
                $str .= $i == 0 ? $type : ';'.$type;
            }
            $i ++ ;
        }
        return $str;

    }
}

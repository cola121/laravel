<?php

namespace App\Http\Controllers\Wxapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Actors;
use App\Lib\CommonUtils;
use App\Lib\VideoTypes;
class VideoIndexController extends Controller
{
    public function index() {

        $result = Video::where('video_id', '>', 0)->limit(20)->orderBy('year', 'desc')->get();

//        $url = 'https://api.douban.com/v2/movie/subject/1764796';
//        $result = CommonUtils::requestUrl($url);
//        $result = json_decode($result, true);
//        $result = Video::all();
        foreach ($result as $row) {
            $video['title'] = $row->title;
            $video['title_en'] = $row->title_en;
            $video['year'] = date('Y-m-d', $row->year);
            $video['points'] = $row->points;
            $video['types'] = VideoTypes::returnTypeArrValue($row->types);
            $video['actors'] = $row->actors;
            $video['directors'] = $row->directors;

            $videos[] = $video;
        }
echo "<pre>";print_r($videos);
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

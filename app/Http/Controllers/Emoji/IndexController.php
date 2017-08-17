<?php

namespace App\Http\Controllers\Emoji;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Emoticon;
use App\Lib\CommonUtils;
use App\Lib\VideoTypes;
use App\Lib\FuncUtils;

class VIndexController extends Controller
{
    public function index() {

        $result = Emoticon::where('video_id', '>', 0)->limit(20)->orderBy('year', 'desc')->get();

        foreach ($result as $row) {
            $video['title'] = $row->title;
            $video['title_en'] = $row->title_en;
            $video['year'] = date('Y-m-d', $row->year);
            $video['points'] = $row->points;
            $video['types'] = VideoTypes::returnTypeArrValue($row->types);
            $video['pic']['small'] = $row->pic_small;
            $video['pic']['medium'] = $row->pic_medium;
            $video['pic']['large'] = $row->pic_large;
            $videos[] = $video;
        }

        $video = new Video();
        $list['videos'] = $videos;
        $result = $video->getTvRecomment();
        foreach ($result as $row) {
            $tv['title'] = $row->title;
            $tv['title_en'] = $row->title_en;
            $tv['year'] = date('Y-m-d', $row->year);
            $tv['points'] = $row->points;
            $tv['types'] = VideoTypes::returnTypeArrValue($row->types);
            $tv['pic']['small'] = $row->pic_small;
            $tv['pic']['medium'] = $row->pic_medium;
            $tv['pic']['large'] = $row->pic_large;
            $tvs[] = $tv;
        }
        $list['tvs'] = $tvs;

        FuncUtils::getSuccess($list);
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

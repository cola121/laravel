<?php

namespace App\Http\Controllers\Wxapi;

use App\Models\VideoImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Actors;
use App\Lib\CommonUtils;
use App\Lib\VideoTypes;
use App\Lib\FuncUtils;

class VideoInfoController extends Controller
{

    /**获取资源详情
     * @param Request $request
     * @param $id video_id
     */
    public function index(Request $request, $id)
    {

        $result = Video::find($id);
        $video['title'] = $result->title;
        $video['title_en'] = $result->title_en;
        $video['year'] = date('Y-m-d', $result->year);
        $video['points'] = $result->points;
        $video['types'] = VideoTypes::returnTypeArrValue($result->types);
        $video['summary'] = $result->summary;
        $video['pic']['small'] = $result->pic_small;
        $video['pic']['medium'] = $result->pic_medium;
        $video['pic']['large'] = $result->pic_large;
        $video['images'] = [];
        $images = Video::find($id)->getVideoImages;
        foreach ($images as $row) {
            $video['images'][] = $row->image_name;
        }
       // $actors = Actors::find([1054450,1002676,1031848,1031912]);
        $actor = new Actors();
        $actors = $actor->returnActorName($result->actors);
        echo "<pre>";print_r($actors);
//        $videos[] = $video;
//
//        FuncUtils::getSuccess($videos);
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

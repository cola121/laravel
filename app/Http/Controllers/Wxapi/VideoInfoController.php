<?php

namespace App\Http\Controllers\Wxapi;

use App\Models\VideoImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Actors;
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

        $video = array();
        $result = Video::find($id);
        $video['title'] = $result->title;
        $video['title_en'] = $result->title_en;
        $video['year'] = date('Y-m-d', $result->year);
        $video['points'] = $result->points;
        $video['types'] = VideoTypes::returnTypeArrValue($result->types);
        $video['summary'] = $result->summary;
        $video['aks'] = $result->aka;
        $video['pic']['small'] = $result->pic_small;
        $video['pic']['medium'] = $result->pic_medium;
        $video['pic']['large'] = $result->pic_large;
        $video['images'] = [];
        $images = Video::find($id)->getVideoImages;
        foreach ($images as $row) {
            $video['images']['small'][] = VideoImage::SMALL_IMAGE_LINK.$row->image_name;
            $video['images']['big'][] = VideoImage::BIG_IMAGE_LINK.$row->image_name;
        }
        //演员
        $video['actors']= [];
        $actor = new Actors();
        $res = $actor->returnActorName($result->actors);
        foreach ($res as $val) {
            $actorInfo['name'] = $val->name;
            $actorInfo['avatar']['small'] = $val->avatar_small;
            $actorInfo['avatar']['medium'] = $val->avatar_medium;
            $actorInfo['avatar']['large'] = $val->avatar_large;
            $video['actors'][] = $actorInfo;
        }
        //导演
        $video['directors']= [];
        $director = new Actors();
        $res = $director->returnActorName($result->directors);
        foreach ($res as $val) {
            $directorInfo['name'] = $val->name;
            $directorInfo['avatar']['small'] = $val->avatar_small;
            $directorInfo['avatar']['medium'] = $val->avatar_medium;
            $directorInfo['avatar']['large'] = $val->avatar_large;
            $video['directors'][] = $directorInfo;
        }

        FuncUtils::getSuccess($video);
    }

}

<?php

namespace App\Http\Controllers\Wxapi;

use App\Models\VideoImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Actors;
use App\Lib\CommonUtils;
use App\Lib\VideoTypes;
use App\Lib\getDouBanMovieInfo;
class IndexController extends Controller
{
    public function test(Request $request) {
        set_time_limit(0);
        //$this->save250(0);
        $this->saveMovies(200);
//        $result = Actors::where('db_id', 10023)->get();
//        if ($result->first()) { }
//        if ($result->isEmpty()) {echo 'empty'; }
//        if ($result->count()) { }
      // $this->saveTime();
      // $this->updateInfo();
//        $url = 'https://api.douban.com/v2/movie/subject/1764796';
//        $result = CommonUtils::requestUrl($url);
//        $result = json_decode($result, true);
//        $result = Video::all();
      //echo "<pre>";print_r($result);


    }

    public function save250($start)
    {
        $url = 'https://api.douban.com/v2/movie/top250?start='.$start;
        $result = CommonUtils::requestUrl($url);
        //echo "<pre>";print_r(json_decode($result, true));
        $result = json_decode($result, true);

        foreach ($result['subjects'] as $row) {
            $video = Video::firstOrNew(['db_id' => $row['id']]);
            if ($video->isEmpty()) {
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

        }
        if ($start < 240) {
            $start = intval($start) + 20;
            return $this->save250($start);
        } else {
            echo "ok";
        }
    }


    public function saveMovies($start)
    {
       // $url = 'https://api.douban.com/v2/movie/in_theaters?start='.$start;
        //$url = 'https://api.douban.com/v2/movie/us_box';
        $url = 'https://api.douban.com/v2/movie/search?tag=科幻&start='.$start;
        $result = CommonUtils::requestUrl($url);
        //echo "<pre>";print_r(json_decode($result, true));
        $result = json_decode($result, true);

        foreach ($result['subjects'] as $row) {
            $video = Video::firstOrNew(['db_id' => $row['id']]);
            if (!$video->db_id) {
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
        }

        echo "ok";
    }

    public function updateInfo()
    {
        $videos = Video::whereBetween('video_id', [276, 298])->get();
        foreach ($videos as $video)
        {
            $dbID = $video->db_id;
            $id = $video->video_id;echo "$id"."<br>";
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
                $this->saveActor($result['casts'], 'actor');
                $this->saveActor($result['directors'], 'director');
            } else {
                echo "<pre>";print_r($result);
            }

        }
        echo 'ok';
    }

    public function saveTime()
    {
//        $url = 'https://movie.douban.com/subject/1292063';
//        $info = new getDouBanMovieInfo($url);
//        $images = $info->getMovieImgs();
//        if (count($images) > 0) {
//            echo 'in';
//        }
//        var_dump($images);
//        echo "<pre>";print_r($info->getMovieImgs());exit;
        $videos = Video::whereBetween('video_id', [276, 298])->get();
        foreach ($videos as $video) {
            $dbID = $video->db_id;
            $id = $video->video_id;
            $url = 'https://movie.douban.com/subject/'.$dbID;
            $info = new getDouBanMovieInfo($url);
            $movies = $info->getMovieYear();
            $video->update([
                'year' => strtotime($info->getMovieYear()),
                'duration' =>  $info->getMovieInfoByParttern($info->longParttern) ? $info->getMovieInfoByParttern($info->longParttern) : 0
            ]);

            $images = $info->getMovieImgs();
            if (count($images) > 0) {
                foreach ($images as $row) {
                    $vImage = new VideoImage();
                    $vImage->image_name = $row;
                    $vImage->video_id = $id;
                    $vImage->save();
                }
            }

        }

        echo 'ok';
    }

    public function saveActor($actorArr, $type)
    {
        if (!empty($actorArr)) {
            foreach ($actorArr as $row) {;
                $result = Actors::where('name', $row['name'])->first();
                //echo "<pre>";print_r($result);
                if (!$result) {
                    $actor = new Actors();
                    $actor->db_id = $row['id'] ? $row['id'] : 0;
                    $actor->name = $row['name'];
                    $actor->u_type = $type;
                    $actor->avatar_small = $row['avatars']['small'] ? $row['avatars']['small'] : '';
                    $actor->avatar_medium = $row['avatars']['medium'] ? $row['avatars']['medium'] : '';
                    $actor->avatar_large = $row['avatars']['large'] ? $row['avatars']['large'] : '';
                    $actor->save();
                } else {
                    $result->update(['u_type' => $type]);
                }
            }
        }

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

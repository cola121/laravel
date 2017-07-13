<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Models\Actors;
use App\Models\VideoImage;
use App\Models\MultiPurpose;
use App\Lib\CommonUtils;
use App\Lib\VideoTypes;
use App\Lib\getDouBanMovieInfo;

class SaveMovie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SaveMovie';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '读取豆瓣电影信息保存';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('start');
        set_time_limit(0);
        $purpose = MultiPurpose::where('purpose', 'saveStart')->first();
        $startNum = $purpose->num_a;
        $allVideo = Video::all()->count();
        if ($startNum < $allVideo) {
            $next = $startNum + 20;
            $videos = Video::whereBetween('video_id', [$startNum, $next])->get();
            foreach ($videos as $video)
            {
                $dbID = $video->db_id;
                $id = $video->video_id;echo "$id"."<br>";
                $url = 'https://api.douban.com/v2/movie/subject/'.$dbID;
                $result = CommonUtils::requestUrl($url);
                $result = json_decode($result, true);
                if (!isset($result['code'])) {
                    $purpose->update(['num_a' => $next]);
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
                    var_dump($result);
                }

            }
        } else {
            $purposeb = MultiPurpose::where('purpose', 'saveTimeStart')->first();
            $startNumb = $purposeb->num_a;
            $nextb = $startNumb + 20;
            if ($startNumb < $allVideo) {
                $videos = Video::whereBetween('video_id', [$startNumb, $nextb])->get();
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
                    $purposeb->update(['num_a' => $nextb]);
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
            }
        }



        $this->info('end');
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


}

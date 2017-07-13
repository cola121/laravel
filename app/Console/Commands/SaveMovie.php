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

    const queryArr = [
        1 => '%E5%96%9C%E5%89%A7', //喜剧
        2 => '%E5%89%A7%E6%83%85', //剧情
        3 => '%E7%88%B1%E6%83%85', //爱情
        4 => '%E5%8A%A8%E4%BD%9C', //动作
        5 => '%E6%82%AC%E7%96%91', //悬疑
        6 => '%E7%8A%AF%E7%BD%AA', //犯罪
        7 => '%E6%81%90%E6%80%96', //恐怖
        8 => '%E9%9D%92%E6%98%A5', //青春
        9 => '%E5%8A%B1%E5%BF%97', //励志
        10 => '%E6%88%98%E4%BA%89', //战争
        11 => '%E6%96%87%E8%89%BA', //文艺
        12 => '%E9%BB%91%E8%89%B2%E5%B9%BD%E9%BB%98', //黑色幽默
        13 => '%E4%BC%A0%E8%AE%B0', //传记
        14 => '%E6%83%85%E8%89%B2', //情色
        15 => '%E6%9A%B4%E5%8A%9B', //暴力
        16 => '%E9%9F%B3%E4%B9%90', //音乐
        17 => '%E5%AE%B6%E5%BA%AD', //家庭
    ];

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
        $multi = new MultiPurpose();
        $multi->purpose = 'cronlog';
        $multi->str_a = date('Y-m-d H:i:s');
        $multi->save();
        $this->info('start');
        set_time_limit(0);
        $purpose = MultiPurpose::where('purpose', 'SearchMoiveTag')->first();
        $startNum = $purpose->num_b;
        $searchIndex = $purpose->num_a;
        if ($searchIndex <= 17) {
            $tagArr = self::queryArr;
            $tag = $tagArr[$searchIndex];
            $url = 'https://api.douban.com/v2/movie/search?tag='.$tag.'&start='.$startNum;
            $result = CommonUtils::requestUrl($url);
            //echo "<pre>";print_r(json_decode($result, true));
            $result = json_decode($result, true);
            $searchIndexNew = $startNum == 200 ? $searchIndex + 1 : $searchIndex;
            $next = $startNum == 200 ? 0 : $startNum + 20;
            if (!isset($result['code'])) {
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
                    $hasIn = true;
                }
            } else {
                $multi = new MultiPurpose();
                $multi->purpose = 'Searchlog';
                $multi->str_a = $result['msg'];
                $multi->num_a = $result['code'];
                $multi->num_b = $startNum;
                $multi->num_c = $searchIndex;
                $multi->save();
            }

            if ($hasIn) {
                $purpose->update(['num_a' => $searchIndexNew, 'num_b' => $next]);
            }
        } else {
            $this->updateInfo();
        }

        $this->info('end');
    }

    public function updateInfo ()
    {
        $purpose = MultiPurpose::where('purpose', 'saveStart')->first();
        $startNum = $purpose->num_a;
        $allVideo = Video::all()->count();
        if ($startNum < $allVideo) {
            $next = $startNum + 20 > $allVideo ? $allVideo : $startNum + 20;
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
                    $multi = new MultiPurpose();
                    $multi->purpose = 'Updatelog';
                    $multi->str_a = $result['msg'];
                    $multi->num_a = $result['code'];
                    $multi->num_b = $startNum;
                    $multi->num_c = $id;
                    $multi->save();
                }

            }
        } else {
            $purposeb = MultiPurpose::where('purpose', 'saveTimeStart')->first();
            $startNumb = $purposeb->num_a;
            $nextb = $startNumb + 20 > $allVideo ? $allVideo : $startNumb + 20;
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

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MultiPurpose;
use App\Lib\CommonUtils;

class SaveEmoji extends Command
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
    protected $description = '采集表情包';


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
        $purpose = MultiPurpose::where('purpose', 'SearchEmoji')->first();
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
                        $video->title = $row['title']; //��Ӱ��
                        $video->db_id = $row['id']; //����ID
                        $video->title_en = $row['original_title']; //ԭ��
                        $video->channel = $row['subtype']; //Ƶ��
                        $video->year = strtotime($row['year']); //���
                        $video->pic_large  = $row['images']['large']; //��ͼ
                        $video->pic_small  = $row['images']['small']; //Сͼ
                        $video->pic_medium   = $row['images']['medium']; //��ͼ
                        $video->points = $row['rating']['average']; //����
                        $video->summary = '';
                        $video->actors  = $this->arrToStr($row['casts'], 'id'); //��Ա
                        $video->directors  = $this->arrToStr($row['directors'], 'id');; //����
                        $video->types  = $this->returnTypes($row['genres']); //����
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

        //$purpose = MultiPurpose::where('purpose', 'saveStart')->first();
        //$startNum = $purpose->num_a;
        $allVideo = Video::all()->count();
        $this->updateInfo2($allVideo);

//        if ($startNum < $allVideo) {
//            $hasFail = false;
//            $next = $startNum + 20 > $allVideo ? $allVideo : $startNum + 20;
//            $videos = Video::whereBetween('video_id', [$startNum, $next])->get();
//            foreach ($videos as $video)
//            {
//                $dbID = $video->db_id;
//                $id = $video->video_id;echo "$id"."<br>";
//                $url = 'https://api.douban.com/v2/movie/subject/'.$dbID;
//                $result = CommonUtils::requestUrl($url);
//                $result = json_decode($result, true);
//                if (!isset($result['code'])) {
//                    $purpose->update(['num_a' => $next]);
//                    $video->countries = $this->arrToStr($result['countries']);
//                    $video->summary = $result['summary'];
//                    $video->aka = $this->arrToStr($result['aka']);
//                    $video->actors  = $this->arrToStr($result['casts'], 'id'); //��Ա
//                    $video->directors  = $this->arrToStr($result['directors'], 'id'); //����
//                    $video->types  = $this->returnTypes($result['genres']); //����
//                    $video->save();
//                    $this->saveActor($result['casts'], 'actor');
//                    $this->saveActor($result['directors'], 'director');
//                } else {
//                    $multi = new MultiPurpose();
//                    $multi->purpose = 'Updatelog';
//                    $multi->str_a = $result['msg'];
//                    $multi->num_a = $result['code'];
//                    $multi->num_b = $startNum;
//                    $multi->num_c = $id;
//                    $multi->save();
//                    $hasFail = true;
//                    if ($result['code'] = 112) {
//                        break;
//                    }
//                }
//            }
//
//            if ($hasFail) {
//                $this->updateInfo2($allVideo);
//            }
//
//        } else {
//            $this->updateInfo2($allVideo);
//        }
    }

    public function updateInfo2($allVideo)
    {
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
                var_dump($id,$movies);
                if ($info->getMovieYear()) {
                    $video->update([
                        'year' => strtotime($info->getMovieYear()),
                        'duration' =>  $info->getMovieInfoByParttern($info->longParttern) ? $info->getMovieInfoByParttern($info->longParttern) : 0
                    ]);
                    $purposeb->update(['num_a' => $nextb]);
                    $images = $info->getMovieImgs();
                    if (count($images) > 0 && $startNumb > 800) {
                        foreach ($images as $row) {
                            $vImage = new VideoImage();
                            $vImage->image_name = $row;
                            $vImage->video_id = $id;
                            $vImage->save();
                        }
                    }
                } else {
                    return false;
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

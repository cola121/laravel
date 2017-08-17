<?php

namespace App\Http\Controllers\Wxapi;

use App\Models\VideoImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Emoticon;
use App\Models\Actors;
use App\Models\MultiPurpose;
use App\Lib\CommonUtils;
use App\Lib\VideoTypes;
use App\Lib\getDouBanMovieInfo;
class IndexController extends Controller
{
    const category = [
        5 => '1001', //金馆长
        7 => '1002', //张学友
        8 => '1003', //教皇
        9 => '1004', //尔康
        10 => '1005', //兵库北
        116 => '1006', //doge
        13 => '1013', //其他
        14 => '1014', //熊猫头
        15 => '1015', //蘑菇头
        19 => '1019', // 芮小凸
        17 => '1017', // 猥琐猫
        20 => '1020', // 阿狮马
        22 => '1022', //彼尔德
        136 => '1023', //喷雾
        134 => '1024', //打电话
        132 => '1025', //还有这种操作
        129 => '1026', //信仰心
        125 => '1027', //可达鸭
        123 => '1028', //微笑
        122 => '1029', //肥宅
        117 => '1030', //爱你
        116 => '1006', //doge
        115 => '1032', //皮皮虾
        111 => '1033', //我可能是假的
        108 => '1034', //鹦鹉兄弟
        104 => '1035', //小叮当
        103 => '1036', //猫
        102 => '1037', //痞老板
        101 => '1038', //突然系列
        98 => '1039', //滑稽
        97 => '1040', //小坏坏
        96 => '1041', //贱萌绿毛
        94 => '1042', //黑恶势力
        93 => '1043', //猫老师
        84 => '1044', //波动猫
        81 => '1045', //葫芦娃
        80 => '1046', //hamham
        79 => '1047', //猥琐萌
        74 => '1048', //那年那兔
        73 => '1049', //悲伤蛙
        72 => '1050', //咸鱼
        71 => '1051', //熊本熊
        65 => '1052', //仓鼠
        61 => '1053', //北方栖姬
        57 => '1054', //sadayuki
        52 => '1055', //贱公公
        24 => '1056', //装逼
        25 => '1057', //耍贱
        26 => '1058', //挑衅
        29 => '1059', //大便
        27 => '1060', //打斗
        28 => '1061', //红包
        85 => '1062', //攻击
        82 => '1063', //巴拉拉能量
        68 => '1064', //喝饮料
        63 => '1065', //扔狗
        87 => '1066', //张国伟
        86 => '1067', //傅园慧
        83 => '1068', //薛之谦
        70 => '1069', //胡歌
        62 => '1070', //江疏影
        58 => '1071', //张馨予
        56 => '1072', //方脸
        55 => '1073', //宋民国
        54 => '1074', //科比
        53 => '1075', //邓紫棋
        51 => '1076', //宋仲基
        50 => '1077', //papi酱
        47 => '1078', //鹿晗
        46 => '1079', //迪丽热巴
        45 => '1080', //宋小宝
        131 => '1081', //守望先锋
        130 => '1082', //全职高手
        126 => '1083', //人民的名义
        119 => '1084', //怪诞小镇
        112 => '1085', //春晚
        99 => '1086', //行尸走肉
        95 => '1087', //阴阳师
        92 => '1088', //王者荣耀
        91 => '1089', //英雄联盟
        90 => '1090', //炉石传说
        77 => '1091', //老九门
        76 => '1092', //中国新歌声
        135 => '1093', //小仙男
        133 => '1094', //武功
        128 => '1095', //仙女
        127 => '1096', //绿
        124 => '1097', //围观
        121 => '1098', //怎么回事
        120 => '1099', //想不到吧
        118 => '1100', //修仙
        114 => '1101', //记仇
        113 => '1102', //小拳拳
        110 => '1103', //五福
        109 => '1104', //喝酒
        107 => '1105', //大字报
        106 => '1106', //学习
        105 => '1107', //圣诞节
        88 => '1108', //迷妹专用
        78 => '1109', //赞
        75 => '1110', //老司机
        67 => '1111', //跪
        66 => '1112', //水果
        59 => '1113', //高考
        43 => '1114', //吃
        41 => '1115', //丢
        40 => '1116', //搂
        39 => '1117', //指
        38 => '1118', //举
        37 => '1119', //持
        36 => '1120', //饮料
        31 => '1121', //剑
        30 => '1122', //枪
    ];

    public function test() {
        echo "start";
        $cateArr = self::category;
        $url = 'https://api.jiefu.tv/app2/api/dt/tag/allList.html?pageNum=0&pageSize=48';

        $result = CommonUtils::requestUrl($url);
       // echo "<pre>";print_r(json_decode($result, true));exit;
        $result = json_decode($result, true);
         echo "<pre>";print_r($result);exit;
       // echo "<pre>";
        $i = '1';
        foreach ($result['data'] as $row) {
           foreach ($row['tagList'] as $val) {
               echo  $i.' => '. "'".$val['id']."'". ',' ."<br>";
               $i++;
           }
        }

//        set_time_limit(0);
//
//        $url = 'https://movie.douban.com/subject/25980443';
//        //$url = 'https://movie.douban.com/subject/20438962';
//        $info = new getDouBanMovieInfo($url);
//        $movies = $info->getMovieYear();
//        var_dump($movies);
//        var_dump(strtotime($movies));
        //$this->save250(0);
      //  $this->saveMovies(20);
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
        $url = 'https://api.douban.com/v2/movie/search?tag=喜剧&start='.$start;
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
//            if (count($images) > 0) {
//                foreach ($images as $row) {
//                    $vImage = new VideoImage();
//                    $vImage->image_name = $row;
//                    $vImage->video_id = $id;
//                    $vImage->save();
//                }
//            }

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

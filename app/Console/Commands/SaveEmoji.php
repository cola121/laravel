<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MultiPurpose;
use App\Models\Emoticon;
use App\Lib\CommonUtils;

class SaveEmoji extends Command
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

    protected $startArr = [
        7 => '14',
        8 => '15',
        9 => '19',
        10 => '17',
        11 => '20',
        12 => '22',
        13 => '136',
        14 => '134',
        15 => '132',
        16 => '129',
        17 => '125',
        18 => '123',
        19 => '122',
        20 => '117',
        21 => '116',
        22 => '115',
        23 => '111',
        24 => '108',
        25 => '104',
        26 => '103',
        27 => '102',
        28 => '101',
        29 => '98',
        30 => '97',
        31 => '96',
        32 => '94',
        33 => '93',
        34 => '84',
        35 => '81',
        36 => '80',
        37 => '79',
        38 => '74',
        39 => '73',
        40 => '72',
        41 => '71',
        42 => '65',
        43 => '61',
        44 => '57',
        45 => '52',
        46 => '24',
        47 => '25',
        48 => '26',
        49 => '29',
        50 => '27',
        51 => '28',
        52 => '85',
        53 => '82',
        54 => '68',
        55 => '63',
        56 => '87',
        57 => '86',
        58 => '83',
        59 => '70',
        60 => '62',
        61 => '58',
        62 => '56',
        63 => '55',
        64 => '54',
        65 => '53',
        66 => '51',
        67 => '50',
        68 => '47',
        69 => '46',
        70 => '45',
        71 => '131',
        72 => '130',
        73 => '126',
        74 => '119',
        75 => '112',
        76 => '99',
        77 => '95',
        78 => '92',
        79 => '91',
        80 => '90',
        81 => '77',
        82 => '76',
        83 => '135',
        84 => '133',
        85 => '128',
        86 => '127',
        87 => '124',
        88 => '121',
        89 => '120',
        90 => '118',
        91 => '114',
        92 => '113',
        93 => '110',
        94 => '109',
        95 => '107',
        96 => '106',
        97 => '105',
        98 => '88',
        99 => '78',
        100 => '75',
        101 => '67',
        102 => '66',
        103 => '59',
        104 => '43',
        105 => '41',
        106 => '40',
        107 => '39',
        108 => '38',
        109 => '37',
        110 => '36',
        111 => '31',
        112 => '30',
    ];

    const url = [
        'http://service.magicemoticon.cccwei.com/topic/list?page=1&item_count=3',//类别列表
        'http://service.magicemoticon.cccwei.com/topic/detail?topic_id=162&item_count=300',//类别详细
        'http://service.magicemoticon.cccwei.com/template/list?item_count=15&page=2',//模板
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SaveEmoji';

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
        $this->info('start');
        set_time_limit(0);

        $this->getEmoji();

        $this->info('end');
    }

    public function getEmoji($cNum = 7, $start = 0)
    {
        $startC = $this->startArr;
        $multi = new MultiPurpose();
        $set = MultiPurpose::where('purpose', 'emojiSet')->first();
        if (!$set) {
            $multi->purpose = 'emojiSet';
            $multi->num_a = 0;
            $multi->num_b = 7;
            $multi->save();
            $start = 0;
            $cNum = 7;
            $category = $startC[7];
        } else {
            $start = $set['num_a'];
            $cNum = $set['num_b'];
            $category = $startC[$set['num_b']];
            if ($cNum > 112) {
                exit;
            }
        }
        $exp = [1119,1118,1117,1114,1090,1089,1088,1064,1061,1060,1059,1058,1057,1056,1053,1049,1047,1046,1036,1032,1019,1015,1014];

        if (in_array($category, $exp)) {
            $start = 0;
            $cNum = $cNum + 1;
            $category = $startC[$cNum];
        }

        $url = 'https://api.jiefu.tv/app2/api/dt/item/getByTag.html?tagId='.$category.'&pageNum='.$start.'&pageSize=50';

        $result = CommonUtils::requestUrl($url);
        $result = json_decode($result, true);

        if ($result['data']) {
            foreach ($result['data'] as $row) {
                $data['raw_image'] = $row['picPath'];
                $data['text'] = $row['name'];
                $data['edit_type'] = 2;
                $emoji = new Emoticon();
                $categoryArr = self::category;
                $data['category'] = $categoryArr[$category];
                $emoji->saveEmoji($data);
            }
            MultiPurpose::where('purpose', 'emojiSet')->update(
                ['num_a' => $start + 1]
            );
            echo "done";
        } else {
            $multi->purpose = 'emojiError';
            $multi->num_a = $start;
            $multi->num_b = $category;
            $multi->num_c = $cNum;
            $multi->str_long = $url;
            $multi->save();

            MultiPurpose::where('purpose', 'emojiSet')->update(
                [
                    'num_a' => 0,
                    'num_b' => $cNum + 1
                    ]
            );

            return $this->getEmoji($cNum, 0);
        }
    }

}
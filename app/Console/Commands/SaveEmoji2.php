<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MultiPurpose;
use App\Models\Emoticon;
use App\Lib\CommonUtils;

class SaveEmoji2 extends Command
{
    const category = [
        1 => '1001', //金馆长
        2 => '1002', //张学友
        3 => '1003', //教皇
        4 => '1004', //尔康
        5 => '1005', //兵库北
        6 => '1006', //doge
        7 => '1007', //
        8 => '1008', //
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
        $multi = new MultiPurpose();
        $multi->purpose = 'cronlog';
        $multi->str_a = date('Y-m-d H:i:s');
        $multi->save();
        $this->info('start');
        set_time_limit(0);

        $this->getEmoji();

        $this->info('end');
    }

    public function getEmoji($category = 1, $start = 0)
    {
        $multi = new MultiPurpose();
        $set = MultiPurpose::where('purpose', 'emojiSet')->first();
        if (!$set) {
            $multi->purpose = 'emojiSet';
            $multi->int_a = 0;
            $multi->int_b = 1;
            $multi->save();
            $start = 0;
            $category = 1;
        } else {
            $start = $set['int_a'] + 15;
            $category = $set['int_b'];
        }
        $url = 'https://api.inwotalk.com/biaoqingbao/pic/list?cate='.$category.'&start='.$start.'&limit=15';

        $result = CommonUtils::requestUrl($url);
        $result = json_decode($result, true);

        if ($result['data']) {
            foreach ($result['data'] as $row) {
                $emoji = new Emoticon();
                $categoryArr = self::category;
                $row['category'] = $categoryArr[$row['category']];
                $emoji->saveEmoji($row);
            }
            echo "done";
        } else {
            $category = '';
            $start = 0;
            $multi->purpose = 'emojiSet';
            $multi->int_a = $start;
            $multi->int_b = $category + 1;
            $multi->save();

            return $this->getEmoji($category, $start);
        }
    }

}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MultiPurpose;
use App\Models\Emoticon;
use App\Lib\CommonUtils;

class DownloadEmoji extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DownloadEmoji';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '下载表情包';


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
        $emojis = Emoticon::where('download_status', 0)
            ->orderBy('created_at', 'asc')
            ->take(20)
            ->get();

        foreach ($emojis as $emoji) {
            $raw = $emoji->raw_image;
            $full = $emoji->full_image;
            $category = $emoji->category;
            $id = $emoji->em_id;
            if ($raw) {
                $ext = strrchr($raw, '.');
                $name = $id."_".$category.'_raw';
                $resa = CommonUtils::GrabImage($raw, $category, $name);
                if ($resa) {
                    $emoji->raw_image = $category.'/'.$name.$ext;
                    $emoji->save();
                }
            }
            if ($full) {
                $ext = strrchr($full, '.');
                $name = $id."_".$category.'_full';
                $resb = CommonUtils::GrabImage($full, $category, $name);
                if ($resb) {
                    $emoji->full_image = $category.'/'.$name.$ext;
                    $emoji->save();
                }
            }

            if ($resa || $resb) {
                $emoji->download_status = 1;
                $emoji->save();
            }

        }
        exit;
    }

}
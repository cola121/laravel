<?php

namespace App\Http\Controllers\Wxapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmojiMakeController extends Controller
{
    public function emojiMaker()
    {
        $receive = $_POST;
        $backImage = new \Imagick($receive['backImageUrl']);
        $background = $backImage->getImageGeometry();
        $backgroundWidth = $background['width'];
        $backgroundHeight = $background['height'];

        foreach ($receive as $frontItme) {
            $frontObj = new \Imagick($frontItme['url']);
            $frontObj->thumbnailImage($frontItme['width'], $frontItme['height']); //缩放参数
            if ($frontItme['rote']) { //旋转参数
                $frontObj->rotateImage(new \ImagickPixel(), $frontItme['angle']);
            }
            //合并
            $backImage->compositeImage( $frontObj, \imagick::COMPOSITE_DEFAULT , $frontItme['xposition'], $frontItme['yposition'] );
            $frontObj->destroy();
        }
        $tempName = time().rand(1000,9999);
        $ext = $backImage->getImageFormat();
        $res = $backImage->writeimage(public_path().'/temp/emoji/'. $tempName.'.'.$ext);
        if ($res) {
            $backImage->destroy();
            $list['tempUrl'] = 'http://www.hankele.cn/temp/emoji/'.$tempName.'.'.$ext;
            FuncUtils::getSuccess($list);
        } else {

        }

    }
}

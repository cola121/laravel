<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/7/5
 * Time: 15:06
 */
namespace App\Lib;

class CommonUtils {

    static function requestUrl($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    static function saveImage($path) {
        if(!preg_match('/\/([^\/]+\.[a-z]{3,4})$/i',$path,$matches))
            die('Use image please');
        $image_name = strToLower($matches[1]);
        $extend = substr(strrchr($image_name, '.'), 1);
        return $matches;
        $ch = curl_init ($path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $img = curl_exec ($ch);
        curl_close ($ch);
        $fp = fopen($image_name,'w');
        fwrite($fp, $img);
        fclose($fp);
    }

    static function GrabImage($url, $type='' ,$filename='',$dir=''){
        if(empty($url)){
            return false;
        }
        $ext = strrchr($url, '.');

        //为空就当前目录
        if(empty($dir))$dir = public_path().'/emoji/'.$type;

        if (!realpath($dir)) {
            mkdir($dir, 0777);
            chmod($dir, 0777);
        }

        //目录+文件
        $filename = $dir . (empty($filename) ? '/'.time().$ext : '/'.$filename.$ext);
        //开始捕捉
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
        $size = strlen($img);
        $fp2 = fopen($filename, "a");
        fwrite($fp2, $img);
        fclose($fp2);
        return $filename;
    }
}
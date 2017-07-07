<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/7/7
 * Time: 14:25
 */
namespace App\Lib;
class VideoTypes {
    const typesArr = array(
        1001 => '剧情',
        1002 => '爱情',
        1003 => '喜剧',
        1004 => '科幻',
        1005 => '动作',
        1006 => '悬疑',
        1007 => '犯罪',
        1008 => '恐怖',
        1009 => '青春',
        1010 => '励志',
        1011 => '战争',
        1012 => '文艺',
        1013 => '黑色',
        1014 => '幽默',
        1015 => '传记',
        1016 => '情色',
        1017 => '暴力',
        1018 => '音乐',
        1019 => '家庭'
    );

    /**
     * 返回类型值
     * @param $key int
     * @return mixed
     */
    static function returnTypeValue ($key)
    {
        $typesArr = self::typesArr;
        return $typesArr[$key];
    }


    /**
     * 返回类型对应主键
     * @param $val str
     * @return int|string
     */
    static function returnTypeKey ($val)
    {
        $typesArr = self::typesArr;
        foreach ($typesArr as $key => $row) {
            if ($row == $val) {
                return $key;
            }
        }
        return '';
    }
}
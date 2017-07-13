<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/7/13
 * Time: 17:13
 */
namespace App\Lib;

use App\Lib\JsonResult;

class FuncUtils {

    /**
     * json返回值(正确状态返回)
     * @param array|string| $returnArr 状态码(正整数)
     * @return json 处理结果
     */
    public static function getSuccess($data = array())
    {
        if (!$data) {
            $data = array();
        }
        echo new JsonResult(self::getArrChangeStr($data));
        exit();
    }

    /**
     * json返回值(错误状态返回)
     * FunUtils::getError('', 0, JsonResult::SUCCESS, array()); 成功状态返回,作用于特殊处理
     * @param array|string| $returnArr 状态码(正整数)
     * @return json 处理结果
     */
    public static function getError($message = 'error', $status = 100, $data = null)
    {
        echo new JsonResult($message, $status, $level = JsonResult::INFO, self::getArrChangeStr($data));
        exit();
    }

    /**
     * json返回值(错误状态返回)
     * @return 处理结果
     */
    public static function getArrChangeStr($arr)
    {
        if (is_array($arr)) {
            foreach ($arr as $key => $val) {
                if (is_array($val)) {
                    $arr[$key] = count($val) > 0 ? self::getArrChangeStr($val) : array();
                } else {
                    $arr[$key] = (string)$val;
                }
            }
        } else {
            $arr = (string)$arr;
        }
        return $arr;
    }

}
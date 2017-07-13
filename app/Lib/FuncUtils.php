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
     * json����ֵ(��ȷ״̬����)
     * @param array|string| $returnArr ״̬��(������)
     * @return json ������
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
     * json����ֵ(����״̬����)
     * FunUtils::getError('', 0, JsonResult::SUCCESS, array()); �ɹ�״̬����,���������⴦��
     * @param array|string| $returnArr ״̬��(������)
     * @return json ������
     */
    public static function getError($message = 'error', $status = 100, $data = null)
    {
        echo new JsonResult($message, $status, $level = JsonResult::INFO, self::getArrChangeStr($data));
        exit();
    }

    /**
     * json����ֵ(����״̬����)
     * @return ������
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
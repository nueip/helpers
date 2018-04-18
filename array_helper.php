<?php

namespace nueip\helpers;

/**
 * Array Helper
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @version 1.0.0
 * @see     https://github.com/yidas/php-helpers
 * @example 
 *  \nueip\helpers\ArrayHelper::indexBy($modelData, 's_sn');
 */
class ArrayHelper
{
    /**
     * Index by Key
     *
     * @param array $array Array data for handling
     * @param string $key  Array key for index key
     * @param boolean $obj2array Array content convert to array (when object)
     * @return array Result with indexBy Key
     */
    public static function indexBy(Array &$array, $key='id', $obj2array = false)
    {
        $tmp = [];

        foreach ($array as $row) {

            if (is_object($row) && isset($row->$key)) {
                
                $tmp[$row->$key] = $obj2array ? (array)$row : $row;

            } 
            elseif (is_array($row) && isset($row[$key])) {

                $tmp[$row[$key]] = $row;
            }
        }

        return $array = $tmp;
    }
    
    /**
     * 從目標資料中的指定欄位搜集資料，並組成陣列清單
     *
     * 資料陣列，格式：array(stdClass|array usersInfo1, stdClass|array usersInfo2, stdClass|array usersInfo3, ............);
     * 使用範例：
     * - $data = $this->db->select('*')->from('audit_list')->where(array('open_status'=>'1', 'reply_status'=>'0', 'm_sn'=>'40', 'rec_status' => '1'))->limit(100)->get()->result();
     * - 欄位 manager, sign_manager, c_user 值放在同一個一維陣列中
     * - $ssnList1 = \nueip\helpers\ArrayHelper::gatherData($data, array('manager', 'sign_manager','c_user'), 1);
     * - 欄位 manager 值放一個陣列, 欄位 sign_manager, c_user 值放同一陣列中，形成2維陣列 $dataList2 = ['manager' => [], 'other' => []];
     * - $ssnList2 = \nueip\helpers\ArrayHelper::gatherData($data, array('manager' => array('manager'), 'other' => array('sign_manager','c_user')), 1);
     *
     * 遞迴效率太差 - 改限最小定層數為1層，並1層時不用遞迴
     *
     * @author Mars.Hung <tfaredxj@gmail.com>
     *
     * @param array $data
     *            資料陣列
     * @param array $colNameList
     *            資料陣列中，目標資料的Key名稱
     * @param number $objLv
     *            資料物件所在層數
     * @param array $dataList
     *            遞迴用
     */
    public static function gatherData($data, $colNameList, $objLv = 1, $dataList = array())
    {
        $data = (array) $data;
        
        if (! empty($data)) {
            if ($objLv > 1) {
                // === 超過1層 ===
                foreach ($data as $k => $row) {
                    // 遞迴處理
                    $dataList = self::gatherData($row, $colNameList, $objLv - 1, $dataList);
                }
            } else {
                // === 1層 ===
                // 遍歷要處理的資料
                foreach ($data as $k => $row) {
                    $row = (array) $row;
                    // 遍歷目標欄位名稱
                    foreach ($colNameList as $tKey1 => $tCol) {
                        if (is_array($tCol)) {
                            // === 如果目標是二維陣列，輸出的資料也要依目標陣列的第一維度分類 ===
                            foreach ($tCol as $tKey2 => $tCol2) {
                                if (isset($row[$tCol2])) {
                                    $dataList[$tKey1][$row[$tCol2]] = $row[$tCol2];
                                }
                            }
                        } else {
                            // === 目標是一維陣列，不需分類 ===
                            if (isset($row[$tCol])) {
                                $dataList[$row[$tCol]] = $row[$tCol];
                            }
                        }
                    }
                }
            }
        }
        
        return $dataList;
    }

    /**
     * Groups an array by a given key.
     *
     * @param array $array The array to have grouping performed on
     * @param string $key The key to group or split by
     * @example
     *  $usersDeptData = [
     *      0 => [
     *          'd_sn' => '20',
     *          'u_sn' => '100',
     *      ],
     *      1 => [
     *          'd_sn' => '20',
     *          'u_sn' => '101',
     *      ],
     *      2 => [
     *          'd_sn' => '21',
     *          'u_sn' => '102',
     *      ]
     *  ];
     *  \nueip\helpers\ArrayHelper::groupBy($usersDeptData, 'd_sn');
     *  result : $usersDeptData = [
     *     '20' => [
     *         0 => [
     *             'd_sn' => '20',
     *             'u_sn' => '100',
     *         ],
     *         1 => [
     *             'd_sn' => '20',
     *             'u_sn' => '101',
     *         ],
     *     ],
     *     '21' => [
     *         0 => [
     *             'd_sn' => '21',
     *             'u_sn' => '100',
     *         ],
     *     ]
     *  ];
     *
     * @return array Returns a multidimensional array
     */
    public static function groupBy(array &$array, $key)
    {
        $grouped = [];

        foreach ($array as $row) {
            $valueForKey = null;

            if (is_object($row) && isset($row->{$key})) {
                $valueForKey = $row->{$key};
            } elseif (isset($row[$key])) {
                $valueForKey = $row[$key];
            }

            if (is_null($valueForKey)) {
                continue;
            }

            $grouped[$valueForKey][] = $row;
        }

        return $array = $grouped;
    }
}

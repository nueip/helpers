<?php

namespace nueip\helpers;

/**
 * Array Helper
 *
 * @example 
 *  \nueip\helpers\ArrayHelper::indexBy($modelData, 's_sn');
 */
class ArrayHelper
{
    /**
     * Index by Key
     * 
     * @example
     * \nueip\helpers\ArrayHelper::indexBy($data, 'd_sn');
     * \nueip\helpers\ArrayHelper::indexBy($data, ['d_sn', 'u_sn']);
     * 
     * @param mixed $data Array/stdClass data for handling
     * @param mixed $keys keys for index key (Array/string)
     * @param boolean $obj2array stdClass convert to array
     * @return mixed Result with indexBy Keys
     */
    public static function indexBy(&$data, $keys, $obj2array = false)
    {
        // Refactor Array $data structure by $keys
        return self::_refactorBy($data, $keys, $obj2array, $type = 'indexBy');
    }

    /**
     * Index Only by keys, No Data
     *
     * @example
     * \nueip\helpers\ArrayHelper::indexOnly($data, 'd_sn');
     * \nueip\helpers\ArrayHelper::indexOnly($data, ['d_sn', 'u_sn']);
     * 
     * @param array|stdClass $data Array/stdClass data for handling
     * @param string|array $keys
     * @param boolean $obj2array Array content convert to array (when object)
     */
    public static function indexOnly(&$data, $keys, $obj2array = false)
    {
        // Refactor Array $data structure by $keys
        return self::_refactorBy($data, $keys, $obj2array, $type = 'indexOnly');
    }

    /**
     * Get Data content by index
     *
     * Usage:
     * - $data = ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']];
     * - var_export(getContent($data)); // full $data content
     * - var_export(getContent($data, 'user')); // ['name' => 'Mars', 'birthday' => '2000-01-01']
     * - echo getContent($data, ['user', 'name']); // Mars
     * 
     * @param array $data
     * @param array|string $indexTo Content index of the data you want to get
     * @return array
     */
    public static function getContent(array $data, $indexTo = [])
    {
        //* Arguments prepare */
        $indexTo = (array) $indexTo;

        foreach ($indexTo as $idx) {
            if (is_array($data) && array_key_exists($idx, $data)) {
                // If exists, Get values by recursion
                $data = $data[$idx];
            } else {
                $data = [];
                break;
            }
        }

        return $data;
    }

    /**
     * Get Closest item by key
     * 
     * @author Gunter Chou
     * 
     * @param array $data
     * @param string|integer $needle
     * @param string $compareWith closest|more|less
     * @param callback $formatKey
     * @return mixed
     */
    public static function getClosest(array $data, $needle, $compareWith = 'closest', $formatKey = 'strtotime')
    {
        // If needle not is numeric, use formatKey callback to format.
        $needle = is_numeric($needle) ? $needle : call_user_func_array($formatKey, [$needle]);

        $compareWith = strtolower($compareWith);

        $closestKey = null;
        $closestItem = null;

        foreach ($data as $key => $item) {

            // If key not is numeric, use formatKey callback to format.
            $currentKey = is_numeric($key) ? $key : call_user_func_array($formatKey, [$key]);

            switch ($compareWith) {
                default:
                case 'closest':
                    $compare = true;
                    break;
                case 'more':
                    $compare = $needle <= $currentKey;
                    break;
                case 'less':
                    $compare = $needle >= $currentKey;
                    break;
                case 'morethan':
                    $compare = $needle < $currentKey;
                    break;
                case 'lessthan':
                    $compare = $needle > $currentKey;
                    break;
            }

            if ($compare && ($closestKey === null || abs($needle - $currentKey) < abs($needle - $closestKey))) {
                $closestKey = $currentKey;
                $closestItem = $item;
            }

        }

        return $closestItem;
    }

    /**
     * Get less than and equal closest item by key
     * 
     * @author Gunter Chou
     * 
     * @param array $data
     * @param mixed $needle
     * @return mixed
     */
    public static function getClosestLess(array $data, $needle)
    {
        return self::getClosest($data, $needle, 'less');
    }

    /**
     * Get more than and equal closest item by key
     * 
     * @author Gunter Chou
     * 
     * @param array $data
     * @param mixed $needle
     * @return mixed
     */
    public static function getClosestMore(array $data, $needle)
    {
        return self::getClosest($data, $needle, 'more');
    }

    /**
     * Get less than closest item by key
     * 
     * @param array $data
     * @param mixed $needle
     * @return mixed
     */
    public static function getClosestLessThan(array $data, $needle)
    {
        return self::getClosest($data, $needle, 'lessthan');
    }

    /**
     * Get more than closest item by key
     * 
     * @param array $data
     * @param mixed $needle
     * @return mixed
     */
    public static function getClosestMoreThan(array $data, $needle)
    {
        return self::getClosest($data, $needle, 'morethan');
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
     * 遞迴效率太差 - 改成遞迴到最後一層陣列後直接處理，不再往下遞迴
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
        // 將物件轉成陣列
        $data = is_object($data) ? (array) $data : $data;

        // 遍歷陣列 - 只處理陣列
        if (is_array($data) && !empty($data)) {
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
     * Unable to be grouped by $key, will be skipped
     * Extend $key to type:array, and group by array element order
     * 
     * @author Nick.Lai, Mars.Hung
     * @param array $array The array to have grouping performed on
     * @param string|array $key The key to group or split by
     * 
     * @example
     * \nueip\helpers\ArrayHelper::groupBy($data, 'd_sn');
     * \nueip\helpers\ArrayHelper::groupBy($data, ['d_sn', 'u_sn']);
     * 
     * @example
     *  $usersDeptData = [
     *      0 => [
     *          'd_sn' => '20',
     *          'u_sn' => '100',
     *          'date' => '2018-08-01',
     *      ],
     *      1 => [
     *          'd_sn' => '20',
     *          'u_sn' => '100',
     *          'date' => '2018-08-02',
     *      ],
     *      2 => [
     *          'd_sn' => '21',
     *          'u_sn' => '101',
     *          'date' => '2018-08-01',
     *      ]
     *  ];
     *  
     *  \nueip\helpers\ArrayHelper::groupBy($usersDeptData, 'd_sn');
     *  result : $usersDeptData = [
     *     '20' => [
     *         0 => [
     *             'd_sn' => '20',
     *             'u_sn' => '100',
     *             'date' => '2018-08-01',
     *         ],
     *         1 => [
     *             'd_sn' => '20',
     *             'u_sn' => '100',
     *             'date' => '2018-08-02',
     *         ],
     *     ],
     *     '21' => [
     *         0 => [
     *             'd_sn' => '21',
     *             'u_sn' => '101',
     *             'date' => '2018-08-01',
     *         ],
     *     ]
     *  ];
     * @example
     *  $usersDeptData = [
     *      0 => [
     *          'd_sn' => '20',
     *          'u_sn' => '100',
     *          'date' => '2018-08-01',
     *      ],
     *      1 => [
     *          'd_sn' => '20',
     *          'u_sn' => '100',
     *          'date' => '2018-08-02',
     *      ],
     *      2 => [
     *          'd_sn' => '21',
     *          'u_sn' => '101',
     *          'date' => '2018-08-01',
     *      ]
     *  ];
     *  
     *  \nueip\helpers\ArrayHelper::groupBy($usersDeptData, ['d_sn', 'u_sn']);
     *  result : $usersDeptData = [
     *     '20' => [
     *         '100' => [
     *             0 => [
     *                 'd_sn' => '20',
     *                 'u_sn' => '100',
     *                 'date' => '2018-08-01',
     *             ],
     *             1 => [
     *                 'd_sn' => '20',
     *                 'u_sn' => '100',
     *                 'date' => '2018-08-02',
     *             ],
     *         ],
     *     ],
     *     '21' => [
     *         '101' => [
     *             0 => [
     *                 'd_sn' => '21',
     *                 'u_sn' => '101',
     *                 'date' => '2018-08-01',
     *             ],
     *         ],
     *     ]
     *  ];
     *
     * @return array Returns a multidimensional array
     */
    public static function groupBy(&$data, $keys, $obj2array = false)
    {
        // Refactor Array $data structure by $keys
        return self::_refactorBy($data, $keys, $obj2array, $type = 'groupBy');
    }

    /**
     * Filter an array by a given key.
     * 
     * @example
     * \nueip\helpers\ArrayHelper::filterKey($data, ['s_sn', 'u_sn', 'remark']);
     * \nueip\helpers\ArrayHelper::filterKey($data, 's_sn, u_sn, remark']);
     * 
     * @author Gunter.Chou
     * @param array
     * @param array|string $keys
     * 
     * @return array
     */
    public static function filterKey(array $data, $keys)
    {
        if (is_string($keys)) {
            $keys = explode(',', $keys);
        }

        return array_map(function ($row) use ($keys) {
            $result = [];
            foreach ($keys as $key) {
                $result[$key] = $row[$key] ?? null;
            }
            return $result;
        }, $data);
    }

    /**
     * Array Diff Recursive
     * 
     * @example
     * \nueip\helpers\ArrayHelper::diffRecursive($newData, $oldData);
     * 
     *  @author Gunter Chou
     * @param array $Comparative
     * @param array $Comparison
     * @return array $outputDiff Result diff data
     */
    public static function diffRecursive($Comparative, $Comparison)
    {
        $outputDiff = [];
        foreach ($Comparative as $key => $value) {
            if (array_key_exists($key, $Comparison)) {
                if (is_array($value)) {
                    $recursiveDiff = self::diffRecursive($value, $Comparison[$key]);
                    if (!empty($recursiveDiff)) {
                        $outputDiff[$key] = $recursiveDiff;
                    }
                } elseif (!in_array($value, $Comparison, true) || $value !== $Comparison[$key]) {
                    $outputDiff[$key] = $value;
                }
            } elseif (!in_array($value, $Comparison, true)) {
                $outputDiff[$key] = $value;
            }
        }
        return $outputDiff;
    }

    /**
     * Array Sort Recursive
     *
     * @param array $srcArray
     * @param string $type ksort(default), krsort, sort, rsort
     */
    public static function sortRecursive(array &$srcArray, $type = 'ksort')
    {
        // Run ksort(default), krsort, sort, rsort
        switch ($type) {
            case 'ksort':
            default:
                ksort($srcArray);
                break;
            case 'krsort':
                krsort($srcArray);
                break;
            case 'sort':
                sort($srcArray);
                break;
            case 'rsort':
                rsort($srcArray);
                break;
        }

        // If child element is array, recursive
        foreach ($srcArray as $key => &$value) {
            is_array($value) && self::sortRecursive($value, $type);
        }
    }

    /**
     * **********************************************
     * ************** Private Function **************
     * **********************************************
     */

    /**
     * Refactor Array $data structure by $keys
     * 
     * @auth Mars.Hung
     * @see https://github.com/marshung24/helper/blob/master/src/ArrayHelper.php
     * 
     * @param array|stdClass $data Array/stdClass data for handling
     * @param string|array $keys
     * @param boolean $obj2array Array content convert to array (when object)
     * @param string $type indexBy(index)/groupBy(group)/only index,no data(indexOnly/noData)
     */
    protected static function _refactorBy(&$data, $keys, $obj2array = false, $type = 'index')
    {
        // 參數處理
        $keys = (array) $keys;

        $result = [];

        // 遍歷待處理陣列
        foreach ($data as $row) {
            // 旗標，是否取得索引
            $getIndex = false;
            // 位置初炲化 - 傳址
            $rRefer = &$result;
            // 可用的index清單
            $indexs = [];

            // 遍歷$keys陣列 - 建構索引位置
            foreach ($keys as $key) {
                $vKey = null;

                // 取得索引資料 - 從$key
                if (is_object($row) && isset($row->{$key})) {
                    $vKey = $row->{$key};
                } elseif (is_array($row) && isset($row[$key])) {
                    $vKey = $row[$key];
                }

                // 有無法取得索引資料，跳出
                if (is_null($vKey)) {
                    $getIndex = false;
                    break;
                }

                // 記錄可用的index
                $indexs[] = $vKey;

                // 本次索引完成
                $getIndex = true;
            }

            // 略過無法取得索引或索引不完整的資料
            if (!$getIndex) {
                continue;
            }

            // 變更位置 - 傳址
            foreach ($indexs as $idx) {
                $rRefer = &$rRefer[$idx];
            }

            // 將資料寫入索引位置
            switch ($type) {
                case 'index':
                case 'indexBy':
                default:
                    $rRefer = $obj2array ? (array) $row : $row;
                    break;
                case 'group':
                case 'groupBy':
                    $rRefer[] = $obj2array ? (array) $row : $row;
                    break;
                case 'indexOnly':
                case 'noData':
                    $rRefer = '';
                    break;
            }
        }

        return $data = $result;
    }
}

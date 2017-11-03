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
}

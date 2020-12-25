<?php

namespace nueip\helpers;

use Exception;

/**
 * System Helper
 *
 * Provide methods for handling system parameter control.
 *
 * @author  Mars Hung
 */
class SystemHelper
{

    /**
     * 最小記憶體設定
     *
     * - 以M為單位，不做單位轉換
     * - 如果現有可用記憶體大於本次設定值，不會縮減
     * - 建議：一般程式不超過512M，匯出程式不超過1024M
     * - 最大 2048M
     *
     * @uses memoryMoreThan($memorySize);
     * @example memoryMoreThan('256M');
     *
     * @param  string       $memorySize 記憶體大小，單位 M，預設 256M
     * @throws Exception
     * @return bool
     */
    public static function memoryMoreThan($memorySize = '256M'): bool
    {
        // 格式檢查：3~4位字數+字元M
        if (!preg_match('/^([0-9]{1,4})([mM])$/', trim($memorySize), $matches)) {
            throw new Exception('Memory size error!', 400);
        }

        // 最大值檢查
        if ($matches[1] > 2048) {
            throw new Exception('Memory size cannot greater than 2048M!', 400);
        }

        // 大於現用值時，才更新 memory_limit
        $memory = rtrim(ini_get('memory_limit'), 'M');

        if ((int) $matches[1] >= (int) $memory) {
            ini_set('memory_limit', $memorySize);
            return true;
        } else {
            return false;
        }
    }

}

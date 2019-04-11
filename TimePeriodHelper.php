<?php
namespace app\helpers;

use marsapp\helper\timeperiod\TimePeriodHelper as tpHelper;

/**
 * TimePeriod helper
 * 
 * 時間段處理函式庫
 * 
 * 1. 格式: $timePeriods = [[$startDatetime1, $endDatetime1], [$startDatetime2, $endDatetime2], ...];
 * - $Datetime = Y-m-d H:i:s ; Y-m-d H:i:00 ; Y-m-d H:00:00 ;
 * 2. 如果時間單位為 hour/minute/second, 結束時間點通常不被包含的, 例如, 工作時間8點到9點是1小時
 * 3. 如果時間單位為 day/month/year, 結束時間通常會被包含的, 例如, 1月到3月是三個月
 * 4. 處理時，假設數資料式正確。 如有必要，才使用驗證功能來驗證資料。
 * 
 * @author Mars Hung <tfaredxj@gmail.com>
 * @see https://github.com/marshung24/TimePeriodHelper
 */
class TimePeriodHelper
{
    
    /**
     * *********************************************
     * ************** Public Function **************
     * *********************************************
     */
    
    /**
     * 時間段排序 (正序)
     *
     * When sorting, sort the start time first, if the start time is the same, then sort the end time
     * 排序優先序：先排開始時間，再排結束時間
     *
     * @param array $timePeriods
     * @return array
     */
    public static function sort(Array $timePeriods)
    {
        return tpHelper::sort($timePeriods);
    }
    
    /**
     * 時間段聯集
     *
     * 排序+合併有接觸的時間段
     *
     * TimePeriodHelper::union($timePeriods1, $timePeriods2, $timePeriods3, ......);
     *
     * @param array $timePeriods
     * @return array
     */
    public static function union()
    {
        return call_user_func_array(['\marsapp\helper\timeperiod\TimePeriodHelper', 'union'], func_get_args());
    }
    
    /**
     * 時間段差集
     *
     * Compares $timePeriods1 against $timePeriods2 and returns the values in $timePeriods1 that are not present in $timePeriods2.
     *
     * TimePeriodHelper::diff($timePeriods1, $timePeriods2, $sortOut);
     *
     * @param array $timePeriods1
     * @param array $timePeriods2
     * @param bool $sortOut 如果能保証傳入的時間段已處理過union，可將本參數關閉，以提升效能
     * @return array
     */
    public static function diff(Array $timePeriods1, Array $timePeriods2, $sortOut = true)
    {
        return tpHelper::diff($timePeriods1, $timePeriods2, $sortOut);
    }
    
    /**
     * 時間段交集
     *
     * @param array $timePeriods1
     * @param array $timePeriods2
     * @param bool $sortOut 如果能保証傳入的時間段已處理過union，可將本參數關閉，以提升效能
     * @return array
     */
    public static function intersect(Array $timePeriods1, Array $timePeriods2, $sortOut = true)
    {
        return tpHelper::intersect($timePeriods1, $timePeriods2, $sortOut);
    }
    
    /**
     * 時間段是否有交集
     *
     * Determine if there is overlap between the two time periods
     *
     * Only when there is no intersection, no data is needed.
     * Logic is similar to intersect.
     *
     * @param array $timePeriods1
     * @param array $timePeriods2
     * @return bool
     */
    public static function isOverlap(Array $timePeriods1, Array $timePeriods2)
    {
        return tpHelper::isOverlap($timePeriods1, $timePeriods2);
    }
    
    /**
     * 填滿時間段
     *
     * Leaving only the first start time and the last end time
     *
     * @param array $timePeriods
     * @return array
     */
    public static function fill(Array $timePeriods)
    {
        return tpHelper::fill($timePeriods);
    }
    
    /**
     * 時間段間隙
     *
     * @param array $timePeriods
     * @param bool $sortOut 如果能保証傳入的時間段已處理過union，可將本參數關閉，以提升效能
     * @return array
     */
    public static function gap(Array $timePeriods, $sortOut = true)
    {
        return tpHelper::gap($timePeriods, $sortOut);
    }
    
    /**
     * 計算時間段的總時間
     *
     * 可使用函式setUnit()指定計算單位(預設:秒)
     *
     * @param array $timePeriods
     * @param bool $sortOut 如果能保証傳入的時間段已處理過union，可將本參數關閉，以提升效能
     * @return number
     */
    public static function time(Array $timePeriods, $sortOut = true)
    {
        return tpHelper::time($timePeriods, $sortOut);
    }
    
    /**
     * 裁剪時間段-依指定時間長度
     *
     * 可使用函式setUnit()指定計算單位(預設:秒)
     *
     * @param array $timePeriods
     * @param number $time
     *            時間長度
     * @param string $extension
     *            如是 時間長度 超過 時間段的總時間，是否延伸時間段(預設false)
     * @return array
     */
    public static function cut(Array $timePeriods, $time, $extension = false)
    {
        return tpHelper::cut($timePeriods, $time, $extension);
    }
    
    /**
     * 延伸時間段
     *
     * 可使用函式setUnit()指定計算單位(預設:秒)
     *
     * @param array $timePeriods
     * @param number $time
     *            時間長度
     * @param number $interval
     *            延伸時間段與原時間段間隔(預設:0)
     * @return array
     */
    public static function extend(Array $timePeriods, $time, $interval = 0)
    {
        return tpHelper::extend($timePeriods, $time, $interval);
    }
    
    /**
     * 縮短時間段
     *
     * 可使用函式setUnit()指定計算單位(預設:秒)
     *
     * @param array $timePeriods
     * @param number $time
     *            時間長度
     * @param bool $crossperiod
     *            是否跨時間段(預設true)
     * @return array
     */
    public static function shorten(Array $timePeriods, $time, $crossperiod = true)
    {
        return tpHelper::shorten($timePeriods, $time, $crossperiod);
    }
    
    /**
     * 格式轉換
     * 
     * 如原本時間段格式為 Y-m-d H:i:s 時，指定轉換單位為 minute 時，會變成 Y-m-d H:i:00
     * 如原本時間段格式為 Y-m-d H:i:s 時，指定轉換單位為 hour 時，會變成 Y-m-d H:00:00
     * 
     * @param array $timePeriods
     * @param string $unit 時間單位 (hour, minute, second)
     * @return array
     */
    public static function format(Array $timePeriods, $unit = 'default')
    {
        return tpHelper::format($timePeriods, $unit);
    }
    
    /**
     * 驗証時間段格式
     *
     * 驗証格式、大小、時間
     *
     * @param array $timePeriods
     * @throws \Exception
     * @return bool
     */
    public static function validate($timePeriods)
    {
        return tpHelper::validate($timePeriods);
    }
    
    /**
     * 過濾時間段格式
     *
     * 1. 驗証格式、大小、時間, 移除錯誤的資料(不報錯)
     * 2. 處理時間進位問題, 例： 2019-01-01 24:00:00 => 2019-01-02 00:00:00
     *
     * @param array $timePeriods
     * @param bool $exception 有錯誤時，是否丟出例外(預設false)
     * @throws \Exception
     * @return array
     */
    public static function filter($timePeriods, $exception = false)
    {
        return tpHelper::filter($timePeriods, $exception);
    }
    
    
    /**
     * **********************************************
     * ************** Options Function **************
     * **********************************************
     */
    
    
    /**
     * 設定時間處理單位
     *
     * hour, minute, second
     *
     * @param string $unit
     * @param string $target Specify function,or all functions
     * @throws \Exception
     * @return $this
     */
    public static function setUnit(string $unit, string $target = 'all')
    {
        return tpHelper::setUnit($unit, $target);
    }
    
    /**
     * 取得時間處理單位
     *
     * @param string $target Specify function's unit (time, format)
     * @throws \Exception
     * @return string
     */
    public static function getUnit(string $target)
    {
        return tpHelper::getUnit($target);
    }
    
    /**
     * 設定過濾參數-是否濾時間格式
     * 
     * 影響函式 filter(), validate()
     * 
     * @param bool $bool
     * @return $this
     */
    public static function setFilterDatetime($bool)
    {
        return tpHelper::setFilterDatetime($bool);
    }
    
    /**
     * 取得過濾參數-是否濾時間格式
     *
     * @return bool
     */
    public static function getFilterDatetime()
    {
        return tpHelper::getFilterDatetime();
    }
    
    /**
     * **********************************************
     * ************** Private Function **************
     * **********************************************
     */
    
}

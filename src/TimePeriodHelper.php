<?php
namespace nueip\helpers;

use marsapp\helper\timeperiod\TimePeriodHelper as tpHelper;

/**
 * TimePeriod helper
 * 
 * 時間段處理函式庫
 * 
 * 備註：
 * 1. 格式: $timePeriods = [[$startDatetime1, $endDatetime1], [$startDatetime2, $endDatetime2], ...];
 * - - $Datetime = Y-m-d H:i:s ; Y-m-d H:i:00 ; Y-m-d H:00:00 ;
 * 2. 如果時間單位為 hour/minute/second, 結束時間點通常不被包含的, 例如, 工作時間8點到9點是1小時
 * 3. 如果時間單位為 day/month/year, 結束時間通常會被包含的, 例如, 1月到3月是三個月
 * 4. 處理時，假設數資料式正確。 如有必要，才使用驗證功能來驗證資料。
 * 5. 通過保持$timePeriods格式正確來確保效能：
 * - a. 獲取原始$timePeriods時，請通過filter()，union()對其進行過濾&整理。
 * - b. 僅使用TimePeriodHelper提供的函數處理$timePeriods(不會破壞格式，排序)
 * - c. 當您完成上述兩個操作時，可以關閉自動排序( TimePeriodHelper::setSortOut(false) )以提高效能。
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
     * 1. When sorting, sort the start time first, if the start time is the same, then sort the end time
     * 2. 排序優先序：先排開始時間，再排結束時間
     * 
     * @author Mars Hung
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
     * 1. 排序+合併有接觸的時間段
     * 2. 範例：TimePeriodHelper::union($timePeriods1, $timePeriods2, $timePeriods3, ......);
     *
     * @author Mars Hung
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
     * 1. Compares $timePeriods1 against $timePeriods2 and returns the values in $timePeriods1 that are not present in $timePeriods2.
     * 2. 範例：TimePeriodHelper::diff($timePeriods1, $timePeriods2);
     * 3. $timePeriods是否已整理將影響結果的正確性。 請參閱註釋5.通過保持$timePeriods格式正確來確保效能。
     *
     * @author Mars Hung
     * 
     * @param array $timePeriods1
     * @param array $timePeriods2
     * @param bool $sortOut 是否重新整理傳入的時間段 (是(true)、否(false)、使用setSortOut全域方式處理(default))
     * @return array
     */
    public static function diff(Array $timePeriods1, Array $timePeriods2, $sortOut = 'default')
    {
        return tpHelper::diff($timePeriods1, $timePeriods2, $sortOut);
    }
    
    /**
     * 時間段交集
     * 
     * 1. 範例：TimePeriodHelper::intersect($timePeriods1, $timePeriods2);
     * 2. $timePeriods是否已整理將影響結果的正確性。 請參閱註釋5.通過保持$timePeriods格式正確來確保效能。
     * 
     * @author Mars Hung
     * 
     * @param array $timePeriods1
     * @param array $timePeriods2
     * @param bool $sortOut 是否重新整理傳入的時間段 (是(true)、否(false)、使用setSortOut全域方式處理(default))
     * @return array
     */
    public static function intersect(Array $timePeriods1, Array $timePeriods2, $sortOut = 'default')
    {
        return tpHelper::intersect($timePeriods1, $timePeriods2, $sortOut);
    }
    
    /**
     * 時間段是否有交集
     *
     * 1. Determine if there is overlap between the two time periods
     * 2. Only when there is no intersection, no data is needed.
     * 3. Logic is similar to intersect.
     *
     * @author Mars Hung
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
     * @author Mars Hung
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
     * 1. $timePeriods是否已整理將影響結果的正確性。 請參閱註釋5.通過保持$timePeriods格式正確來確保效能。
     * 
     * @author Mars Hung
     * 
     * @param array $timePeriods
     * @param bool $sortOut 是否重新整理傳入的時間段 (是(true)、否(false)、使用setSortOut全域方式處理(default))
     * @return array
     */
    public static function gap(Array $timePeriods, $sortOut = 'default')
    {
        return tpHelper::gap($timePeriods, $sortOut);
    }
    
    /**
     * 計算時間段的總時間
     *
     * 1. 可使用函式setUnit()指定計算單位(預設:秒)
     * 2. $timePeriods是否已整理將影響結果的正確性。 請參閱註釋5.通過保持$timePeriods格式正確來確保效能。
     * 3. 進似值取法：無條件捨去
     *
     * @author Mars Hung
     * 
     * @param array $timePeriods
     * @param int $precision
     *            小數精度位數
     * @param bool $sortOut 是否重新整理傳入的時間段 (是(true)、否(false)、使用setSortOut全域方式處理(default))
     * @return number
     */
    public static function time(Array $timePeriods, $precision = 0, $sortOut = 'default')
    {
        return tpHelper::time($timePeriods, $precision, $sortOut);
    }
    
    /**
     * 裁剪時間段-依指定時間長度
     *
     * 1. 可使用函式setUnit()指定計算單位(預設:秒)
     * 2. $timePeriods是否已整理將影響結果的正確性。 請參閱註釋5.通過保持$timePeriods格式正確來確保效能。
     *
     * @author Mars Hung
     * 
     * @param array $timePeriods
     * @param number $time
     *            時間長度
     * @param string $extension
     *            如是 時間長度 超過 時間段的總時間，是否延伸時間段(預設false)
     * @param bool $sortOut 是否重新整理傳入的時間段 (是(true)、否(false)、使用setSortOut全域方式處理(default))
     * @return array
     */
    public static function cut(Array $timePeriods, $time, $extension = false, $sortOut = 'default')
    {
        return tpHelper::cut($timePeriods, $time, $extension, $sortOut);
    }
    
    /**
     * 延伸時間段
     *
     * 1. 可使用函式setUnit()指定計算單位(預設:秒)
     * 2. $timePeriods是否已整理將影響結果的正確性。 請參閱註釋5.通過保持$timePeriods格式正確來確保效能。
     *
     * @author Mars Hung
     * 
     * @param array $timePeriods
     * @param number $time
     *            時間長度
     * @param number $interval
     *            延伸時間段與原時間段間隔(預設:0)
     * @param bool $sortOut 是否重新整理傳入的時間段 (是(true)、否(false)、使用setSortOut全域方式處理(default))
     * @return array
     */
    public static function extend(Array $timePeriods, $time, $interval = 0, $sortOut = 'default')
    {
        return tpHelper::extend($timePeriods, $time, $interval, $sortOut);
    }
    
    /**
     * 縮短時間段
     *
     * 1. 可使用函式setUnit()指定計算單位(預設:秒)
     * 2. $timePeriods是否已整理將影響結果的正確性。 請參閱註釋5.通過保持$timePeriods格式正確來確保效能。
     *
     * @author Mars Hung
     * 
     * @param array $timePeriods
     * @param number $time
     *            時間長度
     * @param bool $crossperiod
     *            是否跨時間段(預設true)
     * @param bool $sortOut 是否重新整理傳入的時間段 (是(true)、否(false)、使用setSortOut全域方式處理(default))
     * @return array
     */
    public static function shorten(Array $timePeriods, $time, $crossperiod = true, $sortOut = 'default')
    {
        return tpHelper::shorten($timePeriods, $time, $crossperiod, $sortOut);
    }
    
    /**
     * 格式轉換
     * 
     * 如原本時間段格式為 Y-m-d H:i:s 時，指定轉換單位為 minute 時，會變成 Y-m-d H:i:00
     * 如原本時間段格式為 Y-m-d H:i:s 時，指定轉換單位為 hour 時，會變成 Y-m-d H:00:00
     * 
     * @author Mars Hung
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
     * @author Mars Hung
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
     * @author Mars Hung
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
     * 1. 作用域：全域
     * 2. hour, minute, second
     *
     * @author Mars Hung
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
     * @author Mars Hung
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
     * 1. 作用域：全域
     * 2. 如果您不想過濾日期時間格式，請將其設置為false。
     * 3. 也許時間格式不是Y-m-d H：i：s（例如Y-m-d H：i），你需要關閉它。
     * 4. 影響函式 filter(), validate()
     * 
     * @author Mars Hung
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
     * @author Mars Hung
     * 
     * @return bool
     */
    public static function getFilterDatetime()
    {
        return tpHelper::getFilterDatetime();
    }
    
    /**
     * 設定自動整理時間段參數
     *
     * 1. 作用域：全域
     * 2. 在函數處理之前，將自動使用union()重整$timePeriods格式。
     *
     * @author Mars Hung
     * 
     * @param bool $bool 自動(true)、手動(false) default true
     * @return $this
     */
    public static function setSortOut($bool = true)
    {
        return tpHelper::setSortOut($bool);
    }
    
    /**
     * 取得自動整理參數
     *
     * @author Mars Hung
     * 
     * @return bool
     */
    public static function getSortOut()
    {
        return tpHelper::getSortOut();
    }
    
    /**
     * **********************************************
     * ************** Private Function **************
     * **********************************************
     */
    
}

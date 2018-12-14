<?php

namespace app\helpers;

/**
 * DateTime helper
 * 
 * @author  Nick Tsai
 */
class DateTimeHelper
{
    /**
     * Get dates between $start and $end
     *
     * @param string $start
     * @param string $end
     * @param string $format
     *
     * @example
     *  $start = '2010-01-30';
     *  $end = '2010-02-01';
     *  $format = "Y-m-d";
     *  $dates = \app\helpers\DateTimeHelper::getDates($start, $end, $format);
     *  // Result: $dates -> (array) ['2010-01-30', '2010-01-31', '2010-02-01'];
     *
     * @return array $dates
     */
    public static function getDates($start, $end, $format = "Y-m-d")
    {
        $start = new \DateTime($start);
        $end = new \DateTime($end);
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($start, $interval, $end->modify('+1 day'));

        $dates = [];

        foreach ($period as $dt) {
            $dates[] = $dt->format($format);
        }

        return $dates;
    }

    /**
     * Shift a giving months from your input datetime with month based
     * 
     * If the target month is smaller than the original month, the extra days will be discarded.
     * 
     * @author  Nick Tsai <myintaer@gmail.com>
     * @see     https://gist.github.com/yidas/d9d6e1d07a7fb2138d630c11a4c3b0ee
     * @example
     *  shiftMonths("2018-01-15", '+', 1);  // 2018-02-15
     *  shiftMonths("2018-01-31", '+', 1);  // 2018-02-28
     *  shiftMonths("2020-02-29", "-", 12); // 2019-02-28
     *  shiftMonths("2018-01-15", '+', 1, false);                   // 1518681600
     *  shiftMonths("2020-02-29 10:11:12", "-", 24, "Y-m-d H:i:s"); // 2018-02-28 10:11:12
     *
     * @param string Datetime string
     * @param string Operation: +/-
     * @param integer Number of months
     * @param string PHP date() format, empty returns Unix time
     * @return string|integer Date format|Unix time
     */
    public static function shiftMonths($date, $operation='+', $months=1, $outputFormat="Y-m-d")
    {
        $timestamp = strtotime($date);
    
        // Year
        $op = ($operation=='+') ? '+' : '-';
        $newBasetime = strtotime(date("Y-m-1 H:i:s", $timestamp) . " {$op}{$months} month");
        
        // Month
        $m = date("m", $newBasetime);
        
        // Day
        $lastDay = date("t", $newBasetime);
        $day = date("d", $timestamp);
        $day = ($lastDay <= $day) ? $lastDay : $day;
        $result = strtotime(date("Y-m-{$day} H:i:s", $newBasetime));

        return ($outputFormat) ? date($outputFormat, $result) : $result;
    }
    
    /**
     * 取得日期迭代器 - 以日為單位迭代日期
     *
     * Usage:
     * $daterange = \app\helpers\DateTimeHelper::dateIterator('2018-01-01', '2018-01-31');
     * foreach($daterange as $date){
     *     echo $date->format("Y-m-d") . "<br>";
     * }
     * 
     * @author  Mars Hung
     * 
     * @param date $start
     *            開始日期
     * @param date $end
     *            結束日期
     * @return DateTime
     */
    public static function dateIterator($start, $end)
    {
        $start = new \DateTime($start);
        $end = new \DateTime($end);
        $end = $end->modify('+1 day');
        
        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($start, $interval, $end);
        
        return $daterange;
    }
    
    
}

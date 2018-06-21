<?php

namespace nueip\helpers;

class DatetimeHelper
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
     *  $dates = \nueip\helpers\DatetimeHelper::getDates($start, $end, $format);
     *  // Result: $dates -> (array) ['2010-01-30', '2010-01-31', '2010-02-01'];
     *
     * @return array $dates
     */
    public static function getDates($start, $end, $format = "Y-m-d")
    {
        $start = new DateTime($start);
        $end = new DateTime($end);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start, $interval, $end->modify('+1 day'));

        $dates = [];

        foreach ($period as $dt) {
            $dates[] = $dt->format($format);
        }

        return $dates;
    }
}
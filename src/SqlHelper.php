<?php

namespace nueip\helpers;

/**
 * SQL Helper for CodeIgniter 3
 * 
 * @author  Mars Hung
 */
class SqlHelper
{
    /**
     * 協助處理array chunk SQL指令
     * 
     * 當 $snList 為空時，會將查詢結果設為空，
     * 如需要例外處理，請自行在函式外檢查判斷。
     * 
     * Usage:
     * \nueip\helpers\SqlHelper::whereInChunk($columnName, $snList, $queryBuilder = null, $size = 300);
     * 
     * @param string $columnName 欄位名稱
     * @param array $snList 資料陣列
     * @param DB_query_builder $queryBuilder 為null時，預設為 $this->db
     * @param number $size 每次處理大小
     */
    public static function whereInChunk($columnName, $snList, $queryBuilder = null, $size = 300)
    {
        // 參數處理
        $snList = (array) $snList;
        $queryBuilder = is_null($queryBuilder) ? get_instance()->db : $queryBuilder;

        // 處理非空陣列
        if (!empty($snList)) {
            $snChunk = array_chunk($snList, $size);

            $queryBuilder->group_start();

            foreach ($snChunk as $sn) {
                $queryBuilder->or_where_in($columnName, $sn);
            }

            $queryBuilder->group_end();
        } else {
            // 空陣列時，將查詢結果設為空
            $queryBuilder->where(1, 0);
        }

        return $queryBuilder;
    }

    /**
     * 協助處理array chunk SQL指令
     * 
     * 當 $snList 為空時，會將略過查詢，
     * 如需要例外處理，請自行在函式外檢查判斷。
     * 
     * Usage:
     * \nueip\helpers\SqlHelper::notWhereInChunk($columnName, $snList, $queryBuilder = null, $size = 300);
     * 
     * @param string $columnName 欄位名稱
     * @param array $snList 資料陣列
     * @param DB_query_builder $queryBuilder 為null時，預設為 $this->db
     * @param integer $size 每次處理大小
     */
    public static function notWhereInChunk($columnName, $snList, $queryBuilder = null, $size = 300)
    {
        // 參數處理
        $snList = (array) $snList;
        $queryBuilder = is_null($queryBuilder) ? get_instance()->db : $queryBuilder;

        // 處理非空陣列
        if (count($snList)) {
            $snChunk = array_chunk($snList, $size);

            $queryBuilder->not_group_start();

            foreach ($snChunk as $sn) {
                $queryBuilder->or_where_in($columnName, $sn);
            }

            $queryBuilder->group_end();
        }

        return $queryBuilder;
    }

    /**
     * 協助處理時間段交集SQL指令
     * 
     * @param string $sCol 起日欄位名
     * @param string $eCol 訖日欄位名
     * @param string $sDate 起日
     * @param string $eDate 訖日
     * @param DB_query_builder $queryBuilder 為null時，預設為 $this->db
     */
    public static function timeIntersect($sCol, $eCol, $sDate, $eDate, $queryBuilder = null)
    {
        // 參數處理
        $queryBuilder = is_null($queryBuilder) ? get_instance()->db : $queryBuilder;

        $queryBuilder->not_group_start()
            ->where($sCol . ' >', $eDate)
            ->or_group_start()
            ->where($eCol . ' <', $sDate)
            ->where($eCol . ' !=', '0000-00-00')
            ->group_end()
            ->group_end();

        return $queryBuilder;
    }

    /**
     * 協助處理時間小於等於特定欄位 且不為 0000-00-00
     * 
     * @param string $col 欄位名
     * @param string $date 日期
     * @param DB_query_builder $queryBuilder 為null時，預設為 $this->db
     */
    public static function timeAfter($columnName, $date, $queryBuilder = null)
    {
        // 參數處理
        $queryBuilder = is_null($queryBuilder) ? get_instance()->db : $queryBuilder;

        $queryBuilder
            ->group_start()
                ->where("{$columnName} >=", $date)
                ->or_where("{$columnName}", '0000-00-00')
            ->group_end();

        return $queryBuilder;
    }
}

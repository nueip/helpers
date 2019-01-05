<?php

namespace app\helpers;

/**
 * SQL Helper
 * 
 * @author  Mars Hung
 */
class SqlHelper
{
    /**
     * 協助處理array chunk SQL指令
     * 
     * Usage:
     * \app\helpers\SqlHelper::whereInChunk($columnName, $snList, $queryBuilder = null, $size = 300);
     * 
     * @param string $columnName 欄位名稱
     * @param array $snList 資料陣列
     * @param DB_query_builder $queryBuilder 為null時，預設為 $this->db
     * @param number $size 每次處理大小
     */
    public static function whereInChunk($columnName, $snList, $queryBuilder = null, $size = 300)
    {
        // 參數處理
        $snList = (array)$snList;
        $queryBuilder = is_null($queryBuilder) ? get_instance()->db : $queryBuilder;
        
        // 處理非空陣列
        if (!empty($snList)) {
            $snChunk = array_chunk($snList, $size);
            
            $queryBuilder->group_start();
            
            foreach ($snChunk as $sn) {
                $queryBuilder->or_where_in($columnName, $sn);
            }
            
            $queryBuilder->group_end();
        }
    }
}

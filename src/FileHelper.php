<?php

namespace nueip\helpers;

/**
 * File Helper
 * 
 * @author  Nick Tsai
 * @author  Jack Lin
 */
class FileHelper
{
    /**
     * Check upload file size
     *
     * @param integer $maxKB
     * @return boolean
     */
    public static function validateUploadSize($maxKB = 10000)
    {
        if (!$_FILES) {
            return false;
        }
        // initialize the size counter
        $totalSize = 0;
        // Calculate the size of all uploaded files
        foreach ($_FILES['files']['size'] as $key => $kbs) {
            $totalSize += $kbs;
        }
        // whether size exceeds the limitation or not
        if (($totalSize / 1024) > $maxKB) {
            return false;
        }
        
        return true;
    }

    /**
     * Filter filename
     * 
     * @param string $filename
     * @return string
     */
    public static function filterFilename($filename)
    {
        // 保留字元
        static $reservedChar = [
            '<', '>', '|', ':', '&',
            '*', '(', ')', ';' ,'"',
            '/', '?', '\\', '\'',
        ];

        // 移除保留字元
        $filename = str_replace($reservedChar, '', $filename);

        // 空白符號 轉換成 底線
        $filename = str_replace(' ', '_', $filename);

        // 檔名長度小於一時報錯
        if (is_null($filename[0])) {
            $errorMsg = get_language('filedes') . ':' . get_language('wrongformat_02');
            throw new Exception($errorMsg, 400);
        }

        return $filename;
    }
}

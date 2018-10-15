<?php

namespace app\helpers;

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
}

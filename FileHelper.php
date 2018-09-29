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

        // ...
    }
}

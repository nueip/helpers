<?php

namespace nueip\helpers;

use \Exception;

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
            '*', '(', ')', ';', '"',
            '/', '?', '\\', '\'',
        ];

        // 移除保留字元
        $filename = str_replace($reservedChar, '', $filename);

        // 空白符號 轉換成 底線
        $filename = str_replace(' ', '_', $filename);

        // 檔名長度小於一時報錯
        if (!isset($filename[0])) {
            $errorMsg = get_language('filedes') . ':' . get_language('wrongformat_02');
            throw new Exception($errorMsg, 400);
        }

        return $filename;
    }

    /**
     * File content encode conversion
     *
     * @param  string          $filePath
     * @param  string|string[] $fileEncode
     * @param  string          $convEncode
     * @return void
     */
    public static function encodeConv($filePath, $fileEncode = 'BIG5', $convEncode = 'UTF-8')
    {
        $content = file_get_contents($filePath);

        // Set detect encode list
        $encodeList = is_array($fileEncode) ? $fileEncode : explode(',', $fileEncode);
        array_push($encodeList, $convEncode);

        // Get content encode type
        $oriEncode = mb_detect_encoding($content, $encodeList, true);

        // Convert when encoding is different
        if ($oriEncode !== $convEncode) {
            $content = mb_convert_encoding($content, $convEncode, $oriEncode);
        }

        file_put_contents($filePath, $content);
    }

    /**
     * 文本檔案輸出 - Text
     * 
     * @author Gunter.Chou
     * @param string $filename 輸出檔案名
     * @param array $data 檔案內容
     * @param string $fileEncode 檔案編碼，預設UTF-8
     * @param string $separator 分隔符號
     */
    public static function exportPlain($filename, $data, $fileEncode = 'UTF-8', $separator = ',')
    {
        header('Content-Encoding: utf-8');
        header('Content-Type: charset=utf-8; text/plain; application/octet-stream');
        header('Content-Disposition: attachment; filename="' . rawurlencode($filename) . '"; filename*=UTF-8\'\'' . rawurlencode($filename));
        
        $content = '';
        foreach ($data as $row) {
            $content = $content . implode($separator, $row) . "\r\n";
        }

        self::_printContent($content, $fileEncode);
        exit;
    }

    /**
     * CSV 檔案輸出
     * 
     * @author Gunter.Chou
     * @param string $filename 輸出檔案名
     * @param array $data 檔案內容
     * @param string $fileEncode 檔案編碼，預設UTF-8
     * @param string $separator 分隔符號
     */
    public static function exportCsv($filename, $data, $fileEncode = 'UTF-8', $separator = ',')
    {
        header('Content-Encoding: utf-8');
        header('Content-Type: charset=utf-8; text/csv; application/octet-stream');
        header('Content-Disposition: attachment; filename="' . rawurlencode($filename) . '"; filename*=UTF-8\'\'' . rawurlencode($filename));

        $content = '';
        foreach ($data as $row) {
            foreach ($row as &$val) {
                $val = '"' . str_replace('"', '""', $val) . '"';
            }
            $content = $content . implode($separator, $row) . "\r\n";
        }

        self::_printContent($content, $fileEncode);
        exit;
    }

    /**
     * ZIP 檔案輸出
     * 
     * - https://datatracker.ietf.org/doc/html/rfc6266
     * 
     * @param string $filename 輸出檔案名
     * @param string $path
     * @param string $autoUnlink
     */
    public static function exportZip($filename, $path, $autoUnlink = true)
    {
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Encoding: utf-8');
        header('Content-Type: charset=utf-8; application/zip; application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: attachment; ' 
                    . sprintf('filename="%s"; ', rawurlencode($filename . '.zip')) 
                    . sprintf("filename*=utf-8''%s", rawurlencode($filename . '.zip')));
        header('Content-Length: ' . filesize($path));

        readfile($path);
        $autoUnlink && @unlink($path);
        exit;
    }

    /**
     * **********************************************
     * ************** Private Function **************
     * **********************************************
     */

    /**
     * 文本編碼輸出
     * 
     * @author Gunter.Chou
     * @param string $content 檔案內容
     * @param string $fileEncode 檔案編碼，預設UTF-8
     * @return void
     */
    protected static function _printContent($content, $fileEncode = 'UTF-8')
    {
        switch ($fileEncode) {
            case 'UTF-8 BOM':
                echo "\xEF\xBB\xBF";
            default:
            case 'UTF-8':
                $encode = 'UTF-8';
                break;
            case 'BIG-5':
                $encode = 'BIG-5';
                break;
        }

        echo mb_convert_encoding($content, $encode, 'UTF-8');
    }

}

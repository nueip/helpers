<?php

namespace nueip\helpers;

/**
 * Text Helper
 *
 * @author Gunter Chou
 */
class TextHelper
{

    /**
     * Alias function
     * 將輸入字串轉換成UTF-8全形文字
     *
     * @param string $strs
     *
     * @return string $strs
     */
    public static function convFullBIG5($strs)
    {
        return mb_convert_encoding(self::half2full($strs, 'full'), 'BIG5', 'UTF-8');
    }

    /**
     * 將輸入字串轉換成 全形 或 半形 文字
     *
     * @param string $strs
     * @param string $types ( full | half )
     *
     * @return string $strtmp
     */
    public static function half2full($strs, $types = 'full')
    {
        $nt = array(
            '(', ')', '[', ']', '{', '}', '.', ',', ';', ':',
            '-', '?', '!', '@', '#', '$', '%', '&', '|', '\\',
            '/', '+', '=', '*', '~', '`', "'", '"', '<', '>',
            '^', '_',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
            'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
            'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z',
            ' ',
        );
        $wt = array(
            '（', '）', '〔', '〕', '｛', '｝', '﹒', '，', '；', '：',
            '－', '？', '！', '＠', '＃', '＄', '％', '＆', '｜', '＼',
            '／', '＋', '＝', '＊', '～', '、', '、', '＂', '＜', '＞',
            '︿', '＿',
            '０', '１', '２', '３', '４', '５', '６', '７', '８', '９',
            'ａ', 'ｂ', 'ｃ', 'ｄ', 'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ', 'ｊ',
            'ｋ', 'ｌ', 'ｍ', 'ｎ', 'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ', 'ｔ',
            'ｕ', 'ｖ', 'ｗ', 'ｘ', 'ｙ', 'ｚ',
            'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ', 'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ',
            'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ', 'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ',
            'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ', 'Ｚ',
            '　',
        );

        $strtmp = ($types === 'full')
            ? str_replace($nt, $wt, $strs)
            : str_replace($wt, $nt, $strs);

        return $strtmp;
    }

    /**
     * 轉換 - br轉nl
     * 
     * @param string $str
     * @return string
     */
    public static function br2nl($str)
    {
        return preg_replace('/<[bB][rR]\\s*\/?>/i', "\n", $str);
    }

    /**
     * 寬度處理 - 單行
     * 
     * - 未處理換行符
     * 
     * 換行符請在程式外部自行處理
     * 
     * @param string $str
     * @param number $widthLimit
     * @param string $trimmarker
     * @return string
     */
    public static function strWidthLimit($str, $widthLimit = 50, $trimmarker = '...')
    {
        return mb_strimwidth($str, 0, $widthLimit, $trimmarker);
    }

    /**
     * 寬度處理 - 字串依寬度補斷行 - textarea
     *
     * 特點
     * 1. 支援multibyte文字
     * 2. 超過寬度的行，會補斷行(變多行)，但不超過的行不另處理
     *
     * @auth MarsHung
     * 
     * @param string $str 字串
     * @param number $widthLimit 寬度限制
     * @param number $lineLimit 行數限制，0為不限制
     */
    public static function strLineWidthRepair($str, $widthLimit = 50, $lineLimit = 0, $trimmarker = '...')
    {
        $encoding = mb_detect_encoding($str, 'auto', true);

        // 以斷行為分隔點，轉陣列
        $strList = mb_split("[\r]?\n", $str);

        $newStr = [];
        foreach ($strList as $k => $s) {
            // 計算寬度
            $sl = mb_strwidth($s, $encoding);

            // 寬度限制處理
            if ($widthLimit < $sl) {
                // 超過寬度限制處理 - 合併剩下的文字，並重新斷行
                // 寬度處理 - 字串依寬度重新斷行成陣列
                $nStrArr = self::_strWidthRefactor2Array($s, $widthLimit, $encoding);
                $newStr = array_merge($newStr, $nStrArr);
            } else {
                // 未超過寬度限度處理
                $newStr[] = $s;
            }

            unset($strList[$k]);
        }

        // 行數限制處理
        if ($lineLimit && $lineLimit < sizeof($newStr)) {
            $newStr = array_slice($newStr, 0, $lineLimit);
            $newStr[$lineLimit - 1] = self::strWidthLimit($newStr[$lineLimit - 1], $widthLimit - strlen($trimmarker), $trimmarker);
        }

        $opt = implode("\n", $newStr);

        return $opt;
    }

    /**
     * 寬度處理 - 字串依寬度重新斷行 - textarea
     *
     * 特點
     * 1. 支援multibyte文字
     * 2. 字串依指定寬度重新斷行
     *
     * @auth MarsHung
     * 
     * @param string $str 字串
     * @param number $widthLimit 寬度限制
     * @param number $lineLimit 行數限制，0為不限制
     * @param string $trimmarker 有多餘字元時，刪除號樣式
     * @return string
     */
    public static function strLineWidthRefactor($str, $widthLimit = 50, $lineLimit = 0, $trimmarker = '...')
    {
        // 偵測編碼
        $encoding = mb_detect_encoding($str, 'auto', true);

        // 重整字串 - 移除斷行 - ASCII,UTF-8中使用str_replace是安全的
        $srMap = ['ASCII' => '', 'UTF-8' => '', 'ascii' => '', 'utf-8' => '', 'UTF8' => '', 'utf8' => ''];
        if (isset($srMap[$encoding])) {
            $ns = str_replace(["\r\n", "\n", "\r"], '', $str);
        } else {
            $strList = mb_split("\n", $str);
            $strList = array_map('trim', $strList);
            $ns = implode('', $strList);
        }

        // 寬度處理 - 字串依寬度重新斷行成陣列
        $newStr = self::_strWidthRefactor2Array($ns, $widthLimit, $encoding);

        // 行數限制處理
        if ($lineLimit && $lineLimit < sizeof($newStr)) {
            $newStr = array_slice($newStr, 0, $lineLimit);
            $newStr[$lineLimit - 1] = self::strWidthLimit($newStr[$lineLimit - 1], $widthLimit - strlen($trimmarker), $trimmarker);
        }

        $opt = implode("\n", $newStr);

        return $opt;
    }



    /**
     * **********************************************
     * ************** Private Function **************
     * **********************************************
     */

    /**
     * 寬度處理 - 字串依寬度重新斷行成陣列
     * 
     * @param string $str 字串
     * @param number $widthLimit 寬度限制
     * @param string $encoding 編碼
     * @return string[]
     */
    protected static function _strWidthRefactor2Array($str, $widthLimit, $encoding = '')
    {
        // 偵測編碼
        $encoding = $encoding ? $encoding : mb_detect_encoding($str, 'auto', true);

        // 計算總寬
        $tw = mb_strwidth($str, $encoding);

        $newStr = [];
        for ($i = 0; $i < $tw; $i += $widthLimit) {
            $s1 = mb_strimwidth($str, 0, $widthLimit, '', $encoding);
            $newStr[] = $s1;

            // 重設待處理字串
            $str = mb_substr($str, mb_strlen($s1));
        }

        // 處理剩餘字元
        if (!empty($str)) {
            $newStr[] = $str;
        }

        return $newStr;
    }

    /**
     * 將輸入數字轉換成 全形 或 半形 數字
     *
     * @param string $strs
     * @param string $types ( full | half )
     *
     * @return string $strtmp
     */
    public static function full2half($strs, $types = 'half')
    {
        $nt = array(
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        );
        $wt = array(
            '０', '１', '２', '３', '４', '５', '６', '７', '８', '９',
        );

        $strtmp = ($types === 'half')
            ? str_replace($wt, $nt, $strs)
            : str_replace($nt, $wt, $strs);

        return $strtmp;
    }
}

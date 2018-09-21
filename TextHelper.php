<?php
namespace app\helpers;

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
}

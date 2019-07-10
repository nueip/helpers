<?php

namespace nueip\helpers;

/**
 * Encode Helper
 * 
 * @author Mars Hung <tfaredxj@gmail.com>
 * @example <br>
 *          1. Snapshot encode(zip) & decode(unzip) <br>
 *          - encode: $snapshot = \nueip\helpers\EncodeHelper::snapshotEncode($data); <br>
 *          - decode: $data = \nueip\helpers\EncodeHelper::snapshotDecode($snapshot); <br>
 *         
 */
class EncodeHelper
{

    /**
     * Snapshot encode(zip)
     *
     * 1. 為支援快照資料(snapshot)儲存，將特定資料建構成資料陣列後，給予壓縮，以節省空間
     * 2. 如果資料量小，無壓縮效率
     * 3. 判斷是否有壓縮
     * - 有壓縮：字串第1個字元是2 (gzdeflate/gzinflate)
     * - 有壓縮：字串第1個字元是1 (gzcompress/gzuncompress)
     * - 無壓縮：字串第1個字元是0
     * 4. 本函式只處理陣列，故選用json_encode,json_decode
     * 
     * @author Mars Hung <tfaredxj@gmail.com>
     * @param array|mixed $data
     *            資料陣列
     * @param bool $forceCompress
     *            強制壓縮
     * @return string 快照資料(snapshot)
     */
    public static function snapshotEncode($data, $forceCompress = false)
    {
        // 輸出內容初始化
        $opt = '';

        // 壓縮處理
        if ($forceCompress) {
            // 強制壓縮
            $opt = '2' . base64_encode(gzdeflate(json_encode($data)));
        } else {
            // 不強制壓縮
            $json = json_encode($data);
            $zip = base64_encode(gzdeflate($json));

            if (strlen($json) <= strlen($zip)) {
                // 無壓縮必要
                $opt = '0' . $json;
            } else {
                // 有壓縮必要
                $opt = '2' . $zip;
            }
        }

        return $opt;
    }

    /**
     * Snapshot decode(unzip)
     *
     * 1. 為支援快照資料(snapshot)儲存，將特定資料建構成資料陣列後，給予壓縮，以節省空間
     * 2. 如果資料量小，無壓縮效率
     * 3. 判斷是否有壓縮
     * - 有壓縮：字串第1個字元是2 (gzdeflate/gzinflate)
     * - 有壓縮：字串第1個字元是1 (gzcompress/gzuncompress)
     * - 無壓縮：字串第1個字元是0
     * 4. 本函式只處理陣列，故選用json_encode,json_decode
     * 
     * @author Mars Hung <tfaredxj@gmail.com>
     * @param string $data
     *            快照資料(snapshot)
     * @param bool $assoc 回傳格式: 陣列(true) ; 物件(false)
     * @return mixed
     */
    public static function snapshotDecode(String $data, $assoc = true)
    {
        // 輸出內容初始化
        $opt = '';

        if ($data !== '') {
            // 拆解內容 - 是否壓縮、真實資料
            $isCompress = substr($data, 0, 1);
            $rData = substr($data, 1);

            switch ($isCompress) {
                case '0':
                    // 無壓縮
                    $opt = json_decode($rData, $assoc);
                    break;
                case '1':
                    // 有壓縮 gzcompress (舊)
                    $opt = json_decode(gzuncompress(base64_decode($rData)), $assoc);
                    break;
                case '2':
                    // 有壓縮 gzdeflate
                    $opt = json_decode(gzinflate(base64_decode($rData)), $assoc);
                    break;
                default:
                    // 異常，不處理
                    $opt = $data;
            }
        }

        return $opt;
    }
}

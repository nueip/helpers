<?php
namespace app\helpers;

/**
 * Encode Helper
 * 
 * @author Mars Hung <tfaredxj@gmail.com>
 * @example <br>
 *          1. Snapshot encode(zip) & decode(unzip) <br>
 *          - encode: $snapshot = \app\helpers\EncodeHelper::snapshotEncode($data); <br>
 *          - decode: $data = \app\helpers\EncodeHelper::snapshotDecode($snapshot); <br>
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
     * - 有壓縮：字串第1個字元是1
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
            $opt = '1' . base64_encode(gzcompress(json_encode($data)));
        } else {
            // 不強制壓縮
            $json = json_encode($data);
            $zip = base64_encode(gzcompress($json));
            if (strlen($json) <= strlen($zip)) {
                // 無壓縮必要
                $opt = '0' . $json;
            } else {
                // 有壓縮必要
                $opt = '1' . $zip;
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
     * - 有壓縮：字串第1個字元是1
     * - 無壓縮：字串第1個字元是0
     * 4. 本函式只處理陣列，故選用json_encode,json_decode
     * 
     * @author Mars Hung <tfaredxj@gmail.com>
     * @param string $data
     *            快照資料(snapshot)
     * @return mixed
     */
    public static function snapshotDecode(String $data)
    {
        // 輸出內容初始化
        $opt = '';
        
        if ($data !== '') {
            // 拆解內容 - 是否壓縮、真實資料
            $isCompress = substr($data, 0, 1);
            $data = substr($data, 1);
            
            if ($isCompress === '1') {
                // 有壓縮
                $opt = json_decode(gzuncompress(base64_decode($data)), 1);
            } else {
                // 無壓縮
                $opt = json_decode($data, 1);
            }
        }
        
        return $opt;
    }
}

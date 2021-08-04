<?php

namespace nueip\helpers;

use Exception;

/**
 * System Helper
 *
 * Provide methods for handling system parameter control.
 *
 * # Cookie Helper 部份
 * - 包裝PHP原生函式/變數 setcookie()/$_COOKIE ，以豐富作用方式之餘，簡化使用方法.
 * - 額外功能：
 *   - 支援Cookie前綴包裝
 *   - 支援預設值: expires, path, domain, secure, httponly
 *   - 專用函式: Root domain存取
 * - 常數
 *   - Cookie前綴，預設優先序: COOKIE_DEFAULT_PREFIX => 無前綴 '' (空字串)
 *   - 過期時間，預設優先序: COOKIE_DEFAULT_EXPIRES => 無期限 0
 *   - 存放路徑，預設優先序: COOKIE_DEFAULT_PATH => 當前路徑 '' (空字串)
 *   - 所屬網域，優先序: COOKIE_DEFAULT_DOMAIN => 當前網域 '' (空字串)
 *   - 根網域，優先序: COOKIE_ROOT_DOMAIN => COOKIE_DEFAULT_DOMAIN => 當前網域 '' (空字串)
 *
 * @author  Mars Hung
 */
class SystemHelper
{

    /**
     * Cookie預設參數
     */
    protected static $cookieDefaultOptions = [
        // Cookie前綴，預設無前綴
        'prefix' => '',
        // 過期時間，預設無期限
        'expires' => 0,
        // 存放路徑
        'path' => '',
        // 所屬網域
        'domain' => '',
        // 是否只在https生效
        'secure' => true,
        // 是否只能通過HTTP協議訪問
        'httponly' => false,
        // 根網域 - 本函式處理根網域用，非 setcookie()參數
        'rootDomain' => '',
    ];

    /**
     * 旗標-記錄是否執行過靜態建構子
     *
     * @var boolean
     */
    protected static $staticInitialize = false;

    /**
     * 靜態建構子
     * 
     * - 初始化靜態屬性用
     * - 因PHP不支援靜態建構子，因此使用autoload模擬(載入時自動呼叫)，只執行一次
     * - 本建構子中應只支援靜態屬性/靜態函式處理
     */
    public static function __constructStatic()
    {
        if (!self::$staticInitialize) {
            // === Cookie預設參數設定 ===
            defined('COOKIE_DEFAULT_PREFIX') && self::$cookieDefaultOptions['prefix'] = COOKIE_DEFAULT_PREFIX;
            defined('COOKIE_DEFAULT_EXPIRES') && self::$cookieDefaultOptions['expires'] = COOKIE_DEFAULT_EXPIRES;
            defined('COOKIE_DEFAULT_PATH') && self::$cookieDefaultOptions['path'] = COOKIE_DEFAULT_PATH;
            defined('COOKIE_DEFAULT_DOMAIN') && self::$cookieDefaultOptions['domain'] = COOKIE_DEFAULT_DOMAIN;
            self::$cookieDefaultOptions['rootDomain'] = defined('COOKIE_ROOT_DOMAIN') ? COOKIE_ROOT_DOMAIN : self::$cookieDefaultOptions['domain'];

            // 標記執行過靜態建構子
            self::$staticInitialize = true;
        }
    }

    /**
     * 最小記憶體設定
     *
     * - 以M為單位，不做單位轉換
     * - 如果現有可用記憶體大於本次設定值，不會縮減
     * - 建議：一般程式不超過512M，匯出程式不超過1024M
     * - 最大 2048M
     * 
     * @uses memoryMoreThan($memorySize);
     * @example memoryMoreThan('256M');
     *
     * @param  string       $memorySize 記憶體大小，單位 M，預設 256M
     * @throws Exception
     * @return bool
     */
    public static function memoryMoreThan($memorySize = '256M'): bool
    {
        // 格式檢查：3~4位字數+字元M
        if (!preg_match('/^([0-9]{1,4})([mM])$/', trim($memorySize), $matches)) {
            throw new Exception('Memory size error!', 400);
        }

        // 最大值檢查
        if ($matches[1] > 2048) {
            throw new Exception('Memory size cannot greater than 2048M!', 400);
        }

        // 大於現用值時，才更新 memory_limit
        $memory = rtrim(ini_get('memory_limit'), 'M');

        if ((int) $matches[1] >= (int) $memory) {
            ini_set('memory_limit', $memorySize);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Cookie預設參數處理-單筆
     *
     * - 當目標參數不存在，回傳null
     * - 當有目標參數，但沒待處理值，取得現在目標參數的值
     * - 當有目標參數，且有待處理值，更新資料後回傳更新後的值
     * 
     * @author MarsHung
     * @see https://github.com/marshung24/system-helper
     * 
     * @param string $param 目標參數
     * @param mixed $value 待處理值
     * @return mixed
     */
    public static function cookieOption(string $param, $value = null)
    {
        // 存在才處理
        if (isset(self::$cookieDefaultOptions[$param])) {
            // 有待處理值時才更新
            if (!is_null($value)) {
                self::$cookieDefaultOptions[$param] = $value;
            }

            // 回傳目標參數儲存的值
            return self::$cookieDefaultOptions[$param];
        }

        // 目標參數不存在，回傳NULL
        return null;
    }

    /**
     * 設定Cookie預設參數-多筆
     *
     * - 差異處理更新後回傳完整Cookie預設參數
     * - 當傳入空陣列時，不會處理更新，只會回傳完整Cookie預設參數
     * 
     * @author MarsHung
     * @see https://github.com/marshung24/system-helper
     * 
     * @param array $options 參數
     * @return array
     */
    public static function cookieOptions(array $options = [])
    {
        // 有傳入資料時才處理更新
        if (count($options)) {
            self::$cookieDefaultOptions = array_merge(self::$cookieDefaultOptions, array_intersect_key($options, self::$cookieDefaultOptions));
        }

        // 回傳完整Cookie預設參數
        return self::$cookieDefaultOptions;
    }

    /**
     * 設定Cookie
     *
     * - 為簡化系統開發與統一管理Cookie選項預設值，因此開發本函式
     * - 可設定常數 COOKIE_DEFAULT_PREFIX , COOKIE_DEFAULT_EXPIRES , COOKIE_DEFAULT_PATH
     * - 格式
     * $options = [
     *      // Cookie前綴，預設優先序: COOKIE_DEFAULT_PREFIX => '' (無前綴)
     *      'prefix' => '',
     *      // 過期時間，預設優先序: COOKIE_DEFAULT_EXPIRES => 無期限
     *      'expires' => 0,
     *      // 存放路徑，預設優先序: COOKIE_DEFAULT_PATH => 當前路徑
     *      'path' => "",
     *      // 所屬網域
     *      'domain' => "",
     *      // 是否只在https生效
     *      'secure' => true,
     *      // 是否只能通過HTTP協議訪問
     *      'httponly' => false,
     * ];
     *
     * $_COOKIE特性
     * - 使用setcookie設定Cookie內容時，無法馬上從$_COOKIE取得值
     * - 使用setcookie設定Cookie過期時，$_COOKIE內的資料不會馬上消失
     * - 使用unset變更/刪除$_COOKIE內某個Cookie時，並不會真的變動
     * 
     * @author MarsHung
     * @see https://github.com/marshung24/system-helper
     * 
     * @param  string  $name    Cookie Name
     * @param  string  $value   Cookie Value
     * @param  array   $options Cookie setting parameters
     * @return void
     */
    public static function cookieSet(string $name, string $value = "", array $options = [])
    {
        // 取得設定參數
        $op = $options + self::$cookieDefaultOptions;

        $name = $op['prefix'] . $name;

        // 更新資料
        $_COOKIE[$name] = $value;
        return setcookie($name, $value, $op['expires'], $op['path'], $op['domain'], $op['secure'], $op['httponly']);
    }

    /**
     * 設定Cookie - 存放於 Root Domain 中
     *
     * - 需設定常數 COOKIE_ROOT_DOMAIN，如沒設定則為當前網域
     * - 本函式為指定 domain 參數的 cookieSet() 函式
     * - 目前網域與根網域有同名稱變數時，優先讀取先設定的資料(chrome,20210506,PHP7)
     * 
     * @author MarsHung
     * @see https://github.com/marshung24/system-helper
     * 
     * @param  string  $name    Cookie Name
     * @param  string  $value   Cookie Value
     * @param  array   $options Cookie setting parameters
     * @return void
     */
    public static function cookieSetRoot(string $name, string $value = "", array $options = [])
    {
        // 用根網域覆蓋所屬網域
        $options['domain'] = self::$cookieDefaultOptions['rootDomain'];

        return self::cookieSet($name, $value, $options);
    }

    /**
     * 取得Cookie
     * 
     * - 目前網域與根網域有同名稱變數時，優先讀取先設定的資料(chrome,20210506,PHP7)
     * 
     * @author MarsHung
     * @see https://github.com/marshung24/system-helper
     * 
     * @param  string   $name Cookie Name
     * @param  array    $options Cookie setting parameters
     * @return string
     */
    public static function cookieGet(string $name, array $options = [])
    {
        // 取得設定參數
        $op = $options + self::$cookieDefaultOptions;

        $name = $op['prefix'] . $name;

        return $_COOKIE[$name] ?? '';
    }

    /**
     * 移除Cookie
     *
     * - 因使用setcookie()移除cookie後要下一輪才會生效，所以需同步移除$_COOKIE中的值
     * 
     * @author MarsHung
     * @see https://github.com/marshung24/system-helper
     * 
     * @param  string $name Cookie Name
     * @param  array   $options Cookie setting parameters
     * @return bool
     */
    public static function cookieExpired(string $name, array $options = [])
    {
        // 移除Cookie
        $options['expires'] = -1;
        $opt = self::cookieSet($name, '', $options);

        // 取得設定參數
        $op = $options + self::$cookieDefaultOptions;
        $name = $op['prefix'] . $name;

        // 同步移除 $_COOKIE 中的資料
        unset($_COOKIE[$name]);
        return $opt;
    }

    /**
     * 移除Cookie - 存放於 Root Domain 中
     *
     * - 因使用setcookie()移除cookie後要下一輪才會生效，所以需同步移除$_COOKIE中的值
     * 
     * @author MarsHung
     * @see https://github.com/marshung24/system-helper
     * 
     * @param  string $name Cookie Name
     * @param  array   $options Cookie setting parameters
     * @return bool
     */
    public static function cookieExpiredRoot(string $name, array $options = [])
    {
        // 移除Cookie
        $options['expires'] = -1;
        $opt =  self::cookieSetRoot($name, '', $options);

        // 取得設定參數
        $op = $options + self::$cookieDefaultOptions;
        $name = $op['prefix'] . $name;

        // 同步移除 $_COOKIE 中的資料
        unset($_COOKIE[$name]);
        return $opt;
    }
}

// 載入時自動執行靜態建構子
SystemHelper::__constructStatic();

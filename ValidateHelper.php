<?php
namespace app\helpers;

/**
 * Validate Helper
 *
 * @author Mars.Hung <tfaredxj@gmail.com>
 * @version 1.0.0
 */
class ValidateHelper
{

    /**
     * 初始化旗標
     * 
     * @var bool
     */
    protected static $_inited = false;

    /**
     * Validate Object
     *
     * @var unknown
     */
    protected static $_gump = null;

    /**
     *
     * @var object yidas\http\Response;
     */
    protected static $_response = null;

    /**
     * Construct
     */
    public function __construct()
    {
        // 初始化
        self::init();
    }

    /**
     * 執行驗證 - 執行GUMP驗証
     *
     * @param array $rawData
     *            原始資料
     * @param array $rules
     *            驗証規則
     * @param String $msgType
     *            訊息輸出模式 json, Exception, rest
     */
    public static function validate(Array $rawData, Array $rules, $msgType = 'message', $msgCode = '404')
    {
        // 初始化
        self::init();
        $opt = true;
        
        // 執行驗証
        $validated = self::$_gump->validate($rawData, $rules);
        
        // 驗証錯誤結果處理
        if (! ($validated === TRUE)) {
            foreach ($validated as $key => $value) {
                $validated[$key]["message"] = validateMessage($validated[$key]['rule'], $validated[$key]['param']);
                $validated[$key]['name'] = validateName($validated[$key]['field']);
            }
            
            $opt = self::sendMessage($validated, $msgCode, $msgType);
        }
        
        return $opt;
    }

    /**
     * **********************************************
     * ************** Private Function **************
     * **********************************************
     */
    
    /**
     * 初始化
     *
     * @param bool $forceInit
     *            是否強制初始化
     */
    public static function init($forceInit = false)
    {
        if (! self::$_inited || $forceInit) {
            // 初炲化旗標 - 防止重複初始化
            self::$_inited = true;
            
            // 初始化
            self::$_gump = new \GUMP();
            self::$_response = new \yidas\http\Response();
        }
    }

    /**
     * 訊息輸出 - 依指定方式輸出訊息
     *
     * @param string $type
     *            警告模式 set_message模式(message) / 回傳json訊息(json) / 回傳訊息json + Http Status Code(jsonHttp) / 例外(exception) / 回傳(return)
     * @param string $msg
     *            訊息
     * @param string $msgCode
     *            狀態碼
     * @param string $msgType
     *            訊息輸出模式 json, Exception, jsonHttp(json + Http Status Code)
     */
    public static function sendMessage($msg, $msgCode = '404', $msgType = 'message')
    {
        switch ($msgType) {
            case 'message':
            default:
                set_message($msgCode, $msg);
                break;
            case 'json':
                echo json_encode($msg);
                exit();
                break;
            case 'jsonHttp':
                // 初始化
                self::init();
                self::$_response->json($msg, $msgCode);
                break;
            case 'exception':
                $msg = is_array($msg) ? json_encode($msg) : $msg;
                throw new \Exception($msg, (int) $msgCode);
                break;
            case 'return':
                return $msg;
                break;
        }
    }
}

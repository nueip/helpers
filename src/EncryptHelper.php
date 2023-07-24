<?php

namespace nueip\helpers;

// 常數
defined('HASH_TOKEN_DATA')      OR define('HASH_TOKEN_DATA', NULL);
defined('ENVIRONMENT')          OR define('ENVIRONMENT', 'development');
defined('HASH_TOKEN_PATH')      OR define('HASH_TOKEN_PATH', 'hash_token.json');
defined('HASH_TOKEN_PATH_DEV')  OR define('HASH_TOKEN_PATH_DEV', 'hash_token_dev.json');

/**
 * Encrypt Helper
 * 
 * - 設定加解密金鑰方式
 *   - 金鑰設定值(優先) (Array||NULL) HASH_TOKEN_DATA
 *   - 金鑰檔讀取路徑 (String) HASH_TOKEN_PATH / HASH_TOKEN_PATH
 *
 * @author Mars.Hung
 * @author Gunter.Chou
 */
class EncryptHelper
{
    /**
     * Credential path
     *
     * @var string
     */
    protected static $credentialFile = ENVIRONMENT === 'development'
        ? HASH_TOKEN_PATH_DEV
        : HASH_TOKEN_PATH;

    /**
     * Credential data
     *
     * @var mixed
     */
    protected static $credentialFileData = HASH_TOKEN_DATA;

    /**
     * Encrypt temp map
     *
     * @var array
     */
    protected static $encryptTemp = [
        'private' => [],
        'custom' => [],
        'web' => [],
    ];

    /**
     * Encrypt custom key
     *
     * @param  string   $string
     * @return string
     */
    public static function encryptCustom($string, $iv = null)
    {
        return self::_encryptNueip($string, 'custom', null, $iv);
    }

    /**
     * Decrypt custom key
     *
     * @param  string   $string
     * @return string
     */
    public static function decryptCustom($string, $iv = null)
    {
        return self::_decryptNueip($string, 'custom', null, $iv);
    }

    /**
     * Encrypt web data
     *
     * @param  string   $string
     * @return string
     */
    public static function encryptWeb($string)
    {
        return urlencode(self::_encryptNueip($string, 'web', session_id()));
    }

    /**
     * Decrypt web data
     *
     * @param  string   $string
     * @return string
     */
    public static function decryptWeb($string)
    {
        return self::_decryptNueip(urldecode($string), 'web', session_id());
    }

    /**
     * Encrypt
     *
     * @param  string   $string
     * @return string
     */
    public static function encrypt($string)
    {
        $type = 'private';
        $temp = &self::$encryptTemp[$type];
        $method = self::_getEncryptMethod($type);
        $key = self::_getEncryptKey($type);

        $result = array_search($string, $temp, true);
        $result = $result === false
            ? trim(base64_encode(@mcrypt_encrypt($method, $key, $string, 'ecb')))
            : strval($result);
        $temp[$result] ?? $temp[$result] = $string;
        return $result;
    }

    /**
     * Decrypt
     *
     * @param  string   $string
     * @return string
     */
    public static function decrypt($string)
    {
        $type = 'private';
        $temp = &self::$encryptTemp[$type];
        $method = self::_getEncryptMethod($type);
        $key = self::_getEncryptKey($type);

        return $temp[$string] ?? $temp[$string] = trim(@mcrypt_decrypt($method, $key, base64_decode($string), 'ecb'));
    }

    /**
     * Get credential file data
     *
     * @return array
     */
    private static function _getCredentialData()
    {
        // Read Credential content
        if (!isset(self::$credentialFileData)) {
            self::$credentialFileData = file_exists(self::$credentialFile)
                ? json_decode(file_get_contents(self::$credentialFile), true)
                : [];
        }

        return self::$credentialFileData;
    }

    /**
     * Get encrypt method
     *
     * @param  string   $type
     * @return string
     */
    private static function _getEncryptMethod($type)
    {
        return self::_getCredentialData()['method'][$type] ?? '';
    }

    /**
     * Get encrypt key
     *
     * @return string
     */
    private static function _getEncryptKey($type)
    {
        return self::_getCredentialData()['key'][$type] ?? '';
    }

    /**
     * Get system iv
     *
     * @param  string   $custom
     * @return string
     */
    private static function _getSystemIV($custom = null)
    {
        /**
         * @var mixed
         */
        static $iv = null;

        if (isset($custom)) {
            return hex2bin(md5(strval($custom)));
        } else {
            if (!isset($iv)) {
                $systemIV = function_exists('getSystemIV')
                    ? getSystemIV()
                    : 'get System IV function not found.';
                $iv = hex2bin(md5(strval($systemIV)));
            }
        }

        return $iv;
    }

    /**
     * Encrypt Nueip
     *
     * @param  string   $string
     * @return string
     */
    private static function _encryptNueip($string, $type, $key = null, $iv = null)
    {
        $temp = &self::$encryptTemp[$type];
        $method = self::_getEncryptMethod($type);
        $key = $key ?? self::_getEncryptKey($type);
        $iv = self::_getSystemIV($iv);

        $result = array_search($string, $temp, true);
        $result = $result === false
            ? openssl_encrypt($string, $method, $key, 0, $iv)
            : strval($result);

        $temp[$iv][$result] ?? $temp[$iv][$result] = $string;
        return $result;
    }

    /**
     * Decrypt Nueip
     *
     * @param  string   $string
     * @return string
     */
    private static function _decryptNueip($string, $type, $key = null, $iv = null)
    {
        $temp = &self::$encryptTemp[$type];
        $method = self::_getEncryptMethod($type);
        $key = $key ?? self::_getEncryptKey($type);
        $iv = self::_getSystemIV($iv);

        return $temp[$iv][$string] ?? $temp[$iv][$string] = openssl_decrypt($string, $method, $key, 0, $iv);
    }
}

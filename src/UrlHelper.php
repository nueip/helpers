<?php

namespace nueip\helpers;

use nueip\helpers\EncryptHelper;

/**
 * Url Helper
 *
 * @author Nick Lai
 */
class UrlHelper
{
    /**
     * Safe-merge URI segments.
     *
     * @param array $segments
     * 
     * @example 
     *  \nueip\helpers\UrlHelper::safeMergeSegments(['https://example.com/', '/first/second/', '////user_profile//', 'show/200'])
     *    result -> 'https://example.com/first/second/user_profile/show/200'
     * 
     *  \nueip\helpers\UrlHelper::safeMergeSegments(['//https://example.com/', '/test//'], true)
     *    result -> 'https://example.com/test'
     *
     * @return string URI
     */
    public static function safeMergeSegments(array $segments, $trimMergedUri = false)
    {
        $uri = array_shift($segments);

        foreach ($segments as $segment) {
            $uri = rtrim($uri, '/') . '/' . ltrim($segment, '/');
        }

        return $trimMergedUri ? trim($uri, '/') : $uri;
    }

    /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string A decoded string
     */
    public static function safeBase64Decode($input)
    {
        return base64_decode(self::_safeBase64Reduction($input));
    }

    /**
     * Encode a string with URL-safe Base64.
     *
     * @param string $input The string you want encoded
     *
     * @return string The base64 encode of what you passed in
     */
    public static function safeBase64Encode($input)
    {
        return self::_safeBase64Replace(base64_encode($input));
    }

    /**
     * decode the params passed by GET method
     *
     * @param string $paramKey
     * @return mixed
     */
    public static function decodeUrlParams($paramKey = 'params')
    {
        $params = $_GET[$paramKey] ?? null;

        if (!isset($params)) {
            return null;
        }

        // Reduction Base64
        $params = self::_safeBase64Reduction($params);

        // 優先使用新的解密方式
        $decodeData = json_decode(EncryptHelper::decryptCustom($params, 'UrlParams'), true);

        // 若新的解密無法正常解析，使用舊的解密方式(目前使用到舊解密的地方只有以前送過的通知信，本段函式可在2021年後移除)
        if (json_last_error() !== JSON_ERROR_NONE) {
            $decodeData = json_decode(revert_hash($params), true);
        }

        return $decodeData;
    }

    /**
     * encode the params which would pass with GET method
     *
     * @param array $data
     * @return string
     */
    public static function encodeUrlParams($data)
    {
        return urlencode(self::_safeBase64Replace(EncryptHelper::encryptCustom(json_encode($data), 'UrlParams')));
    }

    /**
     * Reduction a string with URL-safe Base64.
     *
     * @param string $input A URL-safe Base64 encoded string
     *
     * @return string A decoded string
     */
    private static function _safeBase64Reduction($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padLength = 4 - $remainder;
            $input .= str_repeat('=', $padLength);
        }
        return strtr($input, '-_', '+/');
    }

    /**
     * Replace a string with Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string The base64 encode of what you passed in
     */
    private static function _safeBase64Replace($input)
    {
        return str_replace('=', '', strtr($input, '+/', '-_'));
    }
}

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
        return urlencode(EncryptHelper::encryptCustom(json_encode($data), 'UrlParams'));
    }
}

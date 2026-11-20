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
     * Only a string value is decoded; a missing parameter or an array (e.g. `?params[]=...`) yields null.
     *
     * @param string $paramKey
     * @return mixed|null Decoded data, or null when the parameter is not a decodable string
     */
    public static function decodeUrlParams($paramKey = 'params')
    {
        $params = $_GET[$paramKey] ?? null;

        if (!is_string($params)) {
            return null;
        }

        // decode the string
        return self::decode($params);
    }

    /**
     * Decode a string produced by encodeUrlParams()
     *
     * Only ciphertext from encodeUrlParams() is accepted; any other input, including plaintext JSON, yields null.
     *
     * @param string $hash
     * @return mixed|null Decoded data, or null when decryption fails or the plaintext is not valid JSON
     */
    public static function decode(string $hash)
    {
        $plaintext = EncryptHelper::decryptCustom(self::_safeBase64Reduction($hash), 'UrlParams');

        if (!is_string($plaintext)) {
            return null;
        }

        $decodeData = json_decode($plaintext, true);

        return json_last_error() === JSON_ERROR_NONE ? $decodeData : null;
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

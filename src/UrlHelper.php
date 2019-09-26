<?php

namespace nueip\helpers;

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
        return isset($_GET[$paramKey])
            ? json_decode(revert_hash($_GET[$paramKey]), true)
            : null;
    }

    /**
     * encode the params which would pass with GET method
     *
     * @param array $data
     * @return string
     */
    public static function encodeUrlParams($data)
    {
        return urlencode(my_hash(json_encode($data)));
    }
}

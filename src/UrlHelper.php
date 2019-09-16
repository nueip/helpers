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
     * 解析由通知訊息所帶入的額外參數
     *
     * @return mixed
     */
    public static function parseNoticeParams()
    {
        return isset($_GET['params'])
            ? json_decode(revert_hash($_GET['params']), true)
            : null;
    }
}

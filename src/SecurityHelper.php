<?php

namespace nueip\helpers;

/**
 * Security Helper
 *
 * @author Nick Lai
 * @author Gunter Chou
 */
class SecurityHelper
{
    /**
     * Convert special characters to HTML entities
     *
     * @example
     *  $result = SecurityHelper::deepHtmlspecialchars(
     *      ['name' => 'Hello<script>alert("World");</script>']
     *  );
     *
     * @param  mixed $data The data being deep converted.
     * @return mixed The converted data.
     */
    public static function deepHtmlspecialchars($data)
    {
        if (is_array($data) || is_object($data)) {
            foreach ($data as &$row) {
                $row = self::deepHtmlspecialchars($row);
            }
        } else {
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }

        return $data;
    }

    /**
     * Identifying the "originating protocol" is HTTPS request
     *
     * @return boolean
     */
    public static function isHttps()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            return strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https';
        } elseif (isset($_SERVER['HTTP_FRONT_END_HTTPS'])) {
            return strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) === 'on';
        } elseif (isset($_SERVER['HTTPS'])) {
            return strtolower($_SERVER['HTTPS']) !== 'off';
        }

        return false;
    }
}

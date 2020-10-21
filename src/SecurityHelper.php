<?php

namespace nueip\helpers;

use RobThree\Auth\TwoFactorAuth;

/**
 * Security Helper
 *
 * @author Nick Lai
 * @author Gunter Chou
 */
class SecurityHelper
{
    /**
     * @var TwoFactorAuth
     */
    static $totpSpace = null;

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

    /**
     * Set TwoFactorAuth display organization name
     *
     * @param  string                   $space
     * @throws TwoFactorAuthException
     * @return TwoFactorAuth
     */
    public static function setTotpSpace($space)
    {
        return self::$totpSpace = new TwoFactorAuth($space);
    }

    /**
     * Get TwoFactorAuth object
     *
     * @throws TwoFactorAuthException
     * @return TwoFactorAuth
     */
    public static function getTotpSpace()
    {
        return is_a(self::$totpSpace, TwoFactorAuth::class) ? self::$totpSpace : self::setTotpSpace(null);
    }

    /**
     * Get TwoFactorAuth secret string
     *
     * @param  integer                  $bits
     * @throws TwoFactorAuthException
     * @return string
     */
    public static function getTotpSecret($bits = 160)
    {
        return self::getTotpSpace()->createSecret($bits);
    }

    /**
     * Get TwoFactorAuth secret Qr Code
     *
     * @param  string                   $label
     * @param  string                   $secret
     * @throws TwoFactorAuthException
     * @return string
     */
    public static function getTotpQrCode($label, $secret)
    {
        return self::getTotpSpace()->getQRCodeImageAsDataUri($label, $secret);
    }

    /**
     * Verify TwoFactorAuth
     *
     * @param  string $secret
     * @param  string $code
     * @return bool
     */
    public static function verifyTotp($secret, $code)
    {
        return self::getTotpSpace()->verifyCode($secret, $code);
    }
}

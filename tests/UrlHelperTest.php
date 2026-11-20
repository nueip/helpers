<?php

use nueip\helpers\EncryptHelper;
use nueip\helpers\UrlHelper;
use PHPUnit\Framework\TestCase;

defined('HASH_TOKEN_DATA') or define('HASH_TOKEN_DATA', json_decode(file_get_contents(__DIR__ . '/credentials/encryptToken.json'), true));

// Consumers define revert_hash() as a near-identity function; keep it defined so decode() is tested against that behaviour.
if (!function_exists('revert_hash')) {
    function revert_hash($hash)
    {
        return $hash;
    }
}

/**
 * Test for UrlHelper - PHP Unit
 */
class UrlHelperTest extends TestCase
{
    public function testEncodeUrlParamsRoundTrip()
    {
        $data = ['u_sn' => 123, 'c_sn' => 45, 'token' => 'a+b/c=d', 'flag' => false];

        $encoded = UrlHelper::encodeUrlParams($data);
        $this->clearEncryptCache();

        // Consumers receive the value through $_GET, which PHP has already URL-decoded.
        $this->assertSame($data, UrlHelper::decode(urldecode($encoded)));
    }

    public function testDecodeUrlParamsRoundTrip()
    {
        $data = ['id' => 'abc-_123', 'list' => [1, 2, 3]];

        $_GET['params'] = urldecode(UrlHelper::encodeUrlParams($data));
        $this->clearEncryptCache();

        $this->assertSame($data, UrlHelper::decodeUrlParams());
    }

    public function testDecodeRejectsUnencryptedJson()
    {
        $this->assertNull(UrlHelper::decode('{"a":12}'));
        $this->assertNull(UrlHelper::decode('[1,2,3,4]'));
    }

    public function testDecodeUrlParamsRejectsUnencryptedJson()
    {
        $_GET['params'] = '{"a":12}';

        $this->assertNull(UrlHelper::decodeUrlParams());
    }

    public function testDecodeRejectsTamperedCiphertext()
    {
        $encoded = urldecode(UrlHelper::encodeUrlParams(['u_sn' => 123]));
        $this->clearEncryptCache();

        $this->assertNull(UrlHelper::decode(substr($encoded, 0, -4) . 'AAAA'));
    }

    public function testDecodeRejectsGarbage()
    {
        $this->assertNull(UrlHelper::decode(''));
        $this->assertNull(UrlHelper::decode('not-an-encrypted-link'));
    }

    public function testDecodeUrlParamsReturnsNullForArrayValue()
    {
        // `?params[]=...` arrives in $_GET as an array, not a string.
        $_GET['params'] = [urldecode(UrlHelper::encodeUrlParams(['u_sn' => 123]))];
        $this->clearEncryptCache();

        $this->assertNull(UrlHelper::decodeUrlParams());
    }

    public function testDecodeUrlParamsReturnsNullWhenMissing()
    {
        $_GET = [];

        $this->assertNull(UrlHelper::decodeUrlParams());
    }

    /**
     * Reset EncryptHelper's in-memory plaintext cache so decode() exercises real decryption.
     */
    private function clearEncryptCache()
    {
        $property = new ReflectionProperty(EncryptHelper::class, 'encryptTemp');
        $property->setAccessible(true);
        $property->setValue(null, ['private' => [], 'custom' => [], 'web' => []]);
    }
}

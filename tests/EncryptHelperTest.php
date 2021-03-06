<?php

use nueip\helpers\EncryptHelper;
use PHPUnit\Framework\TestCase;

define('ENVIRONMENT', 'development');
define('HASH_TOKEN_PATH_DEV', __DIR__ . '/credentials/encryptToken.json');
define('HASH_TOKEN_PATH', __DIR__ . '/credentials/encryptToken.json');

/**
 * Test for EncryptHelperTest - PHP Unit
 */
class EncryptHelperTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testEncrypt($string)
    {
        $encrypt = EncryptHelper::encrypt($string);
        $decrypt = EncryptHelper::decrypt($encrypt);
        $this->assertNotEquals($string, $encrypt);
        $this->assertEquals($string, $decrypt);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testWebEncrypt($string)
    {
        $encrypt = EncryptHelper::encryptWeb($string);
        $decrypt = EncryptHelper::decryptWeb($encrypt);

        $this->assertNotEquals($string, $encrypt);
        $this->assertEquals($string, $decrypt);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCustomEncrypt($string)
    {
        $encrypt = EncryptHelper::encryptCustom($string, '61SD5F5');
        $decrypt = EncryptHelper::decryptCustom($encrypt, '61SD5F5');
        $this->assertNotEquals($string, $encrypt);
        $this->assertEquals($string, $decrypt);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            ['5000'],
            ['PE 0%%20^.^%2000'],
            [9999],
            [0],
            [1],
            [false],
            [true],
            [null],
            [''],
            [json_encode(['a' => 2123, 'b' => false])],
        ];
    }
}

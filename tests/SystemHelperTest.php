<?php

use PHPUnit\Framework\TestCase;
use nueip\helpers\SystemHelper;

/**
 * Test for TimePeriodHelper - PHP Unit
 * 
 * @author Mars.Hung <tfaredexj@gmail.com>
 */
class SystemHelperTest extends TestCase
{

    /**
     * *********************************************
     * ************** Public Function **************
     * *********************************************
     */

    /**
     * Test memoryMoreThan
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testMemoryMoreThan()
    {
        $templete = self::memoryMoreThanData();
        $expected = self::memoryMoreThanExpected();

        foreach ($templete as $k => $v) {
            SystemHelper::memoryMoreThan($v);

            $result = ini_get('memory_limit');
            $this->assertEquals($expected[$k], $result);
        }
    }

    /**
     * ****************************************************
     * ************** Data Templete Function **************
     * ****************************************************
     */

    /**
     * Test Data - Sort
     * @return array
     */
    protected static function memoryMoreThanData()
    {
        return [
            '256M',
            '512M',
            '256M',
        ];
    }

    /**
     * Expected Data - Sort
     * @return array
     */
    protected static function memoryMoreThanExpected()
    {
        return [
            '256M',
            '512M',
            '512M',
        ];
    }
}

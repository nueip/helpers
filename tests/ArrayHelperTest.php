<?php

use nueip\helpers\ArrayHelper;
use PHPUnit\Framework\TestCase;

/**
 * Test for ArrayHelperTest - PHP Unit
 */
class ArrayHelperTest extends TestCase
{
    /**
     * test getClosest
     * 
     * @dataProvider courseProvider
     */
    public function testGetClosest($data, $needle, $compareWith, $ans)
    {
        $closest = ArrayHelper::getClosest($data, $needle, $compareWith);

        $this->assertEquals($closest, $ans);
    }

    /**
     * Set course data
     */
    public function courseProvider()
    {
        $intKeyData = [
            90 => 'test90',
            51 => 'test51',
            30 => 'test30',
            66 => 'test66',
        ];

        $dateKeyData = [
            '2020-03-01' => 'test0301',
            '2020-02-11' => 'test0211',
            '2020-01-15' => 'test0115',
            '2020-01-20' => 'test0120',
            '2020-01-31' => 'test0131',
        ];

        return [
            // 查詢目標 小於資料中最小鍵值
            [$intKeyData, 13, 'closest', 'test30'],
            [$intKeyData, 13, 'more', 'test30'],
            [$intKeyData, 13, 'less', null],
            // 查詢目標 大於資料中最小鍵值 小於資料中最大鍵值
            [$intKeyData, 50, 'closest', 'test51'],
            [$intKeyData, 50, 'more', 'test51'],
            [$intKeyData, 50, 'less', 'test30'],
            // 查詢目標 大於資料中最大鍵值
            [$intKeyData, 99, 'closest', 'test90'],
            [$intKeyData, 99, 'more', null],
            [$intKeyData, 99, 'less', 'test90'],
            // 查詢目標日期 小於資料中最小鍵值
            [$dateKeyData, '2020-01-01', 'closest', 'test0115'],
            [$dateKeyData, '2020-01-01', 'more', 'test0115'],
            [$dateKeyData, '2020-01-01', 'less', null],
            // 查詢目標日期 大於資料中最小鍵值 小於資料中最大鍵值
            [$dateKeyData, '2020-02-01', 'closest', 'test0131'],
            [$dateKeyData, '2020-02-01', 'more', 'test0211'],
            [$dateKeyData, '2020-02-01', 'less', 'test0131'],
            // 查詢目標日期 大於資料中最大鍵值
            [$dateKeyData, '2020-04-01', 'closest', 'test0301'],
            [$dateKeyData, '2020-04-01', 'more', null],
            [$dateKeyData, '2020-04-01', 'less', 'test0301'],
        ];
    }
}

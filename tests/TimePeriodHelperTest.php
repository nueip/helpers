<?php

namespace nueip\helpers\tests;

use PHPUnit\Framework\TestCase;
use nueip\helpers\TimePeriodHelper;
use marsapp\helper\timeperiod\TimePeriodHelper as tpHelper;
use marsapp\helper\timeperiod\classes\DataProcess;

/**
 * Test for TimePeriodHelper - PHP Unit
 * 
 * @author Mars.Hung <tfaredexj@gmail.com>
 */
class TimePeriodHelperTest extends TestCase
{

    /**
     * *********************************************
     * ************** Public Function **************
     * *********************************************
     */

    /**
     * Test Sort
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testSort()
    {
        $templete = self::sortData();
        $expected = self::sortExpected();

        $result = TimePeriodHelper::sort($templete);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Union
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testUnion()
    {
        $templete1 = self::unionData1();
        $templete2 = self::unionData2();
        $expected = self::unionExpected();

        $result = TimePeriodHelper::union($templete1, $templete2);

        $this->assertEquals($expected, $result);

        // test Empty
        $result = TimePeriodHelper::union([]);
        $this->assertEquals([], $result);
    }

    /**
     * Test Diff
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testDiff()
    {
        $templete1 = self::diffData1();
        $templete2 = self::diffData2();
        $expected = self::diffExpected();

        // The same
        TimePeriodHelper::setSortOut(true);
        $result = TimePeriodHelper::diff($templete1, $templete2);
        $this->assertEquals($expected, $result);

        // Need Different
        TimePeriodHelper::setSortOut(false);
        $result = TimePeriodHelper::diff($templete1, $templete2);
        $this->assertNotEquals($expected, $result);

        // The same
        $result = TimePeriodHelper::diff($templete1, $templete2, true);
        $this->assertEquals($expected, $result);

        // Need Different
        $result = TimePeriodHelper::diff($templete1, $templete2, false);
        $this->assertNotEquals($expected, $result);

        $result = TimePeriodHelper::diff([], $templete2);
        $this->assertNotEquals($expected, $result);
    }

    /**
     * Test Intersect
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testIntersect()
    {
        $templete1 = self::intersectData1();
        $templete2 = self::intersectData2();
        $expected = self::intersectExpected();

        // The same
        TimePeriodHelper::setSortOut(true);
        $result = TimePeriodHelper::intersect($templete1, $templete2);
        $this->assertEquals($expected, $result);

        // Need Different
        TimePeriodHelper::setSortOut(false);
        $result = TimePeriodHelper::intersect($templete1, $templete2);
        $this->assertNotEquals($expected, $result);

        // The same
        $result = TimePeriodHelper::intersect($templete1, $templete2, true);
        $this->assertEquals($expected, $result);

        // Need Different
        $result = TimePeriodHelper::intersect($templete1, $templete2, false);
        $this->assertNotEquals($expected, $result);

        // Need Different
        $result = TimePeriodHelper::intersect([], $templete2, false);
        $this->assertNotEquals($expected, $result);
    }

    /**
     * Test IsOverlap
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testIsOverlap()
    {
        $templete1 = self::isOverlapData1();
        $templete2 = self::isOverlapData2();
        $expected = self::isOverlapExpected();

        $result = TimePeriodHelper::isOverlap([], $templete2[0]);
        $this->assertEquals(false, $result);

        foreach ($templete1 as $k1 => $v1) {
            $result = TimePeriodHelper::isOverlap($templete1[$k1], $templete2[$k1]);
            $this->assertEquals($expected[$k1], $result);
        }
    }

    /**
     * Test Contact
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testContact()
    {
        $templetes = self::contactData();
        $expecteds = self::contactExpected();

        // The same, Auto sorting out $templete
        TimePeriodHelper::setSortOut(true);
        foreach ($templetes as $k => $templete) {
            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'contact'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }

        // Test Empty input
        $result = TimePeriodHelper::contact([], '2019-06-11 15:00:00');
        $this->assertNotEquals(false, $result);
    }

    /**
     * Test GreaterThan
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testGreaterThan()
    {
        $templetes = self::greaterThanData();
        $expecteds = self::greaterThanExpected();

        // The same, Auto sorting out $templete
        TimePeriodHelper::setSortOut(true);
        foreach ($templetes as $k => $templete) {
            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'greaterThan'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }

        // Test Empty input
        $result = TimePeriodHelper::greaterThan([], '2019-06-11 15:00:00');
        $this->assertNotEquals(false, $result);
    }

    /**
     * Test LessThan
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testLessThan()
    {
        $templetes = self::lessThanData();
        $expecteds = self::lessThanExpected();

        // The same, Auto sorting out $templete
        TimePeriodHelper::setSortOut(true);
        foreach ($templetes as $k => $templete) {
            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'lessThan'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }

        // Test Empty input
        $result = TimePeriodHelper::lessThan([], '2019-06-11 15:00:00');
        $this->assertNotEquals(false, $result);
    }

    /**
     * Test Fill
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testFill()
    {
        $templete = self::fillData();
        $expected = self::fillExpected();

        $result = TimePeriodHelper::fill($templete);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test Gap
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testGap()
    {
        $templete = self::gapData();
        $expected = self::gapExpected();

        // The same
        TimePeriodHelper::setSortOut(true);
        $result = TimePeriodHelper::gap($templete);
        $this->assertEquals($expected, $result);

        // Need Different
        TimePeriodHelper::setSortOut(false);
        $result = TimePeriodHelper::gap($templete);
        $this->assertNotEquals($expected, $result);

        // The same
        $result = TimePeriodHelper::gap($templete, true);
        $this->assertEquals($expected, $result);

        // Need Different
        $result = TimePeriodHelper::gap($templete, false);
        $this->assertNotEquals($expected, $result);

        // Test Empty input
        $result = TimePeriodHelper::gap([]);
        $this->assertEquals([], $result);
    }

    /**
     * Test Time
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testTime()
    {
        $templete = self::timeData();
        $expected = self::timeExpected();

        TimePeriodHelper::setUnit('second');

        // The same, Auto sorting out $templete - second
        TimePeriodHelper::setSortOut(true);
        $result = TimePeriodHelper::time($templete);
        $this->assertEquals($expected, $result);

        // Need Different, No sorting out $templete - second
        TimePeriodHelper::setSortOut(false);
        $result = TimePeriodHelper::time($templete);
        $this->assertNotEquals($expected, $result);

        // The same, Set sorting out $templete by argument - second
        $result = TimePeriodHelper::time($templete, 0, true);
        $this->assertEquals($expected, $result);

        // Need Different, No sorting out $templete by argument - second
        $result = TimePeriodHelper::time($templete, 0, false);
        $this->assertNotEquals($expected, $result);

        // The same, Auto sorting out $templete - minutes - No precision
        TimePeriodHelper::setUnit('minutes');
        TimePeriodHelper::setSortOut(true);
        $result = TimePeriodHelper::time($templete, 0);
        $this->assertEquals(floor($expected / 60), $result);

        // The same, Auto sorting out $templete - minutes - Has precision
        TimePeriodHelper::setUnit('minutes');
        TimePeriodHelper::setSortOut(true);
        $result = TimePeriodHelper::time($templete, 2);
        $this->assertEquals(((int) ($expected / 60 * 100)) / 100, $result);

        // The same, Manually sorting out $templete - hour
        TimePeriodHelper::setUnit('h');
        TimePeriodHelper::setSortOut(false);
        $templete = TimePeriodHelper::union($templete);
        $result = TimePeriodHelper::time($templete);
        $this->assertEquals(floor($expected / 3600), $result);

        // The same, Auto sorting out $templete - hour - Has precision
        TimePeriodHelper::setUnit('hour');
        TimePeriodHelper::setSortOut(true);
        $result = TimePeriodHelper::time($templete, 2);
        $this->assertEquals(((int) ($expected / 3600 * 100)) / 100, $result);

        // Test Empty input
        $result = TimePeriodHelper::time([]);
        $this->assertEquals(0, $result);
    }

    /**
     * Test Cut
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testCut()
    {
        $templetes = self::cutData();
        $expecteds = self::cutExpected();
        $templetesNS = self::cutNotSortData();
        $expectedsNS = self::cutNotSortExpected();

        // The same, Auto sorting out $templete
        TimePeriodHelper::setSortOut(true);
        foreach ($templetes as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[2]);
            unset($templete[2]);

            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'cut'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }

        // Need Different, No sorting out $templete
        TimePeriodHelper::setSortOut(false);
        foreach ($templetesNS  as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[2]);
            unset($templete[2]);

            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'cut'], $templete);
            $this->assertNotEquals($expectedsNS[$k], $result);
        }

        // The same, Auto sorting out $templete by argument
        foreach ($templetes as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[2]);
            unset($templete[2]);
            // Set sort out by argument
            $templete[2] = true;

            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'cut'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }

        // Need Different, No sorting out $templete by argument
        foreach ($templetesNS as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[2]);
            unset($templete[2]);
            // Set sort out by argument
            $templete[2] = false;

            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'cut'], $templete);
            $this->assertNotEquals($expectedsNS[$k], $result);
        }

        // Test Empty input
        $result = TimePeriodHelper::cut([], 0);
        $this->assertEquals([], $result);
    }

    /**
     * Test Extend
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testExtend()
    {
        $templetes = self::extendData();
        $expecteds = self::extendExpected();
        $templetesNS = self::extendNotSortData();
        $expectedsNS = self::extendNotSortExpected();

        // The same, Auto sorting out $templete
        TimePeriodHelper::setSortOut(true);
        foreach ($templetes as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[3]);
            unset($templete[3]);

            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'extend'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }

        // Need Different, No sorting out $templete
        TimePeriodHelper::setSortOut(false);
        foreach ($templetesNS as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[3]);
            unset($templete[3]);

            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'extend'], $templete);
            $this->assertNotEquals($expectedsNS[$k], $result);
        }

        // The same, Auto sorting out $templete by argument
        foreach ($templetes as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[3]);
            unset($templete[3]);
            // Set sort out by argument
            $templete[3] = true;

            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'extend'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }

        // Need Different, No sorting out $templete by argument
        foreach ($templetesNS as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[3]);
            unset($templete[3]);
            // Set sort out by argument
            $templete[3] = false;

            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'extend'], $templete);
            $this->assertNotEquals($expectedsNS[$k], $result);
        }

        // Test Empty input
        $result = TimePeriodHelper::extend([], 0);
        $this->assertEquals([], $result);
    }

    /**
     * Test Shorten
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testShorten()
    {
        $templetes = self::shortenData();
        $expecteds = self::shortenExpected();
        $templetesNS = self::shortenNotSortData();
        $expectedsNS = self::shortenNotSortExpected();


        // The same, Auto sorting out $templete
        TimePeriodHelper::setSortOut(true);
        foreach ($templetes as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[3]);
            unset($templete[3]);

            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'shorten'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }

        // Need Different, No sorting out $templete
        TimePeriodHelper::setSortOut(false);
        foreach ($templetesNS as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[3]);
            unset($templete[3]);

            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'shorten'], $templete);
            $this->assertNotEquals($expectedsNS[$k], $result);
        }

        // The same, Auto sorting out $templetee by argument
        foreach ($templetes as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[3]);
            unset($templete[3]);
            // Set sort out by argument
            $templete[3] = true;

            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'shorten'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }

        // Need Different, No sorting out $templetee by argument
        foreach ($templetesNS as $k => $templete) {
            // Set time uint
            TimePeriodHelper::setUnit($templete[3]);
            unset($templete[3]);
            // Set sort out by argument
            $templete[3] = false;

            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'shorten'], $templete);
            $this->assertNotEquals($expectedsNS[$k], $result);
        }


        // Test Empty input
        $result = TimePeriodHelper::shorten([], 0);
        $this->assertEquals([], $result);
    }

    /**
     * Test Format
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testFormat()
    {
        $templete = self::formatData();
        $expectedS = self::formatExpectedS();
        $expectedM = self::formatExpectedM();
        $expectedH = self::formatExpectedH();

        // The same
        TimePeriodHelper::setUnit('s');
        $result = TimePeriodHelper::format($templete);
        $this->assertEquals($expectedS, $result);

        // The same
        TimePeriodHelper::setUnit('minute');
        $result = TimePeriodHelper::format($templete);
        $this->assertEquals($expectedM, $result);

        // The same
        TimePeriodHelper::setUnit('hours');
        $result = TimePeriodHelper::format($templete);
        $this->assertEquals($expectedH, $result);

        // The same
        $result = TimePeriodHelper::format($templete);
        $this->assertEquals($expectedH, $result);

        // The same
        $result = TimePeriodHelper::format($templete, 'second');
        $this->assertEquals($expectedS, $result);

        // Need Different
        $result = TimePeriodHelper::format($templete);
        $this->assertNotEquals($expectedS, $result);
    }

    /**
     * Test Validate
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testValidate()
    {
        $templete = self::validateData();
        $expected = self::validateExpected();

        foreach ($templete as $k => $tp) {
            try {
                $result = TimePeriodHelper::validate($tp);
            } catch (\Exception $e) {
                $result = false;
            }
            $this->assertEquals($expected[$k], $result);
        }
    }

    /**
     * Test Filter
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testFilter()
    {
        $templete1 = self::filterData1();
        $templete2 = self::filterData2();
        $expected1 = self::filterExpected1();
        $expected2 = self::filterExpected2();

        // not array
        $result0 = TimePeriodHelper::filter('');
        $this->assertEquals([], $result0);

        // The same
        $result1 = TimePeriodHelper::filter($templete1);
        $this->assertEquals($expected1, $result1);

        // The same
        $result2 = TimePeriodHelper::filter($templete2);
        $this->assertEquals($expected2, $result2);

        // Need Different
        $this->assertNotEquals($expected2, $result1);
    }

    /**
     * Test IsDatetime
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testIsDatetime()
    {
        $templetes = self::isDatetimeData();
        $expecteds = self::isDatetimeExpected();

        // The same, Auto sorting out $templete
        foreach ($templetes as $k => $templete) {
            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'isDatetime'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }
    }

    /**
     * Test TimeFormatConv
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testTimeFormatConv()
    {
        $templetes = self::timeFormatConvData();
        $expecteds = self::timeFormatConvExpected();

        // The same, default unit: second
        TimePeriodHelper::setUnit('second');
        foreach ($templetes as $k => $templete) {
            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'timeFormatConv'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }
    }

    /**
     * Test Time2Second
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testTime2Second()
    {
        $templetes = self::time2SecondData();
        $expecteds = self::time2SecondExpected();

        // The same, default unit: second
        TimePeriodHelper::setUnit('second');
        foreach ($templetes as $k => $templete) {
            // The same
            $result = \call_user_func_array(['\nueip\helpers\TimePeriodHelper', 'time2Second'], $templete);
            $this->assertEquals($expecteds[$k], $result);
        }
    }

    /**
     * Test Set/Get Unit
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testSetGetUnit()
    {
        // check return type && set all target unit:hour
        $result = TimePeriodHelper::setUnit('hour');
        $this->assertInstanceOf(tpHelper::class, $result);

        $unitTime = TimePeriodHelper::getUnit('time');
        $unitFormat = TimePeriodHelper::getUnit('format');
        $this->assertEquals('hour', $unitTime);
        $this->assertEquals('hour', $unitFormat);


        // set time unit: mintue, format unit: second
        TimePeriodHelper::setUnit('minute', 'time')->setUnit('second', 'format');
        $unitTime = TimePeriodHelper::getUnit('time');
        $unitFormat = TimePeriodHelper::getUnit('format');
        $this->assertEquals('minute', $unitTime);
        $this->assertEquals('second', $unitFormat);


        // invalid unit for set unit
        try {
            $result = true;
            TimePeriodHelper::setUnit('notThisUnit');
        } catch (\Exception $e) {
            $result = false;
        }
        $this->assertEquals(false, $result);


        // invalid target for set unit
        try {
            $result = true;
            TimePeriodHelper::setUnit('hour', 'notThisTarget');
        } catch (\Exception $e) {
            $result = false;
        }
        $this->assertEquals(false, $result);


        // invalid target for get unit
        try {
            $result = true;
            TimePeriodHelper::getUnit('notThisTarget');
        } catch (\Exception $e) {
            $result = false;
        }
        $this->assertEquals(false, $result);

        // Check static options hour - need global
        DataProcess::setUnit('hour');
        $unitTime = TimePeriodHelper::getUnit('time');
        $this->assertEquals('hour', $unitTime);

        // Check static options minute - need global
        TimePeriodHelper::setUnit('minute');
        $unitTime = DataProcess::getUnit('format');
        $this->assertEquals('minute', $unitTime);
    }

    /**
     * Test Set/Get FilterDatetime
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testSetGetFilterDatetime()
    {
        // check return type
        $result = TimePeriodHelper::setFilterDatetime(true);
        $this->assertInstanceOf(tpHelper::class, $result);

        // check is set
        $result = TimePeriodHelper::getFilterDatetime();
        $this->assertEquals(true, $result);
        $result = TimePeriodHelper::setFilterDatetime(false)->getFilterDatetime();
        $this->assertEquals(false, $result);

        // Check static options - need global
        TimePeriodHelper::setFilterDatetime(true);
        $result = DataProcess::getFilterDatetime();
        $this->assertEquals(true, $result);
    }

    /**
     * Test Set/Get SortOut
     * 
     * @author Mars.Hung <tfaredexj@gmail.com>
     */
    public function testSetGetSortOut()
    {
        // check return type
        $result = TimePeriodHelper::setSortOut(true);
        $this->assertInstanceOf(tpHelper::class, $result);

        // check is set
        $result = TimePeriodHelper::getSortOut();
        $this->assertEquals(true, $result);
        $result = TimePeriodHelper::setSortOut(false)->getSortOut();
        $this->assertEquals(false, $result);

        // Check static options - need global
        TimePeriodHelper::setSortOut(true);
        $result = DataProcess::getSortOut();
        $this->assertEquals(true, $result);
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
    protected static function sortData()
    {
        return [
            ['2019-01-04 12:00:00', '2019-01-04 18:00:00'],
            ['2019-01-04 08:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 12:00:00', '2019-01-04 18:00:00'],
            ['2019-01-04 12:00:00', '2019-01-04 17:00:00'],
            ['2019-01-04 12:00:00', '2019-01-04 19:00:00'],
            ['2019-01-04 08:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 09:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 07:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 10:00:00', '2019-01-04 16:00:00'],
            ['2019-01-04 11:00:00', '2019-01-04 18:00:00'],
            ['2019-01-04 10:00:00', '2019-01-04 18:00:00'],
            ['2019-01-04 11:00:00', '2019-01-04 15:00:00']
        ];
    }

    /**
     * Expected Data - Sort
     * @return array
     */
    protected static function sortExpected()
    {
        return [
            ['2019-01-04 07:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 08:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 08:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 09:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 10:00:00', '2019-01-04 16:00:00'],
            ['2019-01-04 10:00:00', '2019-01-04 18:00:00'],
            ['2019-01-04 11:00:00', '2019-01-04 15:00:00'],
            ['2019-01-04 11:00:00', '2019-01-04 18:00:00'],
            ['2019-01-04 12:00:00', '2019-01-04 17:00:00'],
            ['2019-01-04 12:00:00', '2019-01-04 18:00:00'],
            ['2019-01-04 12:00:00', '2019-01-04 18:00:00'],
            ['2019-01-04 12:00:00', '2019-01-04 19:00:00']
        ];
    }

    /**
     * Test Data - Union
     * @return array
     */
    protected static function unionData1()
    {
        return [
            ['2019-01-04 13:00:00', '2019-01-04 15:00:00'],
            ['2019-01-04 10:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 19:00:00', '2019-01-04 22:00:00'],
            ['2019-01-04 15:00:00', '2019-01-04 18:00:00']
        ];
    }

    /**
     * Test Data - Union
     * @return array
     */
    protected static function unionData2()
    {
        return [
            ['2019-01-04 08:00:00', '2019-01-04 09:00:00'],
            ['2019-01-04 14:00:00', '2019-01-04 16:00:00'],
            ['2019-01-04 21:00:00', '2019-01-04 23:00:00']
        ];
    }

    /**
     * Expected Data - Union
     * @return array
     */
    protected static function unionExpected()
    {
        return [
            ['2019-01-04 08:00:00', '2019-01-04 09:00:00'],
            ['2019-01-04 10:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 13:00:00', '2019-01-04 18:00:00'],
            ['2019-01-04 19:00:00', '2019-01-04 23:00:00']
        ];
    }

    /**
     * Test Data - Diff
     * @return array
     */
    protected static function diffData1()
    {
        return [
            // 1
            ['2019-01-01 01:00:00', '2019-01-01 02:00:00'],
            ['2019-01-02 01:00:00', '2019-01-02 02:00:00'],
            // 2
            ['2019-01-03 01:00:00', '2019-01-03 02:00:00'],
            ['2019-01-04 01:00:00', '2019-01-04 02:00:00'],
            // 3
            ['2019-01-04 03:00:00', '2019-01-04 04:00:00'],
            ['2019-01-04 05:00:00', '2019-01-04 06:00:00'],
            // 4
            ['2019-01-04 07:00:00', '2019-01-04 08:00:00'],
            // 5
            ['2019-01-04 09:00:00', '2019-01-04 10:00:00'],
            ['2019-01-04 11:00:00', '2019-01-04 12:00:00'],
            // 6
            ['2019-01-04 13:00:00', '2019-01-04 14:00:00'],
            ['2019-01-04 15:00:00', '2019-01-04 16:00:00'],
            // Multiple processing
            ['2019-01-04 17:00:00', '2019-01-04 20:00:00'],
            // Multiple processing - cross time
            ['2019-01-04 21:00:00', '2019-01-04 21:40:00'],
            ['2019-01-04 21:20:00', '2019-01-04 22:00:00'],
            ['2019-01-04 22:30:00', '2019-01-04 23:00:00'],

        ];
    }

    /**
     * Test Data - Diff
     * @return array
     */
    protected static function diffData2()
    {
        return [
            // 1
            ['2019-01-01 00:30:00', '2019-01-01 00:59:59'],
            ['2019-01-02 00:30:00', '2019-01-02 01:00:00'],
            // 2
            ['2019-01-03 02:00:00', '2019-01-03 02:30:00'],
            ['2019-01-04 02:00:01', '2019-01-04 02:30:00'],
            // 3
            ['2019-01-04 03:00:00', '2019-01-04 04:00:00'],
            ['2019-01-04 04:50:00', '2019-01-04 06:00:01'],
            // 4
            ['2019-01-04 07:30:00', '2019-01-04 07:40:00'],
            // 5
            ['2019-01-04 09:30:00', '2019-01-04 10:00:00'],
            ['2019-01-04 11:30:00', '2019-01-04 12:00:01'],
            // 6
            ['2019-01-04 13:00:00', '2019-01-04 13:30:00'],
            ['2019-01-04 14:50:00', '2019-01-04 15:30:00'],
            // Multiple processing
            ['2019-01-04 17:30:00', '2019-01-04 18:00:00'],
            ['2019-01-04 18:30:00', '2019-01-04 19:00:00'],
            ['2019-01-04 19:30:00', '2019-01-04 20:30:00'],
            // Multiple processing - cross time
            ['2019-01-04 21:30:00', '2019-01-04 22:50:00'],
        ];
    }

    /**
     * Expected Data - Diff
     * @return array
     */
    protected static function diffExpected()
    {
        return [
            // 1
            ['2019-01-01 01:00:00', '2019-01-01 02:00:00'],
            ['2019-01-02 01:00:00', '2019-01-02 02:00:00'],
            // 2
            ['2019-01-03 01:00:00', '2019-01-03 02:00:00'],
            ['2019-01-04 01:00:00', '2019-01-04 02:00:00'],
            // 4
            ['2019-01-04 07:00:00', '2019-01-04 07:30:00'],
            ['2019-01-04 07:40:00', '2019-01-04 08:00:00'],
            // 5
            ['2019-01-04 09:00:00', '2019-01-04 09:30:00'],
            ['2019-01-04 11:00:00', '2019-01-04 11:30:00'],
            // 6
            ['2019-01-04 13:30:00', '2019-01-04 14:00:00'],
            ['2019-01-04 15:30:00', '2019-01-04 16:00:00'],
            // Multiple processing
            ['2019-01-04 17:00:00', '2019-01-04 17:30:00'],
            ['2019-01-04 18:00:00', '2019-01-04 18:30:00'],
            ['2019-01-04 19:00:00', '2019-01-04 19:30:00'],
            // Multiple processing - cross time
            ['2019-01-04 21:00:00', '2019-01-04 21:30:00'],
            ['2019-01-04 22:50:00', '2019-01-04 23:00:00'],
        ];
    }

    /**
     * Test Data - Intersect
     * @return array
     */
    protected static function intersectData1()
    {
        return [
            // 1
            ['2019-01-01 01:00:00', '2019-01-01 02:00:00'],
            ['2019-01-02 01:00:00', '2019-01-02 02:00:00'],
            // 2
            ['2019-01-03 01:00:00', '2019-01-03 02:00:00'],
            ['2019-01-04 01:00:00', '2019-01-04 02:00:00'],
            // 3
            ['2019-01-04 03:00:00', '2019-01-04 04:00:00'],
            ['2019-01-04 05:00:00', '2019-01-04 06:00:00'],
            // 4
            ['2019-01-04 07:00:00', '2019-01-04 08:00:00'],
            // 5
            ['2019-01-04 09:00:00', '2019-01-04 10:00:00'],
            ['2019-01-04 11:00:00', '2019-01-04 12:00:00'],
            // 6
            ['2019-01-04 13:00:00', '2019-01-04 14:00:00'],
            ['2019-01-04 15:00:00', '2019-01-04 16:00:00'],
            // Multiple processing
            ['2019-01-04 17:00:00', '2019-01-04 20:00:00'],
            // Multiple processing - cross time
            ['2019-01-04 21:00:00', '2019-01-04 21:40:00'],
            ['2019-01-04 21:20:00', '2019-01-04 22:00:00'],
            ['2019-01-04 22:30:00', '2019-01-04 23:00:00'],
        ];
    }

    /**
     * Test Data - Intersect
     * @return array
     */
    protected static function intersectData2()
    {
        return [
            // 1
            ['2019-01-01 00:30:00', '2019-01-01 00:59:59'],
            ['2019-01-02 00:30:00', '2019-01-02 01:00:00'],
            // 2
            ['2019-01-03 02:00:00', '2019-01-03 02:30:00'],
            ['2019-01-04 02:00:01', '2019-01-04 02:30:00'],
            // 3
            ['2019-01-04 03:00:00', '2019-01-04 04:00:00'],
            ['2019-01-04 04:50:00', '2019-01-04 06:00:01'],
            // 4
            ['2019-01-04 07:30:00', '2019-01-04 07:40:00'],
            // 5
            ['2019-01-04 09:30:00', '2019-01-04 10:00:00'],
            ['2019-01-04 11:30:00', '2019-01-04 12:00:01'],
            // 6
            ['2019-01-04 13:00:00', '2019-01-04 13:30:00'],
            ['2019-01-04 14:50:00', '2019-01-04 15:30:00'],
            // Multiple processing
            ['2019-01-04 17:30:00', '2019-01-04 18:00:00'],
            ['2019-01-04 18:30:00', '2019-01-04 19:00:00'],
            ['2019-01-04 19:30:00', '2019-01-04 20:30:00'],
            // Multiple processing - cross time
            ['2019-01-04 21:30:00', '2019-01-04 22:50:00'],
        ];
    }

    /**
     * Expected Data - Intersect
     * @return array
     */
    protected static function intersectExpected()
    {
        return [
            // 3
            ['2019-01-04 03:00:00', '2019-01-04 04:00:00'],
            ['2019-01-04 05:00:00', '2019-01-04 06:00:00'],
            // 4
            ['2019-01-04 07:30:00', '2019-01-04 07:40:00'],
            // 5
            ['2019-01-04 09:30:00', '2019-01-04 10:00:00'],
            ['2019-01-04 11:30:00', '2019-01-04 12:00:00'],
            // 6
            ['2019-01-04 13:00:00', '2019-01-04 13:30:00'],
            ['2019-01-04 15:00:00', '2019-01-04 15:30:00'],
            // Multiple processing
            ['2019-01-04 17:30:00', '2019-01-04 18:00:00'],
            ['2019-01-04 18:30:00', '2019-01-04 19:00:00'],
            ['2019-01-04 19:30:00', '2019-01-04 20:00:00'],
            // Multiple processing - cross time
            ['2019-01-04 21:30:00', '2019-01-04 22:00:00'],
            ['2019-01-04 22:30:00', '2019-01-04 22:50:00'],
        ];
    }

    /**
     * Test Data - IsOverlap
     * @return array
     */
    protected static function isOverlapData1()
    {
        return [
            // 1
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 2
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 3
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 4
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 5
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 6
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 7
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 8
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 9
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 10
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 11
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 12
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 13
            [],
        ];
    }

    /**
     * Test Data - IsOverlap
     * @return array
     */
    protected static function isOverlapData2()
    {
        return [
            // 1
            [['2019-01-04 07:00:00', '2019-01-04 08:00:00']],
            // 2
            [['2019-01-04 07:00:00', '2019-01-04 07:59:59']],
            // 3
            [['2019-01-04 12:00:00', '2019-01-04 13:00:00']],
            // 4
            [['2019-01-04 12:00:01', '2019-01-04 13:00:00']],
            // 5
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            // 6
            [['2019-01-04 07:00:00', '2019-01-04 13:00:00']],
            // 7
            [['2019-01-04 09:00:00', '2019-01-04 11:00:00']],
            // 8
            [['2019-01-04 07:00:00', '2019-01-04 10:00:00']],
            // 9
            [['2019-01-04 07:00:00', '2019-01-04 12:00:00']],
            // 10
            [['2019-01-04 08:00:00', '2019-01-04 13:00:00']],
            // 11
            [['2019-01-04 09:00:00', '2019-01-04 13:00:00']],
            // 12
            [],
            // 13
            [['2019-01-04 09:00:00', '2019-01-04 13:00:00']],
        ];
    }

    /**
     * Expected Data - IsOverlap
     * @return array
     */
    protected static function isOverlapExpected()
    {
        return [false, false, false, false, true, true, true, true, true, true, true, false, false];
    }

    /**
     * Test Data - Contact
     * @return array
     */
    protected static function contactData()
    {
        return [
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 12:00:00'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 12:00:00', '2019-01-04 13:00:00'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 12:00:00', '2019-01-04 14:00:00'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 13:00:00'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 13:00:00', '2019-01-04 14:00:00'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 13:30:00'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 13:30:00', '2019-01-04 18:00:00'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 13:30:00', '2019-01-04 22:00:00'],
            // $sDateTime > $eDateTime
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 22:00:00', '2019-01-04 13:30:00'],
        ];
    }

    /**
     * Expected Data - Contact
     * @return array
     */
    protected static function contactExpected()
    {
        return [
            [],
            [],
            [['2019-01-04 13:00:00', '2019-01-04 16:00:00']],
            [['2019-01-04 13:00:00', '2019-01-04 16:00:00']],
            [['2019-01-04 13:00:00', '2019-01-04 16:00:00']],
            [['2019-01-04 13:00:00', '2019-01-04 16:00:00']],
            [['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']],
            [['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']],
            // $sDateTime > $eDateTime
            [['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']],
        ];
    }

    /**
     * Test Data - GreaterThan
     * @return array
     */
    protected static function greaterThanData()
    {
        return [
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 13:00:00', false],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 14:00:00', false],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 16:00:00', false],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 13:00:00', true],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 14:00:00', true],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 16:00:00', true],
            // default value test
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 14:00:00'],
        ];
    }

    /**
     * Expected Data - GreaterThan
     * @return array
     */
    protected static function greaterThanExpected()
    {
        return [
            [['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']],
            [['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']],
            [['2019-01-04 17:00:00', '2019-01-04 19:00:00']],
            [['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']],
            [['2019-01-04 17:00:00', '2019-01-04 19:00:00']],
            [['2019-01-04 17:00:00', '2019-01-04 19:00:00']],
            // default value test
            [['2019-01-04 17:00:00', '2019-01-04 19:00:00']],
        ];
    }

    /**
     * Test Data - LessThan
     * @return array
     */
    protected static function lessThanData()
    {
        return [
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 13:00:00', false],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 14:00:00', false],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 16:00:00', false],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 13:00:00', true],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 14:00:00', true],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 16:00:00', true],
            // default value test
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00'], ['2019-01-04 17:00:00', '2019-01-04 19:00:00']], '2019-01-04 14:00:00'],
        ];
    }

    /**
     * Expected Data - LessThan
     * @return array
     */
    protected static function lessThanExpected()
    {
        return [
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']],
            // default value test
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
        ];
    }

    /**
     * Test Data - Fill
     * @return array
     */
    protected static function fillData()
    {
        return [
            ['2019-01-04 08:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 10:00:00', '2019-01-04 19:00:00'],
            ['2019-01-04 12:00:00', '2019-01-04 18:00:00']
        ];
    }

    /**
     * Expected Data - Fill
     * @return array
     */
    protected static function fillExpected()
    {
        return [
            ['2019-01-04 08:00:00', '2019-01-04 19:00:00'],
        ];
    }

    /**
     * Test Data - Gap
     * @return array
     */
    protected static function gapData()
    {
        return [
            ['2019-01-04 08:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 04:00:00', '2019-01-04 05:00:00'],
            ['2019-01-04 07:00:00', '2019-01-04 09:00:00'],
            ['2019-01-04 13:00:00', '2019-01-04 18:00:00']
        ];
    }

    /**
     * Expected Data - Gap
     * @return array
     */
    protected static function gapExpected()
    {
        return [
            ['2019-01-04 05:00:00', '2019-01-04 07:00:00'],
            ['2019-01-04 12:00:00', '2019-01-04 13:00:00'],
        ];
    }

    /**
     * Test Data - Time
     * @return array
     */
    protected static function timeData()
    {
        return [
            ['2019-01-04 08:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 04:00:00', '2019-01-04 05:00:00'],
            ['2019-01-04 07:00:00', '2019-01-04 09:00:00'],
            ['2019-01-04 13:00:00', '2019-01-04 18:00:25']
        ];
    }

    /**
     * Expected Data - Time
     * @return array
     */
    protected static function timeExpected()
    {
        return 39625;
    }

    /**
     * Test Data - Cut
     * @return array
     */
    protected static function cutData()
    {
        return [
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']], '30', 'second'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']], '30', 'minute'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']], '5', 'hour'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']], '30', 'hour'],

            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 16:00']], '30', 'second'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 16:00']], '30', 'minute'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 16:00']], '30', 'hour'],
            // Test auto sort out by  union()
            [[['2019-01-04 09:00:00', '2019-01-04 10:00:00'], ['2019-01-04 08:00:00', '2019-01-04 09:00:00'], ['2019-01-04 09:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']], '30', 'hour'],
        ];
    }

    /**
     * Expected Data - Cut
     * @return array
     */
    protected static function cutExpected()
    {
        return [
            [['2019-01-04 08:00:00', '2019-01-04 08:00:30']],
            [['2019-01-04 08:00:00', '2019-01-04 08:30:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 14:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']],

            [],
            [['2019-01-04 08:00', '2019-01-04 08:30']],
            [['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 16:00']],
            // Test auto sort out by  union()
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']],
        ];
    }

    /**
     * Test Data - Cut no sortout
     * @return array
     */
    protected static function cutNotSortData()
    {
        return [
            // Test auto sort out by  union()
            [[['2019-01-04 09:00:00', '2019-01-04 10:00:00'], ['2019-01-04 08:00:00', '2019-01-04 09:00:00'], ['2019-01-04 09:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']], '30', 'hour'],
        ];
    }

    /**
     * Expected Data - Cut no sortout
     * @return array
     */
    protected static function cutNotSortExpected()
    {
        return [
            // Test auto sort out by  union()
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 16:00:00']],
        ];
    }

    /**
     * Test Data - Extend
     * @return array
     */
    protected static function extendData()
    {
        return [
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00']], '30', 0, 'second'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00']], '30', 0, 'minute'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00']], '2', 0, 'hour'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00']], '30', 40, 'second'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00']], '30', 40, 'minute'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00']], '2', 1, 'hour'],

            [[['2019-01-04 08:00', '2019-01-04 12:00']], '30', 0, 'second'],
            [[['2019-01-04 08:00', '2019-01-04 12:00']], '30', 0, 'minute'],
            [[['2019-01-04 08:00', '2019-01-04 12:00']], '2', 0, 'hour'],
            [[['2019-01-04 08:00', '2019-01-04 12:00']], '30', 40, 'second'],
            [[['2019-01-04 08:00', '2019-01-04 12:00']], '30', 40, 'minute'],
            [[['2019-01-04 08:00', '2019-01-04 12:00']], '2', 1, 'hour'],
            // Test auto sort out by  union()
            [[['2019-01-04 09:00:00', '2019-01-04 10:00:00'], ['2019-01-04 08:00:00', '2019-01-04 10:00:00'], ['2019-01-04 09:00:00', '2019-01-04 12:00:00']], '30', 0, 'minute'],
        ];
    }

    /**
     * Expected Data - Extend
     * @return array
     */
    protected static function extendExpected()
    {
        return [
            [['2019-01-04 08:00:00', '2019-01-04 12:00:30']],
            [['2019-01-04 08:00:00', '2019-01-04 12:30:00']],
            [['2019-01-04 08:00:00', '2019-01-04 14:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 12:00:40', '2019-01-04 12:01:10']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 12:40:00', '2019-01-04 13:10:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']],

            [['2019-01-04 08:00', '2019-01-04 12:00']],
            [['2019-01-04 08:00', '2019-01-04 12:30']],
            [['2019-01-04 08:00', '2019-01-04 14:00']],
            [['2019-01-04 08:00', '2019-01-04 12:00']],
            [['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 12:40', '2019-01-04 13:10']],
            [['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']],
            // Test auto sort out by  union()
            [['2019-01-04 08:00:00', '2019-01-04 12:30:00']],
        ];
    }

    /**
     * Test Data - Extend
     * @return array
     */
    protected static function extendNotSortData()
    {
        return [
            // Test auto sort out by  union()
            [[['2019-01-04 09:00:00', '2019-01-04 10:00:00'], ['2019-01-04 08:00:00', '2019-01-04 10:00:00'], ['2019-01-04 09:00:00', '2019-01-04 12:00:00']], '30', 0, 'minute'],
        ];
    }

    /**
     * Expected Data - Extend
     * @return array
     */
    protected static function extendNotSortExpected()
    {
        return [
            // Test auto sort out by  union()
            [['2019-01-04 08:00:00', '2019-01-04 12:30:00']],
        ];
    }

    /**
     * Test Data - Shorten
     * @return array
     */
    protected static function shortenData()
    {
        return [
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '30', true, 'second'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '30', true, 'minute'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '1', true, 'hour'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '2', true, 'hour'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '5', true, 'hour'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '10', true, 'hour'],

            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '30', false, 'second'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '30', false, 'minute'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '1', false, 'hour'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '2', false, 'hour'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '5', false, 'hour'],
            [[['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '10', false, 'hour'],

            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '30', true, 'second'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '30', true, 'minute'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '1', true, 'hour'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '2', true, 'hour'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '5', true, 'hour'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '10', true, 'hour'],

            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '30', false, 'second'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '30', false, 'minute'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '1', false, 'hour'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '2', false, 'hour'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '5', false, 'hour'],
            [[['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 15:00']], '10', false, 'hour'],
            // Test auto sort out by  union()
            [[['2019-01-04 09:00:00', '2019-01-04 12:00:00'], ['2019-01-04 08:00:00', '2019-01-04 10:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '5', true, 'hour'],
        ];
    }

    /**
     * Expected Data - Shorten
     * @return array
     */
    protected static function shortenExpected()
    {
        return [
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 14:59:30']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 14:30:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 14:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 09:00:00']],
            [],

            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 14:59:30']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 14:30:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00'], ['2019-01-04 13:00:00', '2019-01-04 14:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00']],

            [['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 14:59']],
            [['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 14:30']],
            [['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 14:00']],
            [['2019-01-04 08:00', '2019-01-04 12:00']],
            [['2019-01-04 08:00', '2019-01-04 09:00']],
            [],

            [['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 14:59']],
            [['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 14:30']],
            [['2019-01-04 08:00', '2019-01-04 12:00'], ['2019-01-04 13:00', '2019-01-04 14:00']],
            [['2019-01-04 08:00', '2019-01-04 12:00']],
            [['2019-01-04 08:00', '2019-01-04 12:00']],
            [['2019-01-04 08:00', '2019-01-04 12:00']],
            // Test auto sort out by  union()
            [['2019-01-04 08:00:00', '2019-01-04 09:00:00']],
        ];
    }

    /**
     * Test Data - Shorten
     * @return array
     */
    protected static function shortenNotSortData()
    {
        return [
            // Test auto sort out by  union()
            [[['2019-01-04 09:00:00', '2019-01-04 12:00:00'], ['2019-01-04 08:00:00', '2019-01-04 10:00:00'], ['2019-01-04 13:00:00', '2019-01-04 15:00:00']], '5', true, 'hour'],
        ];
    }

    /**
     * Expected Data - Shorten
     * @return array
     */
    protected static function shortenNotSortExpected()
    {
        return [
            // Test auto sort out by  union()
            [['2019-01-04 08:00:00', '2019-01-04 09:00:00']],
        ];
    }

    /**
     * Test Data - Format
     * @return array
     */
    protected static function formatData()
    {
        return [
            ['2019-01-04 08:11:11', '2019-01-04 12:22:22'],
            ['2019-01-04 04:33:33', '2019-01-04 05:44:44'],
            ['2019-01-04 05:55', '2019-01-04 06:55'],
            ['2019-01-04 07', '2019-01-04 08'],
        ];
    }

    /**
     * Expected Data - Format
     * @return array
     */
    protected static function formatExpectedS()
    {
        return [
            ['2019-01-04 08:11:11', '2019-01-04 12:22:22'],
            ['2019-01-04 04:33:33', '2019-01-04 05:44:44'],
            ['2019-01-04 05:55:00', '2019-01-04 06:55:00'],
            ['2019-01-04 07:00:00', '2019-01-04 08:00:00'],
        ];
    }

    /**
     * Expected Data - Format
     * @return array
     */
    protected static function formatExpectedM()
    {
        return [
            ['2019-01-04 08:11:00', '2019-01-04 12:22:00'],
            ['2019-01-04 04:33:00', '2019-01-04 05:44:00'],
            ['2019-01-04 05:55:00', '2019-01-04 06:55:00'],
            ['2019-01-04 07:00:00', '2019-01-04 08:00:00'],
        ];
    }

    /**
     * Expected Data - Format
     * @return array
     */
    protected static function formatExpectedH()
    {
        return [
            ['2019-01-04 08:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 04:00:00', '2019-01-04 05:00:00'],
            ['2019-01-04 05:00:00', '2019-01-04 06:00:00'],
            ['2019-01-04 07:00:00', '2019-01-04 08:00:00'],
        ];
    }

    /**
     * Test Data - Validate
     * @return array
     */
    protected static function validateData()
    {
        return [
            // pass
            [['2019-01-04 02:00:00', '2019-01-04 03:00:00']],
            // target not array
            'string',
            // // content not array
            ['string'],
            // size error
            [['2019-01-04 08:00:00', '2019-01-04 12:00:00', '2019-01-04 12:00:00']],
            // size error
            [['2019-01-04 04:00:00']],
            // time format error
            [['2019-01-04 04:00', '2019-01-04 05:00:00']],
            // sort error
            [['2019-01-04 08:00:00', '2019-01-04 05:00:00']],
            // error the same time
            [['2019-01-04 19:00:00', '2019-01-04 19:00:00']],
        ];
    }

    /**
     * Expected Data - FormValidateat
     * @return array
     */
    protected static function validateExpected()
    {
        return [
            // pass
            true,
            // target not array
            false,
            // content not array
            false,
            // size error
            false,
            // size error
            false,
            // time format error
            false,
            // sort error
            false,
            // error the same time
            false,
        ];
    }

    /**
     * Test Data - Filter
     * @return array
     */
    protected static function filterData1()
    {
        return [
            ['2019-01-04 08:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 04:00:00', '2019-01-04 05:00:00'],
            ['2019-01-04 24:10:10', '2019-01-05 24:22:22'],
        ];
    }

    /**
     * Test Data - Filter
     * @return array
     */
    protected static function filterData2()
    {
        return [
            ['2019-01-04 02:00:00', '2019-01-04 03:00:00'],
            ['2019-01-04 08:00:00', '2019-01-04 12:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 04:00:00'],
            ['2019-01-04 04:00', '2019-01-04 05:00:00'],
            'string',
            ['2019-01-04 08:00:00', '2019-01-04 05:00:00'],
            ['2019-01-04 19:00:00', '2019-01-04 19:00:00'],
        ];
    }

    /**
     * Expected Data - Filter
     * @return array
     */
    protected static function filterExpected1()
    {
        return [
            ['2019-01-04 08:00:00', '2019-01-04 12:00:00'],
            ['2019-01-04 04:00:00', '2019-01-04 05:00:00'],
            ['2019-01-05 00:10:10', '2019-01-06 00:22:22'],
        ];
    }

    /**
     * Expected Data - Filter
     * @return array
     */
    protected static function filterExpected2()
    {
        return [
            ['2019-01-04 02:00:00', '2019-01-04 03:00:00'],
        ];
    }

    /**
     * Test Data - IsDatetime
     * @return array
     */
    protected static function isDatetimeData()
    {
        return [
            ['2019-01-04 08:00:00'],
            ['2019-01-04 88:88:88'],
            ['2019-01-04 08:00'],
        ];
    }

    /**
     * Expected Data - IsDatetime
     * @return array
     */
    protected static function isDatetimeExpected()
    {
        return [
            true,
            true,
            false,
        ];
    }

    /**
     * Test Data - TimeFormatConv
     * @return array
     */
    protected static function timeFormatConvData()
    {
        return [
            ['2019-01-04 08:33:33'],
            ['2019-01-04 08:33:33', 'default'],
            ['2019-01-04 08:33:33', 'second'],
            ['2019-01-04 08:33:33', 'minute'],
            ['2019-01-04 08:33:33', 'hour'],
            // fill
            ['2019-01-04'],
            ['2019-01-04 08'],
            ['2019-01-04 08:11:1', 'default'],
            ['2019-01-04 08:2', 'default'],
            ['2019-01-04 08', 'default'],
            ['2019-01-04 08', 'second'],
            ['2019-01-04 08', 'minute'],
            ['2019-01-04 08', 'hour'],
        ];
    }

    /**
     * Expected Data - TimeFormatConv
     * @return array
     */
    protected static function timeFormatConvExpected()
    {
        return [
            '2019-01-04 08:33:33',
            '2019-01-04 08:33:33',
            '2019-01-04 08:33:33',
            '2019-01-04 08:33:00',
            '2019-01-04 08:00:00',
            // fill
            '2019-01-04 00:00:00',
            '2019-01-04 08:00:00',
            '2019-01-04 08:11:10',
            '2019-01-04 08:20:00',
            '2019-01-04 08:00:00',
            '2019-01-04 08:00:00',
            '2019-01-04 08:00:00',
            '2019-01-04 08:00:00',
        ];
    }

    /**
     * Test Data - Time2Second
     * @return array
     */
    protected static function time2SecondData()
    {
        return [
            [30],
            [30, 'default'],
            [30, 'second'],
            [30, 'minute'],
            [30, 'hour'],
        ];
    }

    /**
     * Expected Data - Time2Second
     * @return array
     */
    protected static function time2SecondExpected()
    {
        return [
            30,
            30,
            30,
            1800,
            108000,
        ];
    }
}

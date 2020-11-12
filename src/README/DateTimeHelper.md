# DateTimeHelper

- [DateTimeHelper](#datetimehelper)
  - [Methods](#methods)
    - [getDates](#getdates)
    - [shiftMonths](#shiftmonths)
    - [dateIterator](#dateiterator)
    - [dateWeekday](#dateweekday)
  
## Methods

### getDates

取得開始到結束每天日期

- Description
  - getDates( string `$start`, string `$end`[, string `$format` ] ) : array
- Parameters
  - $start (*string*) – 起日
  - $end (*string*) – 迄日
  - $format (*string*) – 預設是 **Y-m-d** 日期格式
- Returns
  - *array* - 範圍日期陣列
- Example

  ```php
  use \nueip\helpers\DateTimeHelper;

  $start = '2020-11-4';
  $end = '2020-11-10';
  $format = "Y-m-d H:i:s";
  $show = DateTimeHelper::getDates($start, $end, $format);
  var_export($show);
  ```

- Result
  
  ```php
  array (
    0 => '2020-11-04 00:00:00',
    1 => '2020-11-05 00:00:00',
    2 => '2020-11-06 00:00:00',
    3 => '2020-11-07 00:00:00',
    4 => '2020-11-08 00:00:00',
    5 => '2020-11-09 00:00:00',
    6 => '2020-11-10 00:00:00',
  )
  ```

### shiftMonths

取得經過幾個月的日期

- Description
  - shiftMonths( string `$date`[, string `$operation`[, integer `$months`[, string `$outputFormat` ]]] ) : mixed
- Parameters
  - $date (*string*) – 計算日期
  - $operation (*string*) – 預設是 **+** 可用參數 + | -
  - $months (*integer*) – 預設是 **1** 多少個月
  - $outputFormat (*string*) – 預設是 **Y-m-d** 日期格式
- Returns
  - *string|integer* - 目標日期
- Example
  
  ```php
  use \nueip\helpers\DateTimeHelper;

  $show = DateTimeHelper::shiftMonths('2020-11-04');
  var_export($show);
  $show = DateTimeHelper::shiftMonths('2020-11-04', '-', 3);
  var_export($show);
  ```

- Result
  
  ```php
  '2020-12-04'
  '2020-08-04'
  ```

### dateIterator

回傳一個DatePeriod的物件

- Description
  - dateIterator( date `$start`, date `$end`) : DateTime
- Parameters
  - $start (*date*) – 起日
  - $end (*date*) – 迄日
- Returns
  - *DateTime* - DatePeriod的物件

### dateWeekday

取得日期為星期幾

- Description
  - dateWeekday( date `$date`) : integer
- Parameters
  - $date (*date*) – 計算日期
- Returns
  - *integer* - 星期幾
- 換算
  - 一(1)、二(2)、三(3)、四(4)、五(5)、六(6)、日(7)
- Example
  
  ```php
  $show = \nueip\helpers\DateTimeHelper::dateWeekday('2020-11-04');
  var_export($show);
  ```

- Result
  
  ```php
  '3'
  ```

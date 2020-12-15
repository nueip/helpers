# ArrayHelper

- [ArrayHelper](#arrayhelper)
  - [Methods](#methods)
    - [範例Data](#範例data)
    - [indexBy](#indexby)
    - [indexOnly](#indexonly)
    - [getContent](#getcontent)
    - [範例Data 索引為數字](#範例data-索引為數字)
    - [getClosest](#getclosest)
    - [getClosestLess](#getclosestless)
    - [getClosestMore](#getclosestmore)
    - [gatherData](#gatherdata)
    - [範例Data 不同陣列值重複](#範例data-不同陣列值重複)
    - [groupBy](#groupby)
    - [filterKey](#filterkey)
    - [範例Data 新舊資料比較](#範例data-新舊資料比較)
    - [diffRecursive](#diffrecursive)
    - [範例Data 排序](#範例data-排序)
    - [sortRecursive](#sortrecursive)

## Methods

### 範例Data

```php
 $data = [
            'user' => ['name' => 'Mars', 'birthday' => '2000-01-01'],
            '0' => ['name' => 'Mars2', 'birthday' => '2000-02-02'],
            '1' => ['name' => 'Mars3', 'birthday' => '2000-03-03'],
        ];
```

### indexBy

取得要搜尋的key，搜尋array中的值，將array的key替換成索引

- Description
  - indexBy( array `$data`, mixed `$keys`[, bool `$obj2array` ] ) : array
- Parameters
  - $data (*array*) – 需要查詢的陣列
  - $keys (*Array/string*) – 找尋陣列的欄位名
  - $obj2array (*bool*) – 預設是 **false** 是否轉成陣列
- Returns
  - *array* - 目標陣列
- Example 一個Key
  
  ```php
  use use \nueip\helpers\ArrayHelper;

  $show = ArrayHelper::indexBy($data,'name');
  var_export($show);
  ```

- Result
  
  ```php
  array (
    'Mars' =>
    array (
      'name' => 'Mars',
      'birthday' => '2000-01-01',
    ),
    'Mars2' =>
    array (
      'name' => 'Mars2',
      'birthday' => '2000-02-02',
    ),
    'Mars3' =>
    array (
      'name' => 'Mars3',
      'birthday' => '2000-03-03',
    ),
  )
  ```

- Example 多個Key

  ```php
  use \nueip\helpers\ArrayHelper;

  $key = ['birthday','name'];
  $show = ArrayHelper::indexBy($data, $key);
  var_export($show);
  ```

- Result
  
  ```php
  array (
    '2000-01-01' =>
    array (
      'Mars' =>
      array (
        'name' => 'Mars',
        'birthday' => '2000-01-01',
      ),
    ),
    '2000-02-02' =>
    array (
      'Mars2' =>
      array (
        'name' => 'Mars2',
        'birthday' => '2000-02-02',
      ),
    ),
    '2000-03-03' =>
    array (
      'Mars3' =>
      array (
        'name' => 'Mars3',
        'birthday' => '2000-03-03',
      ),
    ),
  )
  ```

### indexOnly

與indexBy()相似，但是只有返回Key

- Description
  - indexOnly( array `$data`, mixed `$keys`[, bool `$obj2array` ] ) : array
- Parameters
  - $data (*array*) – 需要查詢的陣列
  - $keys (*Array/string*) – 找尋陣列的欄位名
  - $obj2array (*bool*) – 預設是 **false** 是否轉成陣列
- Returns
  - *array* - 目標陣列的key
- Example
  
  ```php
  use \nueip\helpers\ArrayHelper;

  $show = ArrayHelper::indexOnly($data, 'name');
  var_export($show);
  ```

- Result
  
  ```php
  array (
    'Mars' => '',
    'Mars2' => '',
    'Mars3' => '',
  )
  ```

### getContent

按照索引，取得索引下的所有值

- Description
  - getContent( array `$data`[, mixed `$indexTo` ] ) : array
- Parameters
  - $data (*array*) – 需要查詢的陣列
  - $indexTo (*array|string*) – 預設是 **[ ]** 找尋陣列的欄位名
- Returns
  - *array* - 目標陣列
- Example 一個索引
  
  ```php
  use \nueip\helpers\ArrayHelper;

  $show = ArrayHelper::getContent($data, 'user');
  var_export($show);
  ```

- Result
  
  ```php
  array (
    'name' => 'Mars',
    'birthday' => '2000-01-01',
  )
  ```

- Example 索引陣列

  ```php
  use \nueip\helpers\ArrayHelper;

  $key = ['user', 'name'];
  $show = ArrayHelper::getContent($data, $key);
  var_export($show);
  ```
  
- Example
  
  ```php
  'Mars'
  ```

### 範例Data 索引為數字

  ```php
  $data = [
              '0' => ['name' => 'Mars', 'birthday' => '2000-01-01'],
              '1' => ['name' => 'Mars2', 'birthday' => '2000-02-02'],
              '2' => ['name' => 'Mars3', 'birthday' => '2000-03-03'],
          ];
  ```

### getClosest

比較最接近 `$needle` 的索引是哪一個，如果尋找的索引比`$needle`大或者小，要使用參數`$compareWith`

- Description
  - getClosest( array `$data`, mixed `$needle`[, string `$compareWith`[, callback `$formatKey` ]] ) : mixed
- Parameters
  - $data (*array*) – 需要查詢的陣列
  - $needle (*string|integer*) – 搜尋最接近的數值
  - $compareWith (*string*) – 預設是 **closest** 可用參數 closest | more | less
  - $formatKey (*callback*) – 預設是 **strtotime** 被呼叫的函式
- Returns
  - *mixed* - 最符合數值的目標陣列
- $formatKey 運作講解

  如果\$needle帶入不是數字，藉由`$formatKey`轉成數字，`$formatKey`預設是strtotime()函數，將`$needle`傳參數到strtotime(`$needle`)返回數字
  
  ```php
  $needle = 'now';
  $formatKey = 'strtotime';
  function strtotime ($needle)
  {
      return 數字;
  }
  ```

- Example 取得最接近陣列
  
  ```php
  use \nueip\helpers\ArrayHelper;

  $show = ArrayHelper::getClosest($data,1.45);
  var_export($show);
  
  $show = ArrayHelper::getClosest($data,1.55);
  var_export($show);
  ```
  
- Result
  
  ```php
  array (
    'name' => 'Mars2',
    'birthday' => '2000-02-02',
  )
  array (
    'name' => 'Mars3',
    'birthday' => '2000-03-03',
  )
  ```

- Example 取得大於的陣列
  
  ```php
  use \nueip\helpers\ArrayHelper;

  $show = ArrayHelper::getClosest($data, 1, 'more');
  var_export($show);

  $show = ArrayHelper::getClosest($data, 1.5, 'more');
  var_export($show);
  ```
  
- Result
  
  ```php
  array (
    'name' => 'Mars2',
    'birthday' => '2000-02-02',
  )
  array (
    'name' => 'Mars3',
    'birthday' => '2000-03-03',
  )

  ```

### getClosestLess

同理getClosest()帶入less

- Description
  - getClosestLess( array `$data`, mixed `$needle`) : mixed
- Parameters
  - $data (*array*) – 需要查詢的陣列
  - $needle (*string|integer*) – 搜尋最接近的數值
- Returns
  - *mixed* - 最符合數值的目標陣列

### getClosestMore

同理getClosest()帶入more

- Description
  - getClosestMore( array `$data`, mixed `$needle`) : mixed
- Parameters
  - $data (*array*) – 需要查詢的陣列
  - $needle (*string|integer*) – 搜尋最接近的數值
- Returns
  - *mixed* - 最符合數值的目標陣列

### gatherData

取得陣列中有指定的key

- Description
  - gatherData( array `$data`, array `$colNameList`[, array `$objLv` [, array `$dataList` ]] ) : array
- Parameters
  - $data (*array*) – 需要查詢的陣列
  - $colNameList (*array*) – 資料陣列中，目標資料的Key名稱
  - $objLv (*array*) – 預設是 **1** 資料物件所在層數，可以指定要取到第幾層的資料
  - $dataList (*array*) – 預設是 **[ ]** 遞迴用
- Returns
  - *array* - 目標陣列

- Example 取得同一層的Key資料
  
  ```php
  use \nueip\helpers\ArrayHelper;

  $key = ['name','birthday'];
  $show = ArrayHelper::gatherData($data, $key);
  var_export($show);
  ```

- Result
  
  ```php
  array (
    'Mars' => 'Mars',
    '2000-01-01' => '2000-01-01',
    'Mars2' => 'Mars2',
    '2000-02-02' => '2000-02-02',
    'Mars3' => 'Mars3',
    '2000-03-03' => '2000-03-03',
  )
  ```

- Example 分類取的的Key資料
  
  ```php
  use \nueip\helpers\ArrayHelper;

  $key = ['name' => ['name'],'other' => ['birthday']];
  $show = ArrayHelper::gatherData($data, $key,1);
  var_export($show);
  ```

- Result
  
  ```php
  array (
    'name' =>
    array (
      'Mars' => 'Mars',
      'Mars2' => 'Mars2',
      'Mars3' => 'Mars3',
    ),
    'other' =>
    array (
      '2000-01-01' => '2000-01-01',
      '2000-02-02' => '2000-02-02',
      '2000-03-03' => '2000-03-03',
    ),
  )
  ```

### 範例Data 不同陣列值重複

```php
$data = [
            '0' => ['name' => 'Mars', 'birthday' => '2000-01-01', 'pet' => 'cat'],
            '1' => ['name' => 'Mars2', 'birthday' => '2000-02-02', 'pet' => 'cat'],
            '2' => ['name' => 'Mars3', 'birthday' => '2000-03-03', 'pet' => 'bird'],
        ];
```

### groupBy

將同一個Key下有相同的值組合在一個陣列

- Description
  - groupBy( array `$data`, mixed `$keys`[, bool `$obj2array` ] ) : array
- Parameters
  - $data (*array*) – 需要查詢的陣列
  - $keys (*Array/string*) – 找尋陣列的欄位名
  - $obj2array (*bool*) – 預設是 **false** 是否轉成陣列
- Returns
  - *array* - 重複的值組成目標陣列
- Example

  ```php
  use \nueip\helpers\ArrayHelper;

  $show = ArrayHelper::groupBy($data, 'pet');
  var_export($show);
  ```

- Result
  
  ```php
  array (
    'cat' =>
    array (
      0 =>
      array (
        'name' => 'Mars',
        'birthday' => '2000-01-01',
        'pet' => 'cat',
      ),
      1 =>
      array (
        'name' => 'Mars2',
        'birthday' => '2000-02-02',
        'pet' => 'cat',
      ),
    ),
    'bird' =>
    array (
      0 =>
      array (
        'name' => 'Mars3',
        'birthday' => '2000-03-03',
        'pet' => 'bird',
      ),
    ),
  )
  ```

### filterKey

過濾有相同的key下的值

- Description
  - filterKey( array `$data`, mixed `$keys`) : array
- Parameters
  - $data (*array*) – 需要查詢的陣列
  - $keys (*Array/string*) – 找尋陣列的欄位名
- Returns
  - *array* - 過濾後組成目標陣列
- Example 
  
  ```php
  use \nueip\helpers\ArrayHelper;

  $show = ArrayHelper::filterKey($data, 'name,birthday');
  var_export($show);
  ```

- Result
  
  ```php
  array (
    0 =>
    array (
      'name' => 'Mars',
      'birthday' => '2000-01-01',
    ),
    1 =>
    array (
      'name' => 'Mars2',
      'birthday' => '2000-02-02',
    ),
    2 =>
    array (
      'name' => 'Mars3',
      'birthday' => '2000-03-03',
    ),
  )
  ```

### 範例Data 新舊資料比較

```php
$data = [
            '0' => ['name' => 'Mars', 'birthday' => '2000-01-01', 'pet' => 'dog'],
            '1' => ['name' => 'Mars2', 'birthday' => '2000-02-02', 'pet' => 'cat'],
            '2' => ['name' => 'Mars3', 'birthday' => '2000-03-03', 'pet' => 'bird'],
        ];

$data2 = [
            '0' => ['name' => 'Mars', 'birthday' => '2000-01-01', 'pet' => 'cat'],
            '1' => ['name' => 'Mars2', 'birthday' => '2000-02-01', 'pet' => 'cat'],
            '2' => ['name' => 'Mars3', 'birthday' => '2000-03-03', 'pet' => 'bird'],
        ];
```

### diffRecursive

前陣列差集後陣列

- Description
  - diffRecursive( array `$Comparative`, array `$Comparison`) : array
- Parameters
  - $Comparative (*array*) – 被比較陣列
  - $Comparison (*array*) – 比較陣列
- Returns
  - *array* - 比較兩陣列中的不同，被比較陣列所組合的目標陣列
- Example

  ```php
  use \nueip\helpers\ArrayHelper;

  $show = ArrayHelper::diffRecursive($data, $data2);
  var_export($show);
  ```

- Result
  
  ```php
  array (
    0 =>
    array (
      'pet' => 'dog',
    ),
    1 =>
    array (
      'birthday' => '2000-02-02',
    ),
  )
  ```

### 範例Data 排序

```php
$data = ['0' => ['5','-1','22'],'9','8','5','4'];
```

### sortRecursive

排序陣列，包含陣列裡的陣列，陣列為傳參考方式取得

- Description
  - sortRecursive( array &`$srcArray`[, string `$type` ] ) : array
- Parameters
  - $srcArray (*array*) – 需要排序的陣列
  - $type (*string*) – 預設是 **ksort** 可用參數 ksort | krsort | sort | rsort
- Returns
  - *array* - 已排序的陣列
- Example

  ```php

  use use \nueip\helpers\ArrayHelper;

  $show = ArrayHelper::sortRecursive($data);
  var_export($data);
  ```

- Result
  
  ```php
  array (
    0 => 
    array (
      0 => '5',
      1 => '-1',
      2 => '22',
    ),
    1 => '9',
    2 => '8',
    3 => '5',
    4 => '4',
  )
  ```

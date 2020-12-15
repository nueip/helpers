# SqlHelper

- [SqlHelper](#sqlhelper)
  - [Methods](#methods)
    - [whereInChunk](#whereinchunk)
    - [notWhereInChunk](#notwhereinchunk)
    - [timeIntersect](#timeintersect)
    - [timeAfter](#timeafter)
    - [whereFilters](#wherefilters)

## Methods

### whereInChunk

帶入其他表取得的條件資料，加入查詢條件

- Description
  - whereInChunk( string `$columnName`, array `$snList`[, DB_query_builder `$queryBuilder`[, integer `$size` ]] ) : DB_query_builder
- Parameters
  - $columnName (*string*) – 欄位名稱
  - $snList (*array*) – 資料陣列
  - `$queryBuilder` (*`DB_query_builder`*) – 預設是 **null** 代表$this->db
  - $size (*integer*) – 預設是 **300** 每次處理大小
- Returns
  - *DB_query_builder* - 目標SQL指令
- Example

    ```php
    use \nueip\helpers\SqlHelper;

    $snList = ['wang','hsieh','chou'];
    $show = SqlHelper::whereInChunk('no',$snList)
    ->select('no,name')
    ->from('account')
    ->get_compiled_select();
    echo $show;
    ```

- Result
  
    ```sql
    SELECT `no`, `name` FROM `account` WHERE ( `no` IN('wang', 'hsieh', 'chou') )
    ```

### notWhereInChunk

帶入其他表取得的條件資料，加入查詢條件，找出非在`$snList`中的資料

- Description
  - notWhereInChunk( string `$columnName`, array `$snList`[, DB_query_builder `$queryBuilder`[, integer `$size` ]] ) : DB_query_builder
- Parameters
  - $columnName (*string*) – 欄位名稱
  - $snList (*array*) – 資料陣列
  - `$queryBuilder` (*`DB_query_builder`*) – 預設是 **null** 代表$this->db
  - $size (*integer*) – 預設是 **300** 每次處理大小
- Returns
  - *DB_query_builder* - 目標SQL指令
- Example
  
    ```php
    use \nueip\helpers\SqlHelper;

    $snList = ['wang','hsieh','chou'];
    $show = SqlHelper::whereInChunk('no',$snList)
    ->select('no,name')
    ->from('account')
    ->get_compiled_select();
    echo $show;
    ```

- Result
  
    ```sql
    SELECT `no`, `name` FROM `account` WHERE NOT ( `no` IN('wang', 'hsieh', 'chou') )
    ```

### timeIntersect

找尋條件時間內的資料

- Description
  - timeIntersect( string `$sCol`, string `$eCol`, string `$sDate`, string `$eDate`[, DB_query_builder `$queryBuilder` ] ) : DB_query_builder
- Parameters
  - $sCol (*string*) – 起日欄位名
  - $eCol (*string*) – 訖日欄位名
  - $sDate (*string*) – 起日
  - $eDate (*string*) – 訖日
  - `$queryBuilder` (*`DB_query_builder`*) – 預設是 **null** 代表$this->db
- Returns
  - *DB_query_builder* - 目標SQL指令
- Example
  
    ```php
    use \nueip\helpers\SqlHelper;

    $show = SqlHelper::timeIntersect('start_date', 'end_date', '2020-11-05', '2020-11-06')
    ->select('no,name')
    ->from('account')
    ->get_compiled_select();
    echo $show;
    ```

- Result

    ```sql
    SELECT `no`, `name` FROM `account` WHERE NOT ( `start_date` > '2020-11-06' OR ( `end_date` < '2020-11-05' AND `end_date` != '0000-00-00' ) )
    ```

### timeAfter

處理時間小於等於特定欄位 且不為 0000-00-00

- Description
  - timeAfter( string `$columnName`, string `$date`[, DB_query_builder `$queryBuilder` ] ) : DB_query_builder
- Parameters
  - $columnName (*string*) – 欄位名
  - $date (*string*) – 日期
  - `$queryBuilder` (*`DB_query_builder`*) – 預設是 **null** 代表$this->db
- Returns
  - *DB_query_builder* - 目標SQL指令
- Example

  ```php
  use \nueip\helpers\SqlHelper;

  $show = SqlHelper::timeAfter('date', '2020-11-05')
  ->select('no,name')
  ->from('account')
  ->get_compiled_select();
  echo $show;
  ```

- Result
  
  ```sql
  SELECT `no`, `name` FROM `account` WHERE ( `date` >= '2020-11-05' OR `date` = '0000-00-00' )
  ```

### whereFilters

協助處理篩選條件，過濾多項條件

- Description
  - whereFilters(array `filters`[, DB_query_builder `queryBuilder` ] ) : DB_query_builder
- Parameters
  - $filters (*array*) – 過濾條件陣列
  - `$queryBuilder` (*`DB_query_builder`*) – 預設是 **null** 代表$this->db
- Returns
  - *DB_query_builder* - 目標SQL指令
- Example
  
  ```php
  use \nueip\helpers\SqlHelper;

  $show = SqlHelper::whereFilters([
    'category_id' => 123,
    'item_type' => [
          'A', 'B', 'C'
      ],
      'item_name' => [
          // none, before, after, both
          'like_mode' => 'both',
          'like_value' => 'test',
      ],
      'created_at' => [
          'start' => '2000-01-01',
          'end' => '2000-01-31'
      ],
  ])
  ->select('no,name')
  ->from('account')
  ->get_compiled_select();
  echo $show;
  ```

- Result
  
  ```sql
  SELECT `no`, `name` FROM `account` WHERE `category_id` = 123 AND ( `item_type` IN('A', 'B', 'C') ) AND `item_name` LIKE '%test%' ESCAPE '!' AND `created_at` >= '2000-01-01' AND `created_at` <= '2000-01-31'
  ```
  
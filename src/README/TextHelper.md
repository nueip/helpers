# TextHelper

- [TextHelper](#texthelper)
  - [Methods](#methods)
    - [convFullBIG5](#convfullbig5)
    - [half2full(`$str`, `$types`, `$kinds`)](#half2fullstr-types-kinds)
    - [br2nl](#br2nl)
    - [trimNonPrintChar](#trimnonprintchar)
    - [strWidthLimit](#strwidthlimit)
    - [strLineWidthRepair](#strlinewidthrepair)
    - [strLineWidthRefactor(`$str`, `$widthLimit`, `$lineLimit`, `$trimmarker`)](#strlinewidthrefactorstr-widthlimit-linelimit-trimmarker)

## Methods

### convFullBIG5

將輸入字串轉換成BIG5全形文字

- Description
  - convFullBIG5( string `$strs`) : string
- Parameters
  - $strs (*string*) – 待轉換文字
- Returns
  - *string* - BIG5全形文字

### half2full(`$str`, `$types`, `$kinds`)

將輸入字串轉換成全形或半形文字

- Description
  - half2full( string `$str`[, string `$types`[, array `$kinds` ]] ) : string
- Parameters
  - $strs (*string*) – 待轉換文字
  - $types (*string*) – 預設是**full** 可用參數 half | full 選擇字型
  - $kinds (*array*) – 預設是 **['symbol', 'number', 'letter']** 代表符號, 數字, 字母 可加其他需要轉換的符號
- Returns
  - *string* - 目標轉換文字
- Example
  
    ```php
    use \nueip\helpers\TextHelper;

    $data = 'abcdefg';
    echo $data;
    $show = TextHelper::half2full($data);
    echo $show;
    ```

- Result
  
    ```php
    abcdefg
    ａｂｃｄｅｆｇ
    ```

### br2nl

將字串中的\<br>轉變成 \n

- Description
  - br2nl( string `$str`) : string
- Parameters
  - $str (*string*) – 待轉換文字
- Returns
  - *string* - 轉換後文字
  
### trimNonPrintChar

濾除無法列印的字元

- Description
  - trimNonPrintChar( string `$str`) : string
- Parameters
  - $str (*string*) – 待轉換文字
- Returns
  - *string* - 轉換後文字
  
### strWidthLimit

寬度處理 - 單行

- Description
  - strWidthLimit( string `$str`[, integer `$widthLimit`[, string `$trimmarker` ]] ) : string
- Parameters
  - $str (*string*) – 待處理文字
  - $widthLimit (*integer*) – 預設是 **50** 字串寬度
  - $trimmarker (*string*) – 預設是 **...** 當超過限制，取代多餘的字串
- Returns
  - *string* - 目標轉換文字
- Example
  
    ```php
    use \nueip\helpers\TextHelper;

    $data = '寬度處理 - 單行 * - 未處理換行符 * 換行符請在程式外部自行處理';
    $show = TextHelper::strWidthLimit($data);
    echo $show;
    ```

- Result
  
    ```php
    寬度處理 - 單行 * - 未處理換行符 * 換行符請在程...
    ```

### strLineWidthRepair

超過寬度的行，會補斷行(變多行)，但不超過的行不另處理

- Description
  - strLineWidthRepair( string `$str`[, integer `$widthLimit`[, string `$lineLimit`[, string `$trimmarker` ]]] ) : string
- Parameters
  - $str (*string*) – 待處理文字
  - $widthLimit (*integer*) – 預設是 **50** 字串寬度
  - $lineLimit (*string*) – 預設是 **0** 行數限制，0為不限制
  - $trimmarker (*string*) – 預設是 **...** 當超過限制，取代多餘的字串
- Returns
  - *string* - 目標轉換文字

### strLineWidthRefactor(`$str`, `$widthLimit`, `$lineLimit`, `$trimmarker`)

超過寬度的行，字串依指定寬度重新斷行

- Description
  - strLineWidthRefactor( string `$str`[, integer `$widthLimit`[, string `$lineLimit`[, string `$trimmarker` ]]] ) : string
- Parameters
  - $str (*string*) – 待處理文字
  - $widthLimit (*integer*) – 預設是 **50** 字串寬度
  - $lineLimit (*string*) – 預設是 **0** 行數限制，0為不限制
  - $trimmarker (*string*) – 預設是 **...** 當超過限制，取代多餘的字串
- Returns
  - *string* - 目標轉換文字
  
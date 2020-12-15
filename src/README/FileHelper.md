# FileHelper

- [FileHelper](#filehelper)
  - [Methods](#methods)
    - [validateUploadSize](#validateuploadsize)
    - [filterFilename](#filterfilename)
    - [encodeConv](#encodeconv)
    - [Data 數組](#data-數組)
    - [exportPlain](#exportplain)
    - [exportCsv](#exportcsv)
    - [exportZip](#exportzip)

## Methods

### validateUploadSize

檢視上傳的檔案總大小，需可以上傳多個檔案

- Description
  - validateUploadSize( [ integer `$maxKB` ] ) : boolean
- Parameters
  - $maxKB (*integer*) – 預設是 **1000**
- Returns
  - *boolean* - 如果大於限制大小回傳false，在許可大小中回傳true
- Example
  
    ```php
    use \nueip\helpers\FileHelper;

    echo FileHelper::validateUploadSize();
    ```

- Result

    ```php
    true
    ```

### filterFilename

檢查檔案名，把保留字元去掉及空白符號轉換成底線，回傳改好的檔案名

- Description
  - filterFilename( string `$filename`) : string
- Parameters
  - $filename (*string*) – 檔案名稱
- Returns
  - *string* - 目標檔名
- Example
  
    ```php
    use \nueip\helpers\FileHelper;

    echo FileHelper::filterFilename('2020<1> 11/14');
    ```

- Result
  
    ```php
    20201_1114
    ```

### encodeConv

改變檔案的加密方式重新儲存，從`$convEncode`加密方式轉成`$fileEncode`

- Description
  - encodeConv( string `$filePath`[, mixed `$fileEncode`[, string `$convEncode` ]] ) : mixed
- Parameters
  - $filePath (*string*) – 目標檔案位置
  - $fileEncode (*string|array*) – 預設是 **BIG5** 加密方式
  - $convEncode (*string*) – 預設是 **UTF-8** 內部加密方式
- Returns
  - *mixed* - 目標檔案

### Data 數組

```php
$data = [
            '0' => ['Hello','World!','Beautiful','Day!'],
            '1' => ['Hello','TAiWAN!','Beautiful','Day!'],
            '2' => ['Hello','!','Beautiful','Day!'],
        ];
```

### exportPlain

文本檔案輸出，將數組轉字符匯出文本Text

- Description
  - exportPlain( string `$filename`, array `$data`[, string `$fileEncode`[, string `$separator` ]] ) : mixed
- Parameters
  - $filename (*string*) – 輸出檔案名，需要加上匯出的檔案類型
  - $data (*array*) – 檔案內容
  - $fileEncode (*string*) – 預設是 **UTF-8** 轉成的編碼方式 可用參數 UTF-8 BOM | UTF-8 | BIG-5
  - $separator (*string*) – 預設是 **,** 分隔數組的方式
- Returns
  - *mixed* - 檔案文本Text
- Example

    ```php
    use \nueip\helpers\FileHelper;

    $filename = "tmp.txt";
    echo FileHelper::exportPlain($filename, $data, 'UTF-8', ' ');
    ```

- Result
  
    ```php
    Hello World! Beautiful Day!
    Hello TAiWAN! Beautiful Day!
    Hello ! Beautiful Day!
    ```

### exportCsv

CSV檔案輸出，將數組轉字符匯出檔案csv，將數組內容加上""

- Description
  - exportCsv( string `$filename`, array `$data`[, string `$fileEncode`[, string `$separator` ]] ) : mixed
- Parameters
  - $filename (*string*) – 輸出檔案名，需要加上匯出的檔案類型
  - $data (*array*) – 檔案內容
  - $fileEncode (*string*) – 預設是 **UTF-8** 轉成的編碼方式 可用參數 UTF-8 BOM | UTF-8 | BIG-5
  - $separator (*string*) – 預設是 **,** 分隔數組的方式
- Returns
  - *mixed* - 檔案CSV
- Example
  
    ```php
    use \nueip\helpers\FileHelper;

    $filename = "tmp.csv";
    echo FileHelper::exportCsv($filename, $data, 'UTF-8', ' ');
    ```

- Result
  
    ```php
    Hello "World!" "Beautiful" "Day!"
    Hello "TAiWAN!" "Beautiful" "Day!"
    Hello "!" "Beautiful" "Day!"

    ```

### exportZip

將存在路徑的檔案壓縮成ZIP，匯出後刪除

- Description
  - exportZip( string `$filename`, string `$path`[, string `$autoUnlink` ] ) : mixed
- Parameters
  - $filename (*string*) – 輸出檔案名
  - $path (*string*) – 檔案存在路徑
  - $autoUnlink (*string*) – 預設是 **true** 是否刪除檔案
- Returns
  - *mixed* - 目標檔案

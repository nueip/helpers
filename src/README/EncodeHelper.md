# EncodeHelper

- [EncodeHelper](#encodehelper)
  - [Methods](#methods)
    - [snapshotEncode](#snapshotencode)
    - [snapshotDecode](#snapshotdecode)
  
## Methods

### snapshotEncode

壓縮檔案

- Description
  - snapshotEncode( mixed `$data`[, bool `$forceCompress`]) : bool
- Parameters
  - $data (*array|mixed*) - 資料陣列
  - $forceCompress (*bool*) – 預設是 **false** 是否要強制壓縮檔案
- Returns
  - *bool* - 快照資料

### snapshotDecode

解壓縮檔案

- Description
  - snapshotDecode( mixed `$data`[, bool `$assoc` ] ) : mixed
- Parameters
  - $data (*array|mixed*) - 快照資料
  - $assoc (*bool*) – 預設是 **true** 回傳格式: 陣列(true) ; 物件(false)
- Returns
  - *mixed* - 目標資料
- Example
  
  ```php
  use \nueip\helpers\EncodeHelper;
  $data = [
              '0' => ['name' => 'Mars', 'birthday' => '2000-01-01', 'pet' => 'dog'],
              '1' => ['name' => 'Mars2', 'birthday' => '2000-02-02', 'pet' => 'cat'],
              '2' => ['name' => 'Mars3', 'birthday' => '2000-03-03', 'pet' => 'bird'],
          ];
  $encode = EncodeHelper::snapshotEncode($data);
  var_export($encode);
  $decode = EncodeHelper::snapshotDecode($encode);
  var_export($decode);
  ```

- Result

  ```php
  '2i65WykvMTVWyUvJNLCpW0lFKyiwqyUhJrASKGBkYGOgaGAIRULwgtQQolJKfrlSrg6LHCJsmIyCCa0pOLEHXZIxNkzEQwTUBZVOUamMB'
  array (
    0 =>
    array (
      'name' => 'Mars',
      'birthday' => '2000-01-01',
      'pet' => 'dog',
    ),
    1 =>
    array (
      'name' => 'Mars2',
      'birthday' => '2000-02-02',
      'pet' => 'cat',
    ),
    2 =>
    array (
      'name' => 'Mars3',
      'birthday' => '2000-03-03',
      'pet' => 'bird',
    ),
  )
  ```

# UrlHelper

- [UrlHelper](#urlhelper)
  - [Methods](#methods)
    - [safeMergeSegments(`$segments`, `$trimMergedUri`)](#safemergesegmentssegments-trimmergeduri)
    - [safeBase64Decode](#safebase64decode)
    - [safeBase64Encode](#safebase64encode)
    - [decodeUrlParams](#decodeurlparams)
    - [encodeUrlParams](#encodeurlparams)

## Methods

### safeMergeSegments(`$segments`, `$trimMergedUri`)

陣列組合成網址

- Description
  - safeMergeSegments( array `$segments`[, bool `$trimMergedUri` ] ) : string
- Parameters
  - $segments (*array*) – 目標組合 URI 陣列
  - $trimMergedUri (*bool*) – 預設是 **false** 是否要去除 `/`
- Returns
  - *string* - URL Link
- Example

  ```php
  use \nueip\helpers\UrlHelper;

  $data = ['https://example.com/', '/first/second/'];
  $show = UrlHelper::safeMergeSegments($data);
  print_r($show);

  $show = UrlHelper::safeMergeSegments($data, true);
  print_r($show);
  ```

- Result

  ```php
  https://example.com/first/second/
  https://example.com/first/second
  ```

### safeBase64Decode

解析一個用Base64加密過後的字串

- Description
  - safeBase64Decode( string `$input`) : string
- Parameters
  - $input (*string*) – 用Base64加密的字串
- Returns
  - *string* - 解密後字串
  
### safeBase64Encode

將一個字串加密成Base64

- Description
  - safeBase64Encode( string `$input`) : string
- Parameters
  - $input (*string*) – 待加密字串
- Returns
  - *string* - 用Base64加密的字串
  
### decodeUrlParams

解析 url 用 GET 帶入的參數 `$paramKey`

- Description
  - decodeUrlParams( string `$paramKey`) : mixed
- Parameters
  - $paramKey (*string*) – 預設是 **params**  GET 帶入的參數
- Returns
  - *mixed* - 解碼後的參數

### encodeUrlParams

加密 url 用 GET 帶入的參數 `$data`

- Description
  - encodeUrlParams( string `$data`) : mixed
- Parameters
  - $data (*string*) – 要加密的參數
- Returns
  - *mixed* - 加密後的參數

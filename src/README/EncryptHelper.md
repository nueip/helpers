# EncryptHelper

- [EncryptHelper](#encrypthelper)
  - [Methods](#methods)
    - [事先準備](#事先準備)
    - [encryptCustom](#encryptcustom)
    - [decryptCustom](#decryptcustom)
    - [encryptWeb](#encryptweb)
    - [decryptWeb](#decryptweb)
    - [encrypt](#encrypt)
    - [decrypt](#decrypt)

## Methods

### 事先準備

- 定義常數
  
    ```php
    // 定義環境
    define('ENVIRONMENT', 'development');
    // 定義開發環境json檔路徑
    define('HASH_TOKEN_PATH_DEV', '開發環境的目標的JSON檔案路徑');
    // 定義產品環境json檔路徑
    define('HASH_TOKEN_PATH','產品環境的目標的JSON檔案路徑');
    ```

- JSON檔案內容
  
    ```json
    {
        "project_id": "nueip-encrypt",
        // 加密模式
        "method": {
            "private": "rijndael-128",
            "custom": "AES-128-CBC",
            "web": "AES-256-CBC"
        },
        // 加密金鑰
        "key": {
            "private": "5AgAJaY4wHRJ56kjh8W65wet",
            "custom": "R9wHRJ65Ja4Ios5JaY4wH65E",
            "web": "session_idJaY4wHwHR4aJs5e"
        }
    }
    ```

### encryptCustom

用JSON檔中的custom的加密模式加密

- Description
  - encryptCustom( string `$string`, string `$iv`) : string
- Parameters
  - $string (*string*) – 待加密目標
  - $iv (*string*) – 加密初始向量
- Returns
  - *string* - 已加密字串
  
### decryptCustom

用JSON檔中的custom的加密模式解密

- Description
  - decryptCustom( string `$string`, string `$iv`) : string
- Parameters
  - $string (*string*) – 待解密目標
  - $iv (*string*) – 加密初始向量
- Returns
  - *string* - 已解密字串
- Example
  
    ```php
    use nueip\helpers\EncryptHelper;

    $data = 'HelloWorld';
    $show = EncryptHelper::encryptCustom($data, '61SD5F5');
    echo $show;
    $show2 = EncryptHelper::decryptCustom($show, '61SD5F5');
    echo $show2;
    ```

- Result

    ```php
    maWY22VBgNRHm9X+lHTIpQ==
    HelloWorld
    ```

### encryptWeb

用JSON檔中的web的加密模式加密，沒有使用iv計算偏移量

- Description
  - encryptWeb( string `$string`) : string
- Parameters
  - $string (*string*) – 待加密目標
- Returns
  - *string* - 已加密字串
  
### decryptWeb

用JSON檔中的web的加密模式解密，沒有使用iv計算偏移量

- Description
  - decryptWeb( string `$string`) : string
- Parameters
  - $string (*string*) – 待解密目標
- Returns
  - *string* - 已解密字串
- Example
  
    ```php
    use nueip\helpers\EncryptHelper;

    $data = 'HelloWorld';
    $show = EncryptHelper::encryptWeb($data);
    echo $show;
    $show2 = EncryptHelper::decryptWeb($show);
    echo $show2;
    ```

- Result

    ```php
    4Sa3qclg0NPkp6oJ%2BJ9o8w%3D%3D
    HelloWorld
    ```

### encrypt

用JSON檔中的private的加密模式加密，沒有使用iv計算偏移量

- Description
  - encrypt( string `$string`) : string
- Parameters
  - $string (*string*) – 待加密目標
- Returns
  - *string* - 已加密字串
  
### decrypt

用JSON檔中的private的加密模式解密，沒有使用iv計算偏移量

- Description
  - decrypt( string `$string`) : string
- Parameters
  - $string (*string*) – 待解密目標
- Returns
  - *string* - 已解密字串
- Example
  
    ```php
    use nueip\helpers\EncryptHelper;

    $data = 'HelloWorld';
    $show = EncryptHelper::encrypt($data);
    echo $show;
    $show2 = EncryptHelper::decrypt($show);
    echo $show2;
    ```

- Result

    ```php
    alih1nbI+cxUUr56P7t1NUM8xRzXPzzzPpdwSDIJncQ=
    HelloWorld
    ```

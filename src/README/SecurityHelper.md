# SecurityHelper

- [SecurityHelper](#securityhelper)
  - [Methods](#methods)
    - [Data 包含Script](#data-包含script)
    - [deepHtmlspecialchars](#deephtmlspecialchars)
    - [isHttps](#ishttps)
    - [setTotpSpace](#settotpspace)
    - [getTotpSpace](#gettotpspace)
    - [getTotpSecret](#gettotpsecret)
    - [getTotpQrCode](#gettotpqrcode)
    - [verifyTotp](#verifytotp)
  
## Methods

### Data 包含Script

```php
$data = [
            '0' => 'Hello<script>alert("World");</script>',
            '1' => 'Hello2<script>alert("World");</script>',
            '2' => 'Hello3<script>alert("World");</script>',
        ];
```

### deepHtmlspecialchars

防注入字符，將含有非預期的script字串原樣返回

- Description
  - deepHtmlspecialchars( mixed `$data`) : mixed
- Parameters
  - $data (*mixed*) – 含有非預期的目標組合
- Returns
  - *mixed* - 原樣目標
- Example.

  ```php
  use \nueip\helpers\SecurityHelper;
  $show = SecurityHelper::deepHtmlspecialchars($data);
  var_export($show);
  ```

- Result
  
  ```php
  array (
    0 => 'Hello<script>alert("World");</script>',
    1 => 'Hello2<script>alert("World");</script>',
    2 => 'Hello3<script>alert("World");</script>',
  )
  ```

### isHttps

識別請求是否是HTTPS

- Description
  - isHttps() : boolean
- Returns
  - *boolean* - 是否是HTTPS
  
### setTotpSpace

設`$totpSpace` 為 class TwoFactorAuth()

- Description
  - setTotpSpace( string `$space`) : TwoFactorAuth
- Parameters
  - $space (*string*) – 顯示的名稱
- Returns
  - *TwoFactorAuth* - 目標物件
  
### getTotpSpace

確認`$totpSpace` 是否為class TwoFactorAuth()，若不是則宣告成class TwoFactorAuth()

- Description
  - getTotpSpace() : TwoFactorAuth
- Returns
  - *TwoFactorAuth* - 目標物件
  
### getTotpSecret

產生二次驗證的密碼

- Description
  - getTotpSecret( [ integer `$bits` ] ) : TwoFactorAuth
- Parameters
  - $bits (*integer*) – 預設是 **160** 密碼的大小
- Returns
  - *TwoFactorAuth* - 目標物件
  
### getTotpQrCode

- Description
  - getTotpQrCode( string `$label`, string `$secret`) : string
- Parameters
  - $label (*string*) – 目標要編碼的URL
  - $secret (*string*) – Qrcode圖形密碼
- Returns
  - *string* - 目標Qrcode
- Example 產生Qrcode圖形密碼
  
  ```php
  use \nueip\helpers\SecurityHelper;

  $secret = SecurityHelper::getTotpSecret();
  $url = 'weiya-service.dev.nueip.com/test';
  $show = SecurityHelper::getTotpQrCode($url, $secret);
  echo "<img src='".$show."'/><hr/>";
  ```

- Result
  ![圖片](img/QRCODE.png)

### verifyTotp

比較儲存的密碼與回傳得到的密碼是否一致

- Description
  - verifyTotp( string `$secret`, string `$code`) : bool
- Parameters
  - $secret (*string*) – 儲存密碼
  - $code (*string*) – 回傳密碼
- Returns
  - *bool* - 密碼是否一致
  
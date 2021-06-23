System Helper<!-- omit in toc -->
===

Helper for system operation.

<img alt="PHP7" src="https://shields.io/badge/PHP-7-green?PHP=7">

# 1. Outline
<!-- TOC -->

- [1. Outline](#1-outline)
- [2. Description](#2-description)
- [3. Installation](#3-installation)
  - [3.1. composer install](#31-composer-install)
- [4. Dependency](#4-dependency)
- [5. Usage](#5-usage)
  - [5.1. System](#51-system)
    - [5.1.1. Setting](#511-setting)
    - [5.1.2. 最小記憶體設定](#512-最小記憶體設定)
      - [5.1.2.1. Description](#5121-description)
      - [5.1.2.2. Parameters](#5122-parameters)
      - [5.1.2.3. Return Values](#5123-return-values)
      - [5.1.2.4. Example](#5124-example)
  - [5.2. Cookie](#52-cookie)
    - [5.2.1. Setting](#521-setting)
    - [5.2.2. Cookie Default Option - Single](#522-cookie-default-option---single)
      - [5.2.2.1. Description](#5221-description)
      - [5.2.2.2. Parameters](#5222-parameters)
      - [5.2.2.3. Return Values](#5223-return-values)
      - [5.2.2.4. Example](#5224-example)
    - [5.2.3. Set Cookie Default Option - Multiple](#523-set-cookie-default-option---multiple)
      - [5.2.3.1. Description](#5231-description)
      - [5.2.3.2. Parameters](#5232-parameters)
      - [5.2.3.3. Return Values](#5233-return-values)
      - [5.2.3.4. Example](#5234-example)
    - [5.2.4. Set Cookie](#524-set-cookie)
      - [5.2.4.1. Description](#5241-description)
      - [5.2.4.2. Parameters](#5242-parameters)
      - [5.2.4.3. Return Values](#5243-return-values)
      - [5.2.4.4. Example](#5244-example)
    - [5.2.5. Set Cookie on root domain](#525-set-cookie-on-root-domain)
      - [5.2.5.1. Description](#5251-description)
    - [5.2.6. Get Cookie](#526-get-cookie)
      - [5.2.6.1. Description](#5261-description)
    - [5.2.7. Delete Cookie](#527-delete-cookie)
      - [5.2.7.1. Description](#5271-description)
    - [5.2.8. Delete Cookie on root domain](#528-delete-cookie-on-root-domain)
      - [5.2.8.1. Description](#5281-description)
- [6. Example](#6-example)
- [7. Contributing](#7-contributing)

<!-- /TOC -->


# 2. [Description](#outline)


# 3. [Installation](#outline)
## 3.1. composer install

```bash
$ composer require nueip/helpers
```

# 4. [Dependency](#outline)
- PHP7

# 5. [Usage](#outline)
## 5.1. System
- library for Cookie operating
- Wrap the PHP native function/variable setcookie()/$_COOKIE to enrich the function and simplify the usage method.
- Extra features:
  - Support Cookie prefix packaging
  - Support default values: expires, path, domain, secure, httponly
  - Dedicated function: Root domain access

### 5.1.1. Setting
- 使用前設定
```php
// 引入Composer autoload
include('../vendor/autoload.php');

// 宣告使用 SystemHelper
use nueip\SystemHelper;
```

### 5.1.2. 最小記憶體設定
最小記憶體設定  
- 以M為單位，不做單位轉換
- 如果現有可用記憶體大於本次設定值，不會縮減
- 建議：一般程式不超過512M，匯出程式不超過1024M
- 最大 2048M

#### 5.1.2.1. Description
    memoryMoreThan( [string `$memorySize`] ) : bool

#### 5.1.2.2. Parameters 
    $memorySize (*string*) – 記憶體大小，單位 M，預設 256M


#### 5.1.2.3. Return Values
    bool

#### 5.1.2.4. Example
```php
use nueip\helpers\SystemHelper;

SystemHelper::memoryMoreThan('256M');

echo ini_get('memory_limit');
// Result: 256M
```


## 5.2. Cookie
- library for Cookie operating
- Wrap the PHP native function/variable setcookie()/$_COOKIE to enrich the function and simplify the usage method.
- Extra features:
  - Support Cookie prefix packaging
  - Support default values: expires, path, domain, secure, httponly
  - Dedicated function: Root domain access

### 5.2.1. Setting
- 使用前設定
```php
// 引入Composer autoload
include('../vendor/autoload.php');

// 宣告使用 SystemHelper
use marsapp\helper\system\SystemHelper;

// 常數設定 - 需設 Cookie前綴、過期時間、預設路徑、根網域
define('COOKIE_DEFAULT_PREFIX', 'dev_');
define('COOKIE_DEFAULT_EXPIRES', 0);
define('COOKIE_DEFAULT_PATH', '/');
define('COOKIE_ROOT_DOMAIN', 'dev.local');
```

### 5.2.2. Cookie Default Option - Single
#### 5.2.2.1. Description
    cookieOption(string $param, string $value = null) : string|null

#### 5.2.2.2. Parameters 
- $param : string
  - The target parameter of the cookie default options.
- $value : string
  - New data.

> - When $param does not exist, return null
> - When there is $param but no $value(null), get the current value of $param
> - When there is $param and $value, after updating the data, the updated value will be returned


#### 5.2.2.3. Return Values
string|null

#### 5.2.2.4. Example
```php
// 取得Cookie預設參數值
$httponly = SystemHelper::cookieOption('httponly');

// 設定Cookie預設參數-單筆
$httponly = SystemHelper::cookieOption('httponly', false);
```

### 5.2.3. Set Cookie Default Option - Multiple
#### 5.2.3.1. Description
    cookieOptions(array $options = []) : array

#### 5.2.3.2. Parameters 
- $options : array
  - batch update cookie default options.

#### 5.2.3.3. Return Values
array, return complete cookie default options. 

#### 5.2.3.4. Example
```php
// 變數設定
$options = [
    // 存放路徑
    'path' => '/tmp/',
    // 是否只能通過HTTP協議訪問
    'httponly' => false,
];

// 回傳完整Cookie預設參數
$cookieOptions = SystemHelper::cookieOptions();

// 設定Cookie預設參數-多筆
$cookieOptions = SystemHelper::cookieOptions($options);
```

### 5.2.4. Set Cookie
#### 5.2.4.1. Description
    cookieSet(string $name, string $value = "", array $options = []) : bool

> Set Cookie, Wrap the PHP native function setcookie() to simplify usage.

#### 5.2.4.2. Parameters 
- $name : string
  - The name of the cookie.
- $value : string
  - The value of the cookie.
- $options : array
  - 'prefix' => '',           // Cookie前綴，預設無前綴
  - 'expires' => time()+3600, // 過期時間，預設無期限
  - 'path' => '/',            // 存放路徑
  - 'domain' => "",           // 所屬網域
  - 'secure' => true,         // 是否只在https生效
  - 'httponly' => false,      // 是否只能通過HTTP協議訪問

#### 5.2.4.3. Return Values
bool, like PHP function setcookie()

#### 5.2.4.4. Example
```php
// 變數設定
$name = 'testCookie';
$value = "test-" . mt_rand(1111, 9999);
$options = [
    // 存放路徑
    'path' => '/',
    // 是否只能通過HTTP協議訪問
    'httponly' => true,
];

// 設定Cookie
SystemHelper::cookieSet($name, $value, $options);
```
> - $options可用參數
>   - 'prefix' => '',           // Cookie前綴，預設無前綴
>   - 'expires' => time()+3600, // 過期時間，預設無期限
>   - 'path' => '/',            // 存放路徑
>   - 'domain' => "",           // 所屬網域
>   - 'secure' => true,         // 是否只在https生效
>   - 'httponly' => false,      // 是否只能通過HTTP協議訪問


### 5.2.5. Set Cookie on root domain
#### 5.2.5.1. Description
    cookieSetRoot(string $name, string $value = "", array $options = []) : bool

> Like `cookieSet()`, but domain fixed on root domain.

### 5.2.6. Get Cookie
#### 5.2.6.1. Description
    cookieGet(string $name) : mixed

> 取得目標Cookie值

### 5.2.7. Delete Cookie
#### 5.2.7.1. Description
    cookieExpired(string $name, array $options = []) : bool

> Delete target cookie.

### 5.2.8. Delete Cookie on root domain
#### 5.2.8.1. Description
    cookieExpiredRoot(string $name, array $options = []) : bool

> Like `cookieExpired()`, but domain fixed on root domain.

# 6. [Example](#outline)
see: example/example-cookie.php

# 7. [Contributing](#outline)
- 20210506: MarsHung Fork Cookie function from https://github.com/marshung24/system-helper
- 20210623: MarsHung Fork Cookie function cookieOption(), cookieOptions(), COOKIE_DEFAULT_PREFIX from https://github.com/marshung24/system-helper

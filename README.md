# NuEIP Helpers

NuEIP Helpers.

[![Latest Stable Version](https://poser.pugx.org/nueip/helpers/v/stable)](https://packagist.org/packages/nueip/helpers) [![Total Downloads](https://poser.pugx.org/nueip/helpers/downloads)](https://packagist.org/packages/nueip/helpers) [![Latest Unstable Version](https://poser.pugx.org/nueip/helpers/v/unstable)](https://packagist.org/packages/nueip/helpers) [![License](https://poser.pugx.org/nueip/helpers/license)](https://packagist.org/packages/nueip/helpers)

## 功能講解

- [ArrayHelper](/src/README/ArrayHelper.md)

  - 陣列有關函式

- [DateTimeHelper](/src/README/DateTimeHelper.md)

  - 日期計算有關函式

- [EncodeHelper](/src/README/EncodeHelper.md)

  - 解/壓縮檔案有關函式

- [EncryptHelper](/src/README/EncryptHelper.md)

  - 加解密有關函式

- [FileHelper](/src/README/FileHelper.md)

  - 檔案輸出有關函式

- [SecurityHelper](/src/README/SecurityHelper.md)

  - Qrcode有關函式

- [SqlHelper](/src/README/SqlHelper.md)

  - ci中用的SQL有關函式

- [SystemHelper](/src/README/SystemHelper.md)

  - 提供處理系統參數控制的方法

- [TextHelper](/src/README/TextHelper.md)

  - 文字有關函式

- [TimePeriodHelper](/src/README/TimePeriodHelper.md)

  - 時間集合有關函式

- [UrlHelper](/src/README/UrlHelper.md)

  - 網址有關函式

- [ValidateHelper](/src/README/ValidateHelper.md)

  - 驗證有關函式

## Mcrypt 支援
> PHP 7.2 以上需要自行安裝 Mcrypt
```bash
# 安裝依賴
$ sudo apt install gcc make autoconf libc-dev pkg-config libmcrypt-dev php-pear php8.1-dev

# 安裝 Mcrypt
$ sudo apt-get -y install libmcrypt-dev
$ sudo pecl install mcrypt-1.0.4

# 設定
$ sudo bash -c "echo extension=/usr/lib/php/20200930/mcrypt.so > /etc/php/8.1/cli/conf.d/mcrypt.ini"
$ sudo bash -c "echo extension=/usr/lib/php/20200930/mcrypt.so > /etc/php/8.1/apache2/conf.d/mcrypt.ini"

# 檢查
$ php -i | grep "mcrypt"
```


## 測試
> 使用PHPUnit測試

### PHP7.0

```bash
# 下載指定版本的 PHPUnit 
$ wget https://phar.phpunit.de/phpunit-6.5.phar

# 執行 PHPUnit 
$ php7.0 phpunit-6.5.phar -c phpunit-6.5.xml
```


### PHP7.4
```bash
# 下載指定版本的 PHPUnit 
$ wget https://phar.phpunit.de/phpunit-9.6.phar

# 環境變數
$ XDEBUG_MODE=coverage; export XDEBUG_MODE;

# 執行 PHPUnit 
$ php7.4 phpunit-9.6.phar -c phpunit-9.6.xml
```


### PHP8.1
```sh
# 下載指定版本的 PHPUnit 
$ wget https://phar.phpunit.de/phpunit-10.1.phar

# 環境變數
$ XDEBUG_MODE=coverage; export XDEBUG_MODE;

# 執行 PHPUnit 
$ php8.1 phpunit-10.1.phar -c phpunit-10.1.xml
```





  
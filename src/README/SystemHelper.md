# SystemHelper

- [SystemHelper](#systemhelper)
  - [Methods](#methods)
    - [memoryMoreThan](#memorymorethan)

## Methods

### memoryMoreThan

最小記憶體設定  
- 以M為單位，不做單位轉換
- 如果現有可用記憶體大於本次設定值，不會縮減
- 建議：一般程式不超過512M，匯出程式不超過1024M
- 最大 2048M


- Description
  - memoryMoreThan( [string `$memorySize`] ) : bool
- Parameters
  - $memorySize (*string*) – 記憶體大小，單位 M，預設 256M
- Returns
  - bool
- Example

    ```php
    use nueip\helpers\SystemHelper;

    SystemHelper::memoryMoreThan('256M');

    echo ini_get('memory_limit');
    ```

- Result
  
    ```
    256M
    ```

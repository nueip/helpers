# ValidateHelper

- [ValidateHelper](#validatehelper)
  - [Methods](#methods)
    - [validate](#validate)


## Methods

### validate

執行GUMP驗証

- Description
  - validate( array `$rawData`, array `$rules`[, string `$msgType`[, string `$msgCode` ]] ) : mixed
- Parameters
  - $rawData (*array*) – 原始資料
  - $rules (*array*) – 驗証規則
  - $msgType (*string*) – 預設是 **message** 可用參數 message | json | jsonHttp | exception| return 訊息輸出模式
  - $msgCode (*string*) – 預設是 **404** 狀態碼
- Returns
  - *mixed* - 若是GUMP回傳true，若不是則回傳錯誤訊息

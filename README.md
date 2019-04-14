# teacher.TKU-OpenData-API
改造原始 API Demo: [data.php](http://www.ee.tku.edu.tw/api/data.php)

## Enter your username and password
Line: 99, 100, 113, 114, 126, 127.

## $\_POST
  - mode
  - data
  
## Mode
### FdTypInfo（類別資訊）
data: (NULL)

### UidList（成員操作）
data: 
- ot
  - l（list 顯示列表）
  - d（delete 刪除）
  - i（insert 加入）

### ThrInfo（教師資訊）
data: (NULL)

### TpFdList（教師歷程類別資料）
data: 
- dt:（資料起始時間, 格式：yyyy-MM-dd)
- yc:（迄年, 1 ~ 10）
- ti:（FdTypInfo 取得的類別代碼）

## 回傳欄位與原有 API相同，故不介紹。

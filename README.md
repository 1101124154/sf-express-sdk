# Wangwei SF Express SDK (PHP Composer Package)

一个轻量的顺丰（丰桥）开放平台 PHP SDK，提供统一的客户端与常用接口方法封装，遵循 PSR-4，无框架依赖。

## 特性

- 统一 `call(serviceCode, payload)`，与官方示例签名一致
- 内置常用方法封装（如下单、查路由、校验运单号等）
- 支持沙盒/生产环境切换，可覆盖默认接口地址
- 仅依赖 PHP 标准库，无需额外 HTTP 客户端

## 安装

- Packagist（推荐正式使用）
  - `composer require wangwei1101/sf-express-sdk`

- VCS 仓库（直接引用 GitHub）
  - `composer config repositories.sf-express-sdk vcs https://github.com/1101124154/sf-express-sdk.git`
  - `composer require wangwei1101/sf-express-sdk:dev-main`

- 本地 Path 仓库（联调）
  - 在应用的 `composer.json` 增加：
    ```json
    {
      "repositories": [
        { "type": "path", "url": "../SF-CSIM-EXPRESS-SDK-PHP-V2.1.2/sf-express-sdk", "options": { "symlink": true } }
      ],
      "require": {
        "wangwei1101/sf-express-sdk": "dev-main"
      },
      "minimum-stability": "dev",
      "prefer-stable": true
    }
    ```
- 执行：`composer update wangwei1101/sf-express-sdk`

## 快速开始

```php
use Wangwei\SfExpress\Client;
use Wangwei\SfExpress\Api;

$config = [
    'partner_id' => 'your-partner-id',
    'checkword'  => 'your-checkword',
    'env'        => 'sandbox', // or 'prod'
    'timeout'    => 30,
];

$client = new Client($config);
$api = new Api($client);

// 原始调用
$res = $client->call('EXP_RECE_VALIDATE_WAYBILLNO', [ 'waybillNo' => '1234567890' ]);

// 便捷方法
$res2 = $api->validateWaybillNo([ 'waybillNo' => '1234567890' ]);
```

## 配置项

- `partner_id`（string）：丰桥顾客编码
- `checkword`（string）：丰桥校验码
- `env`（string）：`sandbox` 或 `prod`
- `timeout`（int）：请求超时秒数，默认 `30`
- `sandbox_url`（string，可选）：覆盖沙盒地址，默认 `http://sfapi-sbox.sf-express.com/std/service`
- `prod_url`（string，可选）：覆盖生产地址，默认 `https://sfapi.sf-express.com/std/service`

## 配置文件

- 已提供示例配置：`config/sf_express.php`
- 直接在项目中引用：
  ```php
$config = require __DIR__ . '/vendor/wangwei1101/sf-express-sdk/config/sf_express.php';
  $client = new \Wangwei\SfExpress\Client($config);
  $api    = new \Wangwei\SfExpress\Api($client);
  ```
- 支持环境变量映射（可在 `.env` 或系统环境中设置）：
  - `SF_PARTNER_ID`、`SF_CHECKWORD`
  - `SF_ENV`（`sandbox`/`prod`）、`SF_TIMEOUT`
  - `SF_SANDBOX_URL`、`SF_PROD_URL`

## 接口方法与 serviceCode 映射

- 订单：
  - `createOrder` → `EXP_RECE_CREATE_ORDER`
  - `searchOrderResp` → `EXP_RECE_SEARCH_ORDER_RESP`
  - `updateOrder` → `EXP_RECE_UPDATE_ORDER`
  - `filterOrderBsp` → `EXP_RECE_FILTER_ORDER_BSP`
- 路由：
  - `searchRoutesByMailNo` → `EXP_RECE_SEARCH_ROUTES`
  - `searchRoutesByOrderNo` → `EXP_RECE_SEARCH_ROUTES`
  - `registerRoute` → `EXP_RECE_REGISTER_ROUTE`
  - `uploadRoute` → `EXP_RECE_UPLOAD_ROUTE`
- 子单与费用：
  - `getSubMailNo` → `EXP_RECE_GET_SUB_MAILNO`
  - `querySfWaybillFees` → `EXP_RECE_QUERY_SFWAYBILL`
- 逆向单：
  - `createReverseOrder` → `EXP_RECE_CREATE_REVERSE_ORDER`
  - `cancelReverseOrder` → `EXP_RECE_CANCEL_REVERSE_ORDER`
- 通知与时效：
  - `deliveryNotice` → `EXP_RECE_DELIVERY_NOTICE`
  - `queryDeliverTm` → `EXP_RECE_QUERY_DELIVERTM`
  - `searchPromitm` → `EXP_RECE_SEARCH_PROMITM`
  - `checkPickupTime` → `EXP_EXCE_CHECK_PICKUP_TIME`
- 其他：
  - `registerWaybillPicture` → `EXP_RECE_REGISTER_WAYBILL_PICTURE`
  - `wantedIntercept` → `EXP_RECE_WANTED_INTERCEPT`
  - `cloudPrintWaybills` → `COM_RECE_CLOUD_PRINT_WAYBILLS`
  - `validateWaybillNo` → `EXP_RECE_VALIDATE_WAYBILLNO`

## 典型 payload 示例

> 完整字段可参考顺丰官方文档与 JSON 模板。

- 运单号校验（`EXP_RECE_VALIDATE_WAYBILLNO`）：
  ```json
  { "waybillNo": "1234567890" }
  ```
- 路由查询（按运单号，`EXP_RECE_SEARCH_ROUTES`）：
  ```json
  { "mailNo": "SF1234567890123" }
  ```
- 下单（`EXP_RECE_CREATE_ORDER`，示例最小化）：
  ```json
  {
    "orderId": "ORDER123",
    "cargo": { "name": "衣服" },
    "contactInfoList": [
      { "contactType": 1, "country": "CN", "province": "广东", "city": "深圳", "address": "南山区科技园", "mobile": "13800000000", "company": "寄件公司" },
      { "contactType": 2, "country": "CN", "province": "上海", "city": "上海", "address": "浦东新区张江", "mobile": "13900000000", "company": "收件公司" }
    ]
  }
  ```

## 完整 JSON 示例（节选）

- 下单（`EXP_RECE_CREATE_ORDER`，来自 `01.order.json`）：
  ```json
  {
  	"cargoDetails": [{
  		"amount": 308.0,
  		"count": 1.0,
  		"name": "君宝牌地毯",
  		"unit": "个",
  		"volume": 0.0,
  		"weight": 0.1
  	}],
  	"contactInfoList": [{
  		"address": "十堰市丹江口市公园路155号",
  		"city": "十堰市",
  		"company": "清雅轩保健品专营店",
  		"contact": "张三丰",
  		"contactType": 1,
  		"county": "武当山风景区",
  		"mobile": "17006805888",
  		"province": "湖北省"
  	}, {
  		"address": "湖北省襄阳市襄城区环城东路122号",
  		"city": "襄阳市",
  		"contact": "郭襄阳",
  		"county": "襄城区",
  		"contactType": 2,
  		"mobile": "18963828829",
  		"province": "湖北省"
  	}],
  	"customsInfo": {},
  	"expressTypeId": 1,
  	"extraInfoList": [],
  	"isOneselfPickup": 0,
  	"language": "zh-CN",
  	"monthlyCard": "7551234567",
  	"orderId": "QIAO-20200618-005",
  	"parcelQty": 1,
  	"payMethod": 1,
  	"totalWeight": 6
  }
  ```

- 路由查询（按运单号，`EXP_RECE_SEARCH_ROUTES`，来自 `05.route_query_by_MailNo.json`）：
  ```json
  {
  	"language": "0",
  	"trackingType": "1",
  	"trackingNumber": ["SF7444407228423"],
  	"methodType": "1"
  }
  ```

- 路由查询（按订单号，`EXP_RECE_SEARCH_ROUTES`，来自 `05.route_query_by_OrderNo.json`）：
  ```json
  {
  	"language": "0",
  	"trackingType": "2",
  	"trackingNumber": ["QIAO-20200605-003"],
  	"methodType": "1"
  }
  ```

- 申请子单（`EXP_RECE_GET_SUB_MAILNO`，来自 `07.sub.mailno.json`）：
  ```json
  {
      "orderId": "QIAO-20200605-003",
      "parcelQty": 2
  }
  ```

- 逆向单创建（`EXP_RECE_CREATE_REVERSE_ORDER`，来自 `13.reverse_order.json`）：
  ```json
  {
  	"language": "zh_CN",
  	"orderId": "F2_20200604180946",
  	"cargoDetails": [{
  		"amount": 100.5111,
  		"count": 2.365,
  		"currency": "HKD",
  		"cargoSku": "AAAA004",
  		"name": "护肤品1",
  		"unit": "个",
  		"weight": 6.1
  	}],
  	"serviceList": [{
  		"name": "INSURE",
  		"value": "3000"
  	}],
  	"contactInfoList": [{
  			"address": "软件产业基地11栋",
  			"city": "深圳市",
  			"contact": "小曾",
  			"contactType": 1,
  			"country": "CN",
  			"county": "南山区",
  			"mobile": "13480155048",
  			"postCode": "580058",
  			"province": "广东省",
  			"tel": "4006789888"
  		},
  		{
  			"address": "广东省广州市白云区湖北大厦",
  			"city": "",
  			"company": "顺丰速运",
  			"contact": "小邱",
  			"contactType": 2,
  			"country": "CN",
  			"county": "",
  			"mobile": "13925211148",
  			"postCode": "580058",
  			"province": "",
  			"tel": "18688806057"
  		}
  	],
  	"monthlyCard": "",
  	"payMethod": 1,
  	"expressTypeId": 1,
  	"volume": 8.0,
  	"sendStartTm": "2020-03-10 10:00:00",
  	"refundAmount": 8.0,
  	"isCheck": "1",
  	"bizTemplateCode": "2020312_order"

  }
  ```

- 妥投通知（`EXP_RECE_DELIVERY_NOTICE`，来自 `15.delivery_notice.json`）：
  ```json
  {
      "waybillNo":"SF7444400067318",
      "dataType":"71",
      "language":"zh-cn"
  }
  ```

- 改址拦截（`EXP_RECE_WANTED_INTERCEPT`，来自 `18.wanted_intercept.json`）：
  ```json
  {
      "cancel":false,
      "deliverDate":"2020-04-30",
      "deliverTimeMax":"12:00",
      "deliverTimeMin":"09:00",
      "monthlyCardNo":"9999999999",
      "newDestAddress":{
          "address":"粤海街道海阔天空雅居B栋16B",
          "city":"深圳市",
          "contact":"牟星",
          "county":"南山区",
          "phone":"15922226666",
          "province":"广东省"
      },
      "payMode":"3",
      "role":"1",
      "serviceCode":"7",
      "waybillNo":"SF444201931741"
  }
  ```

- 运单号校验（`EXP_RECE_VALIDATE_WAYBILLNO`，来自 `24.validate_waybillno.json`）：
  ```json
  {
      "waybillNo":"SF1040275268927"
  }
  ```

- 承诺时效查询（`EXP_RECE_SEARCH_PROMITM`，来自 `22.search_promitm.json`）：
  ```json
  {
    "searchNo": "SF1000181136590",
    "checkType": 2,
    "checkNos": ["5125690150", "18218723913"]
  }
  ```

> 更多完整模板现已汇总至：`docs/examples/` 目录（如下索引）。

## 示例索引（docs/examples）

- 订单创建（EXP_RECE_CREATE_ORDER）：`docs/examples/01.order.json`
- 订单查询（EXP_RECE_SEARCH_ORDER_RESP）：`docs/examples/02.order.query.json`
- 订单确认/取消（EXP_RECE_UPDATE_ORDER）：`docs/examples/03.order.confirm.json`
- 订单筛选（EXP_RECE_FILTER_ORDER_BSP）：`docs/examples/04.order.filter.json`
- 路由查询-按运单号（EXP_RECE_SEARCH_ROUTES）：`docs/examples/05.route_query_by_MailNo.json`
- 路由查询-按订单号（EXP_RECE_SEARCH_ROUTES）：`docs/examples/05.route_query_by_OrderNo.json`
- 申请子单（EXP_RECE_GET_SUB_MAILNO）：`docs/examples/07.sub.mailno.json`
- 运单费用查询（EXP_RECE_QUERY_SFWAYBILL）：`docs/examples/09.waybills_fee.json`
- 路由注册（EXP_RECE_REGISTER_ROUTE）：`docs/examples/12.register_route.json`
- 逆向单创建（EXP_RECE_CREATE_REVERSE_ORDER）：`docs/examples/13.reverse_order.json`
- 逆向单取消（EXP_RECE_CANCEL_REVERSE_ORDER）：`docs/examples/14.cancel_reverse_order.json`
- 妥投通知（EXP_RECE_DELIVERY_NOTICE）：`docs/examples/15.delivery_notice.json`
- 运单影像注册（EXP_RECE_REGISTER_WAYBILL_PICTURE）：`docs/examples/16.register_waybill_picture.json`
- 改址拦截（EXP_RECE_WANTED_INTERCEPT）：`docs/examples/18.wanted_intercept.json`
- 预计时效查询（EXP_RECE_QUERY_DELIVERTM）：`docs/examples/19.query_delivertm.json`
- 云打印运单（COM_RECE_CLOUD_PRINT_WAYBILLS）：`docs/examples/20.cloud_print_waybills.json`
- 路由上传（EXP_RECE_UPLOAD_ROUTE）：`docs/examples/21.upload_route.json`
- 承诺时效查询（EXP_RECE_SEARCH_PROMITM）：`docs/examples/22.search_promitm.json`
- 上门揽收时段查询（EXP_EXCE_CHECK_PICKUP_TIME）：`docs/examples/23.check_pickup_time.json`
- 运单号校验（EXP_RECE_VALIDATE_WAYBILLNO）：`docs/examples/24.validate_waybillno.json`

## 即用代码片段

以下示例假设你已创建 `$client` 与 `$api`（参见“快速开始”），直接读取 `docs/examples/*.json` 作为 payload 并发起调用。

- 订单创建（EXP_RECE_CREATE_ORDER）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/01.order.json'), true);
  $res = $api->createOrder($payload);
  // 或：
  $res = $client->call('EXP_RECE_CREATE_ORDER', $payload);
  ```

- 订单查询（EXP_RECE_SEARCH_ORDER_RESP）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/02.order.query.json'), true);
  $res = $api->searchOrderResp($payload);
  // 或：
  $res = $client->call('EXP_RECE_SEARCH_ORDER_RESP', $payload);
  ```

- 订单确认/取消（EXP_RECE_UPDATE_ORDER）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/03.order.confirm.json'), true);
  $res = $api->updateOrder($payload);
  // 或：
  $res = $client->call('EXP_RECE_UPDATE_ORDER', $payload);
  ```

- 订单筛选（EXP_RECE_FILTER_ORDER_BSP）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/04.order.filter.json'), true);
  $res = $api->filterOrderBsp($payload);
  // 或：
  $res = $client->call('EXP_RECE_FILTER_ORDER_BSP', $payload);
  ```

- 路由查询-按运单号（EXP_RECE_SEARCH_ROUTES）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/05.route_query_by_MailNo.json'), true);
  $res = $api->searchRoutesByMailNo($payload);
  // 或：
  $res = $client->call('EXP_RECE_SEARCH_ROUTES', $payload);
  ```

- 路由查询-按订单号（EXP_RECE_SEARCH_ROUTES）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/05.route_query_by_OrderNo.json'), true);
  $res = $api->searchRoutesByOrderNo($payload);
  // 或：
  $res = $client->call('EXP_RECE_SEARCH_ROUTES', $payload);
  ```

- 申请子单（EXP_RECE_GET_SUB_MAILNO）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/07.sub.mailno.json'), true);
  $res = $api->getSubMailNo($payload);
  // 或：
  $res = $client->call('EXP_RECE_GET_SUB_MAILNO', $payload);
  ```

- 运单费用查询（EXP_RECE_QUERY_SFWAYBILL）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/09.waybills_fee.json'), true);
  $res = $api->querySfWaybillFees($payload);
  // 或：
  $res = $client->call('EXP_RECE_QUERY_SFWAYBILL', $payload);
  ```

- 路由注册（EXP_RECE_REGISTER_ROUTE）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/12.register_route.json'), true);
  $res = $api->registerRoute($payload);
  // 或：
  $res = $client->call('EXP_RECE_REGISTER_ROUTE', $payload);
  ```

- 逆向单创建（EXP_RECE_CREATE_REVERSE_ORDER）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/13.reverse_order.json'), true);
  $res = $api->createReverseOrder($payload);
  // 或：
  $res = $client->call('EXP_RECE_CREATE_REVERSE_ORDER', $payload);
  ```

- 逆向单取消（EXP_RECE_CANCEL_REVERSE_ORDER）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/14.cancel_reverse_order.json'), true);
  $res = $api->cancelReverseOrder($payload);
  // 或：
  $res = $client->call('EXP_RECE_CANCEL_REVERSE_ORDER', $payload);
  ```

- 妥投通知（EXP_RECE_DELIVERY_NOTICE）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/15.delivery_notice.json'), true);
  $res = $api->deliveryNotice($payload);
  // 或：
  $res = $client->call('EXP_RECE_DELIVERY_NOTICE', $payload);
  ```

- 运单影像注册（EXP_RECE_REGISTER_WAYBILL_PICTURE）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/16.register_waybill_picture.json'), true);
  $res = $api->registerWaybillPicture($payload);
  // 或：
  $res = $client->call('EXP_RECE_REGISTER_WAYBILL_PICTURE', $payload);
  ```

- 改址拦截（EXP_RECE_WANTED_INTERCEPT）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/18.wanted_intercept.json'), true);
  $res = $api->wantedIntercept($payload);
  // 或：
  $res = $client->call('EXP_RECE_WANTED_INTERCEPT', $payload);
  ```

- 预计时效查询（EXP_RECE_QUERY_DELIVERTM）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/19.query_delivertm.json'), true);
  $res = $api->queryDeliverTm($payload);
  // 或：
  $res = $client->call('EXP_RECE_QUERY_DELIVERTM', $payload);
  ```

- 云打印运单（COM_RECE_CLOUD_PRINT_WAYBILLS）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/20.cloud_print_waybills.json'), true);
  $res = $api->cloudPrintWaybills($payload);
  // 或：
  $res = $client->call('COM_RECE_CLOUD_PRINT_WAYBILLS', $payload);
  ```

- 路由上传（EXP_RECE_UPLOAD_ROUTE）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/21.upload_route.json'), true);
  $res = $api->uploadRoute($payload);
  // 或：
  $res = $client->call('EXP_RECE_UPLOAD_ROUTE', $payload);
  ```

- 承诺时效查询（EXP_RECE_SEARCH_PROMITM）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/22.search_promitm.json'), true);
  $res = $api->searchPromitm($payload);
  // 或：
  $res = $client->call('EXP_RECE_SEARCH_PROMITM', $payload);
  ```

- 上门揽收时段查询（EXP_EXCE_CHECK_PICKUP_TIME）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/23.check_pickup_time.json'), true);
  $res = $api->checkPickupTime($payload);
  // 或：
  $res = $client->call('EXP_EXCE_CHECK_PICKUP_TIME', $payload);
  ```

- 运单号校验（EXP_RECE_VALIDATE_WAYBILLNO）
  ```php
  $payload = json_decode(file_get_contents('docs/examples/24.validate_waybillno.json'), true);
  $res = $api->validateWaybillNo($payload);
  // 或：
  $res = $client->call('EXP_RECE_VALIDATE_WAYBILLNO', $payload);
  ```

## 返回与错误处理

- 成功时返回为解码后的数组；当响应非 JSON 时返回 `{ "raw": "..." }`
- 常见错误：重复下单、签名错误、参数缺失等，请根据返回的 `errorCode`/`message` 做业务处理

## 说明与实现细节

- 签名算法：`base64_encode(md5(urlencode(msgData + timestamp + checkword), true))`
- `payload` 使用 `JSON_UNESCAPED_UNICODE` 编码为 `msgData`
- 通过 `env` 选择沙盒/生产地址，亦可通过配置覆盖 URL

## 与 Webman 集成（可选）

- 直接在 Service 中依赖 `Wangwei\SfExpress\Client` 或注入 `ClientInterface`
- 如使用容器/DI，可绑定接口到实现后在业务内注入使用

## 版本与许可证

- 版本遵循语义化版本（SemVer）：`MAJOR.MINOR.PATCH`
- 当前许可证：`proprietary`（若公开发布，建议使用 `MIT` 或其他开源协议）

## 贡献

- 欢迎提交 Issue 与 PR，完善更多便捷方法与字段校验
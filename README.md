# DianSan DouDian SDK

> Language: **中文** | [English](./README.en.md)

点三开放平台抖店商品 API 的 PHP SDK。

面向需要对接点三网关的业务系统，用工厂模式创建统一客户端，封装商品发布、编辑与异步批量上下架，减少重复的签名、请求拼装与结果解析工作。

## 关于本 SDK

| 运行环境 | 技术栈 |
| --- | --- |
| PHP 7.4 – 8.x | Composer · Guzzle · 工厂模式 |

**核心能力：**

- **商品发布** — 封装 `ds.dy.item.add`，配合类目推荐、规则查询、属性与图片上传等前置能力
- **商品编辑** — 封装 `ds.dy.item.edit`，支持草稿保存与提审（`commit`）
- **异步批量上下架** — 封装全渠道店铺 SKU 批量上架 / 下架（单次最多 50 个）
- **统一结果处理** — `ApiResult` 解析业务成功 / 失败、消息与数据
- **工厂创建客户端** — `DouDianFactory::create` / `fromClient`

包名：`digital-engine-tech/diansan-doudian-sdk`

## 架构

| 层级 | 类 | 职责 |
| --- | --- | --- |
| 工厂 | `DianSan\DouDianFactory` | 创建已配置客户端 |
| 门面 | `DianSan\DouDian` | 业务入口（发布 / 编辑 / 上下架） |
| 模块 | `ItemApi` / `ShelfApi` / `PictureApi` | 网关方法分组 |
| 传输 | `Client` + `Signer` | HTTP 请求与签名 |
| 结果 | `Support\ApiResult` | 统一成功 / 失败解析 |

## 环境要求

| 项目 | 版本 |
| --- | --- |
| PHP | `>=7.4 <9.0` |
| `guzzlehttp/guzzle` | `^7.8` |

## 安装

```bash
composer require digital-engine-tech/diansan-doudian-sdk
```

## 创建客户端

```php
use DianSan\DouDianFactory;
use DianSan\Support\ApiResult;

$doudian = DouDianFactory::create([
    'gateway' => 'http://open3rd.product.diansan.com/open/oms/router', // 可选
    'app_key' => 'your_app_key',
    'app_secret' => 'your_app_secret',
    'pos_code' => 'your_pos_code',
]);
```

工厂方法：

```php
DouDianFactory::create($config);     // array|Config
DouDianFactory::fromClient($client); // 已有 Client
```

## 发布商品

```php
$raw = $doudian->publish([
    // product.addV2 业务字段
]);
$result = ApiResult::from($raw);

if ($result->bizOk()) {
    $productId = $result->data()['productId'] ?? null;
}
```

常用前置：

```php
$doudian->item()->publishPreCheck([...]);
$doudian->item()->getRecommendCategory(['name' => '...', 'scene' => 'category_infer']);
$doudian->item()->getCatRule(['category_id' => 123]);
$doudian->item()->getCatProps(['category_leaf_id' => 123]);
$doudian->picture()->upload('https://example.com/a.jpg', 'main.jpg');
```

## 编辑商品

平台要求必须传 `commit`。

```php
$doudian->edit([
    'product_id' => 123456789,
    'commit' => false, // true=提审；false=保存类更新
    'name' => '新标题',
]);
```

## 批量上下架（异步）

`id` 为点三文档中的**店铺商品 Id**，单次最多 **50** 个。

```php
$doudian->batchShelfOff(['111', '222']);
$doudian->batchShelfOn(['111', '222']);
```

实时单商品（视平台支持情况）：

```php
$doudian->setShelfStatus('111', 'OFF');
$doudian->setShelfStatus('111', 'ON');
```

## 响应处理

```php
$result = $doudian->result($raw);
// 或：ApiResult::from($raw);

$result->bizOk();
$result->message();
$result->data();
$result->toArray();
```

## 接口对照

| 方法 | 点三 method |
| --- | --- |
| `publish` | `ds.dy.item.add` |
| `edit` | `ds.dy.item.edit` |
| `batchShelfOn` | `ds.omni.erp.pos.sku.shelf.on` |
| `batchShelfOff` | `ds.omni.erp.pos.sku.shelf.off` |
| `setShelfStatus` | `ds.omni.erp.mall.spu.status.update` |

## 原始调用

```php
$doudian->raw('ds.dy.item.edit', [
    'posCode' => $doudian->config()->posCode,
    'request' => ['product_id' => 123, 'commit' => false],
]);
```

## 许可证

MIT

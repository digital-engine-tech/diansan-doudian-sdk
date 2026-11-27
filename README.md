# DianSan DouDian SDK

> Language: **中文** | [English](./README.en.md)

本项目是基于长沙市点三信息技术有限公司「[点三电商开放平台](https://www.diansan.com/)」API 开发的抖音电商商品管理 SDK，面向企业级 ERP、WMS 及自研电商管理系统，提供抖店商品数据查询、商品创建、编辑及管理等接口封装能力。通过点三开放平台授权接入，帮助企业在不直接申请抖店官方「商家后台系统」应用资质的情况下，集成已有授权范围内的抖店商品管理能力。

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

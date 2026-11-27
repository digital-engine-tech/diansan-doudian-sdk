# DianSan DouDian SDK

> Language: **English** | [中文](./README.md)

This project is a Douyin e-commerce product management SDK built on the 「[DianSan E-commerce Open Platform](https://www.diansan.com/)」 APIs from Changsha DianSan Information Technology Co., Ltd. It targets enterprise ERP, WMS, and custom e-commerce systems, and wraps DouDian product query, create, edit, and management APIs. Through DianSan Open Platform authorization, businesses can integrate DouDian product management within the granted scope without applying for DouDian's official 「Merchant Backend System」 application credentials themselves.

## Install

```bash
composer require digital-engine-tech/diansan-doudian-sdk
```

## Create a client

```php
use DianSan\DouDianFactory;
use DianSan\Support\ApiResult;

$doudian = DouDianFactory::create([
    'gateway' => 'http://open3rd.product.diansan.com/open/oms/router', // optional
    'app_key' => 'your_app_key',
    'app_secret' => 'your_app_secret',
    'pos_code' => 'your_pos_code',
]);
```

Factory methods:

```php
DouDianFactory::create($config);     // array|Config
DouDianFactory::fromClient($client); // existing Client
```

## Publish a product

```php
$raw = $doudian->publish([
    // product.addV2 business fields
]);
$result = ApiResult::from($raw);

if ($result->bizOk()) {
    $productId = $result->data()['productId'] ?? null;
}
```

Common prep calls:

```php
$doudian->item()->publishPreCheck([...]);
$doudian->item()->getRecommendCategory(['name' => '...', 'scene' => 'category_infer']);
$doudian->item()->getCatRule(['category_id' => 123]);
$doudian->item()->getCatProps(['category_leaf_id' => 123]);
$doudian->picture()->upload('https://example.com/a.jpg', 'main.jpg');
```

## Edit a product

The platform requires `commit`.

```php
$doudian->edit([
    'product_id' => 123456789,
    'commit' => false, // true = submit for review; false = save-style update
    'name' => 'New title',
]);
```

## Batch shelf on/off (async)

`id` is the **store product Id** in DianSan docs. Max **50** per call.

```php
$doudian->batchShelfOff(['111', '222']);
$doudian->batchShelfOn(['111', '222']);
```

Realtime single product (if the platform supports it):

```php
$doudian->setShelfStatus('111', 'OFF');
$doudian->setShelfStatus('111', 'ON');
```

## Response handling

```php
$result = $doudian->result($raw);
// or: ApiResult::from($raw);

$result->bizOk();
$result->message();
$result->data();
$result->toArray();
```

## API mapping

| Method | DianSan method |
| --- | --- |
| `publish` | `ds.dy.item.add` |
| `edit` | `ds.dy.item.edit` |
| `batchShelfOn` | `ds.omni.erp.pos.sku.shelf.on` |
| `batchShelfOff` | `ds.omni.erp.pos.sku.shelf.off` |
| `setShelfStatus` | `ds.omni.erp.mall.spu.status.update` |

## Raw call

```php
$doudian->raw('ds.dy.item.edit', [
    'posCode' => $doudian->config()->posCode,
    'request' => ['product_id' => 123, 'commit' => false],
]);
```

## License

MIT

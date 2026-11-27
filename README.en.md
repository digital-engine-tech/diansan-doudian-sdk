# diansan-doudian-sdk

> Language: **English** | [中文](./README.md)

PHP SDK for DianSan Open Platform DouDian (Douyin Shop) product APIs.

Built for business systems that integrate with the DianSan gateway. A factory creates a unified client that covers product publish, edit, and async batch shelf on/off, so you spend less time on signing, request assembly, and response parsing.

## About

| Runtime | Stack |
| --- | --- |
| PHP 7.4 – 8.x | Composer · Guzzle · Factory pattern |

**Core capabilities:**

- **Product publish** — wraps `ds.dy.item.add`, with category recommend, rule lookup, attributes, and image upload helpers
- **Product edit** — wraps `ds.dy.item.edit`, supports draft save and submit (`commit`)
- **Async batch shelf** — omni-channel store SKU batch on/off (max 50 per call)
- **Unified results** — `ApiResult` parses business success / failure, message, and data
- **Factory client** — `DouDianFactory::create` / `fromClient`

Package: `digital-engine-tech/diansan-doudian-sdk`

## Architecture

| Layer | Class | Role |
| --- | --- | --- |
| Factory | `DianSan\DouDianFactory` | Build a configured client |
| Facade | `DianSan\DouDian` | Business entry (publish / edit / shelf) |
| Modules | `ItemApi` / `ShelfApi` / `PictureApi` | Gateway method groups |
| Transport | `Client` + `Signer` | HTTP request and signing |
| Result | `Support\ApiResult` | Unified success / failure parsing |

## Requirements

| Item | Version |
| --- | --- |
| PHP | `>=7.4 <9.0` |
| `guzzlehttp/guzzle` | `^7.8` |

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

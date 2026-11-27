<?php

declare(strict_types=1);

namespace DianSan;

use DianSan\Api\ItemApi;
use DianSan\Api\PictureApi;
use DianSan\Api\ShelfApi;
use DianSan\Support\ApiResult;

/**
 * DianSan Douyin Shop product client.
 *
 * Create instances via {@see DouDianFactory}.
 */
final class DouDian
{
    /** @var Client */
    private $client;

    /** @var ItemApi */
    private $item;

    /** @var ShelfApi */
    private $shelf;

    /** @var PictureApi */
    private $picture;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->item = new ItemApi($client);
        $this->shelf = new ShelfApi($client);
        $this->picture = new PictureApi($client);
    }

    public function client(): Client
    {
        return $this->client;
    }

    public function config(): Config
    {
        return $this->client->getConfig();
    }

    public function item(): ItemApi
    {
        return $this->item;
    }

    public function shelf(): ShelfApi
    {
        return $this->shelf;
    }

    public function picture(): PictureApi
    {
        return $this->picture;
    }

    /**
     * Publish a product (ds.dy.item.add).
     *
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public function publish(array $request): array
    {
        return $this->item->add($request);
    }

    /**
     * Edit a product (ds.dy.item.edit). `product_id` and `commit` are required.
     *
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public function edit(array $request): array
    {
        return $this->item->edit($request);
    }

    /**
     * Async batch shelf-on (ds.omni.erp.pos.sku.shelf.on). Max 50 IDs.
     *
     * @param array<int, string|int> $shopItemIds
     * @return array<string, mixed>
     */
    public function batchShelfOn(array $shopItemIds): array
    {
        return $this->shelf->batchOn($shopItemIds);
    }

    /**
     * Async batch shelf-off (ds.omni.erp.pos.sku.shelf.off). Max 50 IDs.
     *
     * @param array<int, string|int> $shopItemIds
     * @return array<string, mixed>
     */
    public function batchShelfOff(array $shopItemIds): array
    {
        return $this->shelf->batchOff($shopItemIds);
    }

    /**
     * Real-time shelf status update (ds.omni.erp.mall.spu.status.update).
     *
     * @return array<string, mixed>
     */
    public function setShelfStatus(string $refProductId, string $status): array
    {
        return $this->shelf->updateStatus($refProductId, $status);
    }

    /**
     * Call a raw DianSan gateway method.
     *
     * @param array<string, mixed> $bizParams
     * @return array<string, mixed>
     */
    public function raw(string $method, array $bizParams): array
    {
        return $this->client->request($method, $bizParams);
    }

    /**
     * Wrap a raw response as ApiResult.
     *
     * @param array<string, mixed> $raw
     */
    public function result(array $raw): ApiResult
    {
        return ApiResult::from($raw);
    }
}

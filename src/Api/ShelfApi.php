<?php

declare(strict_types=1);

namespace DianSan\Api;

use DianSan\Client;
use InvalidArgumentException;

/**
 * Shop product shelf APIs (ds.omni.erp.*).
 */
final class ShelfApi
{
    private const BATCH_LIMIT = 50;

    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array<int, string|int> $shopItemIds
     * @return array<string, mixed>
     */
    public function batchOn(array $shopItemIds): array
    {
        return $this->client->request(
            'ds.omni.erp.pos.sku.shelf.on',
            ['content' => $this->toContent($shopItemIds)]
        );
    }

    /**
     * @param array<int, string|int> $shopItemIds
     * @return array<string, mixed>
     */
    public function batchOff(array $shopItemIds): array
    {
        return $this->client->request(
            'ds.omni.erp.pos.sku.shelf.off',
            ['content' => $this->toContent($shopItemIds)]
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function updateStatus(string $refProductId, string $status): array
    {
        $status = strtoupper($status);
        if (!in_array($status, ['ON', 'OFF'], true)) {
            throw new InvalidArgumentException('status must be ON or OFF');
        }

        return $this->client->request('ds.omni.erp.mall.spu.status.update', [
            'posCode' => $this->client->getConfig()->posCode,
            'refProductId' => $refProductId,
            'status' => $status,
        ]);
    }

    /**
     * @param array<int, string|int> $shopItemIds
     * @return array<int, array{id: string}>
     */
    private function toContent(array $shopItemIds): array
    {
        if ($shopItemIds === []) {
            throw new InvalidArgumentException('shopItemIds must not be empty');
        }
        if (count($shopItemIds) > self::BATCH_LIMIT) {
            throw new InvalidArgumentException('shopItemIds exceeds limit of ' . self::BATCH_LIMIT);
        }

        $content = [];
        foreach ($shopItemIds as $id) {
            $id = trim((string) $id);
            if ($id === '') {
                throw new InvalidArgumentException('shop item id must not be empty');
            }
            $content[] = ['id' => $id];
        }

        return $content;
    }
}

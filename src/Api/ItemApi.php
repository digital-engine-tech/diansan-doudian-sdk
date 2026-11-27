<?php

declare(strict_types=1);

namespace DianSan\Api;

use DianSan\Client;

/**
 * Douyin product publish / edit APIs (ds.dy.item.*).
 */
final class ItemApi
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public function add(array $request): array
    {
        return $this->call('ds.dy.item.add', $request);
    }

    /**
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public function edit(array $request): array
    {
        return $this->call('ds.dy.item.edit', $request);
    }

    /**
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public function publishPreCheck(array $request): array
    {
        return $this->call('ds.dy.item.publish.pre.check', $request);
    }

    /**
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public function getRecommendCategory(array $request): array
    {
        return $this->call('ds.dy.item.recommend.category.get', $request);
    }

    /**
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public function getCatRule(array $request): array
    {
        return $this->call('ds.dy.item.cat.rule.get', $request);
    }

    /**
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public function getCatProps(array $request): array
    {
        return $this->call('ds.dy.item.cat.props.get', $request);
    }

    /**
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    private function call(string $method, array $request): array
    {
        $config = $this->client->getConfig();

        return $this->client->request($method, [
            'posCode' => $config->posCode,
            'request' => $request,
        ]);
    }
}

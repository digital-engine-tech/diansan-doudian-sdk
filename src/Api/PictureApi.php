<?php

declare(strict_types=1);

namespace DianSan\Api;

use DianSan\Client;

final class PictureApi
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return array<string, mixed>
     */
    public function upload(
        string $imgUrl,
        string $name,
        int $folderId = 0,
        bool $needDistinct = true
    ): array {
        $config = $this->client->getConfig();

        return $this->client->request('ds.dy.picture.upload', [
            'posCode' => $config->posCode,
            'folderId' => $folderId,
            'imgUrl' => $imgUrl,
            'name' => $name,
            'needDistinct' => $needDistinct,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function getDetail(string $materialId): array
    {
        $config = $this->client->getConfig();

        return $this->client->request('ds.dy.picture.detail.get', [
            'posCode' => $config->posCode,
            'materialId' => $materialId,
        ]);
    }
}

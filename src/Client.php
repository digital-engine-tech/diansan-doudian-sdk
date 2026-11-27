<?php

declare(strict_types=1);

namespace DianSan;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

final class Client
{
    /** @var Config */
    private $config;

    /** @var HttpClient */
    private $http;

    public function __construct(Config $config, ?HttpClient $http = null)
    {
        $this->config = $config;
        $this->http = $http !== null ? $http : new HttpClient([
            'timeout' => 30,
            'http_errors' => false,
        ]);
    }

    /**
     * @param array<string, mixed> $bizParams
     * @return array<string, mixed>
     */
    public function request(string $method, array $bizParams): array
    {
        $body = json_encode($bizParams, JSON_UNESCAPED_UNICODE);
        if ($body === false) {
            throw new \RuntimeException('Failed to encode business params as JSON');
        }

        $timestamp = (string) (int) (microtime(true) * 1000);

        $publicParams = [
            'appKey' => $this->config->appKey,
            'method' => $method,
            'timestamp' => $timestamp,
        ];

        $sign = Signer::sign($this->config->appSecret, $publicParams, $body);

        $query = $publicParams + ['sign' => $sign];

        try {
            $response = $this->http->post($this->config->gateway, [
                'query' => $query,
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Accept' => 'application/json',
                ],
                'body' => $body,
            ]);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('HTTP request failed: ' . $e->getMessage(), 0, $e);
        }

        $status = $response->getStatusCode();
        $raw = (string) $response->getBody();

        if ($status < 200 || $status >= 300) {
            throw new \RuntimeException('HTTP ' . $status . ': ' . $raw);
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            throw new \RuntimeException('Invalid JSON response: ' . $raw);
        }

        /** @var array<string, mixed> $decoded */
        return $decoded;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }
}

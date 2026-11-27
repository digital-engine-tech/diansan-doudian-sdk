<?php

declare(strict_types=1);

namespace DianSan;

final class Config
{
    /** @var string */
    public $gateway;

    /** @var string */
    public $appKey;

    /** @var string */
    public $appSecret;

    /** @var string */
    public $posCode;

    public function __construct(string $gateway, string $appKey, string $appSecret, string $posCode)
    {
        $this->gateway = $gateway;
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->posCode = $posCode;
    }

    /**
     * @param array<string, mixed> $config
     */
    public static function fromArray(array $config): self
    {
        $gateway = (string) (isset($config['gateway']) ? $config['gateway'] : 'http://open3rd.product.diansan.com/open/oms/router');
        $appKey = (string) (isset($config['app_key']) ? $config['app_key'] : (isset($config['appKey']) ? $config['appKey'] : ''));
        $appSecret = (string) (isset($config['app_secret']) ? $config['app_secret'] : (isset($config['appSecret']) ? $config['appSecret'] : ''));
        $posCode = (string) (isset($config['pos_code']) ? $config['pos_code'] : (isset($config['posCode']) ? $config['posCode'] : ''));

        if ($appKey === '' || $appSecret === '' || $posCode === '') {
            throw new \InvalidArgumentException('app_key / app_secret / pos_code are required');
        }

        return new self($gateway, $appKey, $appSecret, $posCode);
    }
}

<?php

declare(strict_types=1);

namespace DianSan;

/**
 * Factory for {@see DouDian} clients.
 */
final class DouDianFactory
{
    /**
     * Create a client from Config or an associative array.
     *
     * @param Config|array<string, mixed> $config
     */
    public static function create($config): DouDian
    {
        if (is_array($config)) {
            $config = Config::fromArray($config);
        } elseif (!$config instanceof Config) {
            throw new \InvalidArgumentException('config must be Config or array');
        }

        return new DouDian(new Client($config));
    }

    /**
     * Create a client from an existing low-level Client.
     */
    public static function fromClient(Client $client): DouDian
    {
        return new DouDian($client);
    }
}

<?php

declare(strict_types=1);

namespace DianSan;

final class Signer
{
    /**
     * 后端 API 签名：公共参数 ASCII 排序 KeyValue + 业务 JSON，前后加 AppSecret，MD5 大写。
     *
     * @param array<string, string> $publicParams 不含 sign
     */
    public static function sign(string $appSecret, array $publicParams, string $body): string
    {
        ksort($publicParams);

        $plain = '';
        foreach ($publicParams as $key => $value) {
            $plain .= $key . $value;
        }
        $plain .= $body;

        $toHash = $appSecret . $plain . $appSecret;

        return strtoupper(md5($toHash));
    }
}

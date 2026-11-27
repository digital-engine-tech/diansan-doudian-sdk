<?php

declare(strict_types=1);

namespace DianSan\Support;

/**
 * Normalize DianSan gateway responses for callers.
 */
final class ApiResult
{
    /** @var array<string, mixed> */
    public $raw;

    /**
     * @param array<string, mixed> $raw
     */
    public function __construct(array $raw)
    {
        $this->raw = $raw;
    }

    /**
     * @param array<string, mixed> $raw
     */
    public static function from(array $raw): self
    {
        return new self($raw);
    }

    /**
     * @return array<string, mixed>
     */
    public function response(): array
    {
        $resp = isset($this->raw['response']) ? $this->raw['response'] : null;

        return is_array($resp) ? $resp : [];
    }

    public function gatewayOk(): bool
    {
        $resp = $this->response();
        $flag = (string) (isset($resp['flag']) ? $resp['flag'] : '');
        $code = isset($resp['code']) ? $resp['code'] : null;

        return $flag === 'success' && ((string) $code === '200' || $code === 200);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function content(): ?array
    {
        $resp = $this->response();
        $data = isset($resp['data']) && is_array($resp['data']) ? $resp['data'] : [];
        $content = isset($data['content']) ? $data['content'] : null;

        return is_array($content) ? $content : null;
    }

    public function bizOk(): bool
    {
        if (!$this->gatewayOk()) {
            return false;
        }
        $content = $this->content();
        if ($content === null) {
            return true;
        }
        if (array_key_exists('success', $content)) {
            return (bool) $content['success'];
        }
        $code = isset($content['code']) ? $content['code'] : null;

        return $code === null
            || $code === 10000
            || $code === '10000'
            || $code === 200
            || $code === '200';
    }

    public function message(): string
    {
        $resp = $this->response();
        $content = $this->content();
        if ($content === null) {
            $content = [];
        }

        $candidates = [
            isset($content['sub_msg']) ? $content['sub_msg'] : null,
            isset($content['msg']) ? $content['msg'] : null,
            isset($resp['sub_message']) ? $resp['sub_message'] : null,
            isset($resp['message']) ? $resp['message'] : null,
        ];

        foreach ($candidates as $msg) {
            if (is_string($msg) && $msg !== '') {
                return $msg;
            }
        }

        return $this->bizOk() ? 'ok' : 'unknown error';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function data(): ?array
    {
        $content = $this->content();
        if ($content === null) {
            return null;
        }
        $data = isset($content['data']) ? $content['data'] : null;

        return is_array($data) ? $data : $content;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $content = $this->content();
        if ($content === null) {
            $content = [];
        }

        return [
            'ok' => $this->bizOk(),
            'message' => $this->message(),
            'gateway_code' => isset($this->response()['code']) ? $this->response()['code'] : null,
            'biz_code' => isset($content['code']) ? $content['code'] : null,
            'data' => $this->data(),
            'raw' => $this->raw,
        ];
    }
}

<?php
declare(strict_types=1);

namespace Http;

readonly class Request
{
    public function __construct(
        public string $uri,
        public string $method,
        public array $query,
        public array $body,
        public string $rawBody,
    ) {}

    public static function fromGlobals(): self
    {
        return new self(
            uri: $_SERVER['REQUEST_URI'] ?? '/',
            method: $_SERVER['REQUEST_METHOD'] ?? 'GET',
            query: $_GET,
            body: $_POST,
            rawBody: file_get_contents('php://input') ?: '',
        );
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function json(): array
    {
        $data = json_decode($this->rawBody, true);
        return is_array($data) ? $data : [];
    }
}

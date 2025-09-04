<?php
declare(strict_types=1);

namespace Http;

class Response
{
    private int $status = 200;
    private array $headers = [];
    private string $body = '';

    public function setStatus(int $code): self
    {
        $this->status = $code;
        return $this;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setBody(string $content): self
    {
        $this->body = $content;
        return $this;
    }

    public function json(array $data, int $code = 200): void
    {
        $this->setStatus($code);
        $this->header('Content-Type', 'application/json; charset=utf-8');
        $this->setBody(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->send();
    }

    public function redirect(string $url, int $code = 302): void
    {
        $this->setStatus($code);
        $this->header('Location', $url);
        $this->send();
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
        echo $this->body;
    }
}

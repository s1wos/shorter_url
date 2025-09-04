<?php
declare(strict_types=1);

namespace Controllers;

use Core\Logger;
use Models\Url;
use Http\Request;
use Http\Response;

class ApiController
{
    public function __construct(private ?Url $model = null)
    {
        $this->model ??= new Url();
    }

    public function shorten(Request $request, Response $response): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if ($this->isRateLimited($ip)) {
            Logger::log("Rate limit exceeded for {$ip}");
            $response->json(['error' => 'Too many requests'], 429);
            return;
        }

        $data = $request->json();
        $url = $data['url'] ?? '';
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $code = $this->model->create($url);
            Logger::log("API shortened {$url} to {$code} by {$ip}");
            $response->json(['short' => BASE_URL . '/' . $code]);
        } else {
            Logger::log("API invalid URL from {$ip}: {$url}");
            $response->json(['error' => 'Некорректный URL'], 400);
        }
    }

    private function isRateLimited(string $ip, int $limit = 10, int $ttl = 60): bool
    {
        $dir = __DIR__ . '/../../storage/ratelimit';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $file = $dir . '/' . md5($ip);
        $data = ['count' => 0, 'time' => time()];
        if (is_file($file)) {
            $data = json_decode((string) file_get_contents($file), true) ?: $data;
            if ($data['time'] + $ttl <= time()) {
                $data = ['count' => 0, 'time' => time()];
            }
        }
        $data['count']++;
        file_put_contents($file, json_encode($data));

        return $data['count'] > $limit;
    }
}

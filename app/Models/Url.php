<?php
declare(strict_types=1);

namespace Models;

use Core\Model;

class Url extends Model
{
    private const CHARS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    public function create(string $url): string
    {
        if ($existing = $this->findByUrl($url)) {
            return $existing;
        }

        do {
            $code = $this->generateCode();
        } while ($this->codeExists($code));

        $stmt = $this->db->prepare('INSERT INTO urls (code, url) VALUES (:code, :url)');
        $stmt->execute(['code' => $code, 'url' => $url]);

        return $code;
    }

    public function findByCode(string $code): ?string
    {
        $stmt = $this->db->prepare('SELECT url FROM urls WHERE code = :code LIMIT 1');
        $stmt->execute(['code' => $code]);
        $row = $stmt->fetch();

        return $row['url'] ?? null;
    }

    public function findByUrl(string $url): ?string
    {
        $stmt = $this->db->prepare('SELECT code FROM urls WHERE url = :url LIMIT 1');
        $stmt->execute(['url' => $url]);
        $row = $stmt->fetch();

        return $row['code'] ?? null;
    }

    private function codeExists(string $code): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM urls WHERE code = :code LIMIT 1');
        $stmt->execute(['code' => $code]);

        return (bool) $stmt->fetchColumn();
    }

    private function generateCode(int $length = 6): string
    {
        $code = '';
        $max = strlen(self::CHARS) - 1;
        for ($i = 0; $i < $length; $i++) {
            $code .= self::CHARS[random_int(0, $max)];
        }

        return $code;
    }
}

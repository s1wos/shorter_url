<?php
declare(strict_types=1);

namespace Core;

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo 'Представление ' . htmlspecialchars($view) . ' не найдено';
        }
    }
}

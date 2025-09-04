<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/Core/Env.php';

Core\Env::load(__DIR__ . '/../.env');

// Настройки базы данных
define('DB_HOST', $_ENV['DB_HOST'] ?? 'mariadb');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'url_shortener');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? 'root');

// Базовый адрес приложения
define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://localhost:8080');

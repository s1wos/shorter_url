<?php
declare(strict_types=1);

require __DIR__ . '/../config/config.php';

session_start();

spl_autoload_register(function (string $class): void {
    $baseDir = __DIR__ . '/../app/';
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use Core\Router;
use Controllers\HomeController;
use Controllers\ApiController;
use Http\Request;
use Http\Response;

$router = new Router();
$router->get('/', [HomeController::class, 'index']);
$router->post('/shorten', [HomeController::class, 'shorten']);
$router->get('/{code}', [HomeController::class, 'redirect']);
$router->post('/api/shorten', [ApiController::class, 'shorten']);

$request = Request::fromGlobals();
$response = new Response();
$router->dispatch($request, $response);

<?php
declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Core\Logger;
use Models\Url;
use Http\Request;
use Http\Response;

class HomeController extends Controller
{
    public function __construct(private ?Url $model = null)
    {
        $this->model ??= new Url();
    }

    public function index(Request $request, Response $response): void
    {
        $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
        $this->render('home', ['csrf_token' => $_SESSION['csrf_token']]);
    }

    public function shorten(Request $request, Response $response): void
    {
        $token = $request->input('csrf_token');
        if (!$token || $token !== ($_SESSION['csrf_token'] ?? '')) {
            Logger::log('CSRF check failed');
            $response->setStatus(403)->setBody('Неверный CSRF токен')->send();
            return;
        }

        $url = $request->input('url', '');
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $code = $this->model->create($url);
            $short = BASE_URL . '/' . $code;
            Logger::log("Shortened $url to $code");
            $this->render('home', ['short' => $short, 'csrf_token' => $_SESSION['csrf_token']]);
        } else {
            Logger::log("Invalid URL: $url");
            $this->render('home', ['error' => 'Некорректный URL', 'csrf_token' => $_SESSION['csrf_token']]);
        }
    }

    public function redirect(Request $request, Response $response, string $code): void
    {
        if ($url = $this->model->findByCode($code)) {
            $response->redirect($url, 301);
            return;
        }
        $response->setStatus(404)->setBody('Не найдено')->send();
    }
}

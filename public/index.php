<?php

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Controllers\AuthController;
use App\Controllers\QrCodeController;
use App\Controllers\ResetPasswordController;

// Manual autoloading
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});

$request = new Request();
$response = new Response();
$router = new Router($request, $response);

$router->get('/', [QrCodeController::class, 'index']);
$router->post('/generate', [QrCodeController::class, 'generate']);
use App\Core\AuthMiddleware;

$router->get('/dashboard', [QrCodeController::class, 'dashboard']);
$router->addMiddleware('/dashboard', AuthMiddleware::class);
$router->post('/delete-qrcode', [QrCodeController::class, 'delete']);

$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/forgot-password', [ResetPasswordController::class, 'forgotPassword']);
$router->post('/forgot-password', [ResetPasswordController::class, 'forgotPassword']);
$router->get('/reset-password', [ResetPasswordController::class, 'resetPassword']);
$router->post('/reset-password', [ResetPasswordController::class, 'resetPassword']);

echo $router->resolve();

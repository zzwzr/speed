<?php

declare(strict_types=1);

use App\Controller\LoginController;
use App\Controller\TestController;
use App\Controller\UploadController;
use App\Controller\UserController;
use Hyperf\HttpServer\Router\Router;

Router::get('/api/v1/test', [TestController::class, 'test']);
Router::post('/api/v1/upload', [UploadController::class, 'upload']);

Router::addGroup('/api/v1/user/', function(){
    Router::post('register', [LoginController::class, 'register']);
    Router::post('login', [LoginController::class, 'login']);
});

Router::addGroup('/api/v1/user/', function(){
    Router::post('details', [UserController::class, 'details']);
}, [
    'middleware' => [
        \App\Middleware\JwtMiddleware::class,
    ],
]);
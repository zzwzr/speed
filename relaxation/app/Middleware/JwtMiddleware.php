<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Logger\UserLogger;
use App\Tools\JwtTool;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Response;

class JwtMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected JwtTool $jwtTool,
        protected ContainerInterface $container,
        protected Response $response,
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            if ($this->jwtTool->validateToken($request)) {
                return $handler->handle($request);
            }
        } catch (\Throwable $th) {
            return $this->response->json(['code' => 401, 'message' => '用户未授权！'])->withStatus(401);
        };
    }
}

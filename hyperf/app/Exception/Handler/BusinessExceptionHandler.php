<?php

namespace App\Exception\Handler;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use App\Exception\BusinessException;
use Fig\Http\Message\StatusCodeInterface;
use Hyperf\Context\Context;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * 处理程序中异常
 */
class BusinessExceptionHandler extends ExceptionHandler
{
    // 用于订单创建失败、用户已注册等都返回 200
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof BusinessException) {

            $this->stopPropagation();
            $request = Context::get(ServerRequestInterface::class);

            $data = json_encode([
                'code'      => $throwable->getCode(),
                'message'   => $throwable->getMessage(),
            ], JSON_UNESCAPED_UNICODE);

            return $response->withStatus(StatusCodeInterface::STATUS_OK)
                            ->withHeader('Content-type', 'application/json; charset=utf-8')
                            ->withHeader('Access-Control-Allow-Origin', $request->getHeaderLine('origin'))
                            ->withHeader('Access-Control-Allow-Credentials', 'true')
                            ->withHeader('Access-Control-Allow-Headers', 'Keep-Alive, User-Agent, Cache-Control, Content-Type, Authorization')
                            ->withBody(new SwooleStream($data));
        }
        return $response;
    }

    /**
     * 判断该异常处理器是否要对该异常进行处理
     */
    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}

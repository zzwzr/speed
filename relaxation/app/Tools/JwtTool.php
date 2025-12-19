<?php

declare(strict_types=1);

namespace App\Tools;

use App\Logger\UserLogger;
use HyperfExtension\Jwt\Contracts\JwtFactoryInterface;
use HyperfExtension\Jwt\Contracts\ManagerInterface;
use Hyperf\Context\Context;
use HyperfExtension\Jwt\Exceptions\JwtException;
use Psr\Http\Message\ServerRequestInterface;
use App\Model\User;
use Hyperf\Redis\Redis;

use function Hyperf\Support\env;

class JwtTool
{
    /**
     * @var \Hyperf\Redis\Redis
     */
    protected $redis;

    /**
     * @var \HyperfExt\Jwt\Contracts\ManagerInterface
     */
    protected $manager;

    /**
     * @var \HyperfExt\Jwt\Jwt
     */
    protected $jwt;


    public function __construct(
        ManagerInterface $manager,
        JwtFactoryInterface $jwtFactory,
    ) {
        $this->manager = $manager;
        $this->jwt = $jwtFactory->make();
        $this->redis = \Hyperf\Context\ApplicationContext::getContainer()->get(Redis::class);
    }

    /**
     * 生成JWT Token并返回，并将用户信息存入缓存。
     *
     * @param User $user 用户对象
     * @return string 生成的Token
     */
    public function generateToken(User $user): string
    {
        $token = $this->jwt->fromUser($user);

        // $userJson = json_encode($user);
        // $this->redis->setex($this->getCacheKey($token), (int) env('JWT_TTL', 3600), $userJson);

        return $token;
    }

    /**
     * 从请求中解析并验证JWT Token，且从缓存中获取用户信息。
     *
     * @param ServerRequestInterface $request 当前请求
     * @return bool 验证是否成功
     * @throws JwtException
     */
    public function validateToken(ServerRequestInterface $request): bool
    {
        $this->jwt->setRequest($request);

        if ($this->jwt->check()) {
            $token = strval($this->jwt->getToken());

            // 获取存储的JWT无状态信息
            // $payload = $this->jwt->parseToken()->getPayload();
            // var_dump($payload->get('id'), $payload->get('name'));

            $user = $this->getUserFromCache($token);

            if ($user) {
                Context::set('user', $user);
            }

            return true;
        }

        throw new JwtException('Token 已失效');
    }

    /**
     * 从缓存中获取用户信息。
     *
     * @param string $token JWT Token
     * @return string|null 返回缓存中的用户信息
     */
    // public function getUserFromCache(string $token): string
    // {
    //     return $this->redis->get($this->getCacheKey($token));
    // }

    /**
     * 获取缓存的键名。
     *
     * @param string $token JWT Token
     * @return string 缓存键名
     */
    protected function getCacheKey(string $token): string
    {
        return "jwt_user_{$token}";
    }

    /**
     * 刷新Token并返回新的Token。
     *
     * @param bool $forceForever 是否强制设置永久有效
     * @return string 刷新后的Token
     */
    public function refreshToken(bool $forceForever = false): string
    {
        return $this->jwt->refresh($forceForever);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Logger\UserLogger;
use App\Resource\Common\BaseResource;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class UserController
{
    public function details()
    {
        $user = \Hyperf\Context\Context::get('user');

        $userArray = json_decode(strval($user), true);
        return new BaseResource($userArray);
    }
}

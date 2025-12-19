<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use function Hyperf\Support\env;

return [
    'host' => env('NACOS_HOST', '127.0.0.1'),
    'port' => env('NACOS_PORT', 8848),
    'username' => env('NACOS_USERNAME', null),
    'password' => env('NACOS_PASSWORD', null),
    'guzzle' => [
        'config' => null,
    ],
];
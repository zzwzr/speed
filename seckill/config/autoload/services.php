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

return [
    'enable' => [
        'discovery' => true,
        'register' => true,
    ],
    'consumers' => [],
    'providers' => [],
    'drivers' => [
        'nacos' => [
            'host' => env('NACOS_HOST', '127.0.0.1'),
            'port' => env('NACOS_PORT', 8848),
            'username' => env('NACOS_USERNAME', null),
            'password' => env('NACOS_PASSWORD', null),
            'group_name' => env('NACOS_GROUP_NAME', ''),
            'namespace_id' => env('NACOS_NAMESPACE_ID', ''),
            'heartbeat' => 5,
        ]
    ],
];
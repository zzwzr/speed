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
        'register' => false,
    ],
    'consumers' => value(function () {
        $consumers = [];
        $services = [
            'SeckillService' => App\JsonRpcInterface\SeckillServiceInterface::class
        ];
        foreach ($services as $name => $interface) {
            $consumers[] = [
                'name' => $name,
                'service' => $interface,
                'protocol' => 'jsonrpc',
                'registry' => [
                   'protocol' => 'nacos',
                   'address' => 'http://'.env('NACOS_HOST').':'.env('NACOS_PORT'),
                ],
            ];
        }
        return $consumers;
    }),

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
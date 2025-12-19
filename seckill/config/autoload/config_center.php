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
use Hyperf\ConfigCenter\Mode;

use function Hyperf\Support\env;

return [
    'enable' => (bool) env('CONFIG_CENTER_ENABLE', false),
    'driver' => env('CONFIG_CENTER_DRIVER', 'nacos'),
    'mode' => env('CONFIG_CENTER_MODE', Mode::PROCESS),
    'drivers' => [
        'nacos' => [
            'driver' => Hyperf\ConfigNacos\NacosDriver::class,
            'merge_mode' => Hyperf\ConfigNacos\Constants::CONFIG_MERGE_OVERWRITE,
            'interval' => 3,
            'default_key' => 'nacos_config',
            'listener_config' => [
                'AMQP_MESSAGE_SECRET' => [
                    'tenant'   => env('NACOS_NAMESPACE_ID'),
                    'data_id'   => 'AMQP_MESSAGE_SECRET',
                    'group'     => 'DEFAULT_GROUP',
                    'type'      => 'text',
                ]
            ],
        ],
    ],
];

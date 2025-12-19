<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use Hyperf\Amqp\Result;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Contract\StdoutLoggerInterface;
use PhpAmqpLib\Message\AMQPMessage;

#[Consumer(exchange: 'seckill', routingKey: 'seckill', queue: 'seckills', name: "SeckillConsumer", nums: 1)]
class SeckillConsumer extends ConsumerMessage
{
    public function consumeMessage($data, AMQPMessage $message): Result
    {
        return Result::ACK;
    }
}
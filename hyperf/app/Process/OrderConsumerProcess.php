<?php

declare(strict_types=1);

namespace App\Process;

use Hyperf\AsyncQueue\Process\ConsumerProcess;

class OrderConsumerProcess extends ConsumerProcess
{
    protected string $queue = 'order';
}

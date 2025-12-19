<?php

namespace App\JsonRpcInterface;

interface SeckillServiceInterface
{
    public function push(array $data): array;
}
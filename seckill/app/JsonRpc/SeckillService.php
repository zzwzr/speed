<?php

namespace App\JsonRpc;

use App\Amqp\Producer\SeckillProducer;
use App\Controller\AbstractController;
use App\JsonRpcInterface\SeckillServiceInterface;
use Hyperf\RpcServer\Annotation\RpcService;
use Hyperf\Amqp\Producer;

#[RpcService(name: "SeckillService", protocol: "jsonrpc", server: "jsonrpc", publishTo: "nacos")]
class SeckillService extends AbstractController implements SeckillServiceInterface
{
    public function push(array $data): array
    {
        try {
            $secret = config('AMQP_MESSAGE_SECRET');
            if (!verifySign($data, $secret)) {
                return error('sign 验证失败！');
            }
            $message = new SeckillProducer($data);
            $producer = $this->container->get(Producer::class);
            $result = $producer->produce($message);

            return success($result);
        } catch (\Throwable $th) {
            //throw $th;
            return error();
        }
    }
}
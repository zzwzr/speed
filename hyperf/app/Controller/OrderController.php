<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Exception\SystemException;
use App\Logger\OrderLogger;
use App\Model\Good;
use App\Model\Order;
use App\Model\OrderItem;
use App\Request\Order\CreateRequest;
use App\Resource\Common\BaseResource;
use App\Service\OrderQueueService;
use Hyperf\Di\Annotation\Inject;

class OrderController
{
    #[Inject]
    protected OrderQueueService $service;

    public function create(CreateRequest $request)
    {
        $validated = $request->validated();
        try {
            $iteams = [];
            $totalAmount = 0;
            foreach ($validated['goods'] as $k => $v) {
                $good = Good::where('id', $v['id'])->first();

                $itemTotalAmount = round($good->price * $v['number'], 2);

                $totalAmount += $itemTotalAmount;
                $iteams[] = [
                    // 'order_id' => ,
                    'category_id'   => $good->category_id,
                    'brand_id'      => $good->brand_id,
                    'goods_id'      => $v['id'],
                    'goods_name'    => $good->name,
                    'goods_price'   => $good->price,
                    'number'        => $v['number'],
                    'total_amount'  => $itemTotalAmount
                ];
            }
            $user = json_decode(strval(\Hyperf\Context\Context::get('user')), true);

            $order = [
                'order_no'          => bin2hex(random_bytes(16)),
                'user_id'           => $user['id'],
                'total_amount'      => round($totalAmount, 2),
                'status'            => 1,
                'payment_status'    => 1,
                'address_id'        => $validated['address_id'],
                'address_json'      => ['id' => 1, 'mobile' => 13623311796, 'address' => '唱出超声波']
            ];

            $this->service->push(['order' => $order, 'iteams' => $iteams]);
            return new BaseResource();

        } catch (\Throwable $th) {
            OrderLogger::error($th->getMessage());
            throw new BusinessException(ErrorCode::ORDER_ERROR);
        }
    }

    /**
     * 队列订单插入
     * @param [type] $params
     * @return void
     */
    public static function createRun($params): void
    {
        // $order = Order::create($params['order']);
        // foreach ($params['iteams'] as $k => $v) {
        //     $params['iteams'][$k]['order_id'] = $order->id;
        // }
        // OrderItem::insert($params['iteams']);

        $order = Order::create($params['order']);
        $order->items()->createMany($params['iteams']);
    }
}
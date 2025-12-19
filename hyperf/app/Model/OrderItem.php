<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property int $order_id 
 * @property int $category_id 
 * @property int $brand_id 
 * @property int $goods_id 
 * @property string $goods_name 
 * @property string $goods_price 
 * @property int $quantity 
 * @property string $total_amount 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class OrderItem extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'order_items';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'order_id', 'category_id', 'brand_id', 'goods_id', 'goods_name', 'goods_price', 'number', 'total_amount', 'created_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'order_id' => 'integer', 'category_id' => 'integer', 'brand_id' => 'integer', 'goods_id' => 'integer', 'quantity' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}

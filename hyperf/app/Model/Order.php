<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property string $order_no 
 * @property int $user_id 
 * @property string $total_amount 
 * @property int $status 
 * @property int $payment_status 
 * @property string $payment_method 
 * @property string $payment_at 
 * @property int $address_id 
 * @property string $address_json 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class Order extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'orders';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['order_no', 'user_id', 'total_amount', 'status', 'payment_status', 'payment_method', 'payment_at', 'address_id', 'address_json', 'created_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'status' => 'integer', 'payment_status' => 'integer', 'address_id' => 'integer', 'address_json' => 'array', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function items() {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}

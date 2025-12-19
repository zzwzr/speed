<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $sku 
 * @property string $price 
 * @property string $original_price 
 * @property int $stock 
 * @property int $sales 
 * @property string $description 
 * @property string $image 
 * @property string $gallery 
 * @property int $status 
 * @property int $category_id 
 * @property int $brand_id 
 * @property int $sort 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class Good extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'goods';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'name', 'sku', 'price', 'original_price', 'stock', 'sales', 'description', 'image', 'gallery', 'status', 'category_id', 'brand_id', 'sort', 'created_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'stock' => 'integer', 'sales' => 'integer', 'status' => 'integer', 'category_id' => 'integer', 'brand_id' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}

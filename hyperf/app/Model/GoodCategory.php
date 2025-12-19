<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $slug 
 * @property int $sort_order 
 * @property string $image 
 * @property string $description 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class GoodCategory extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'good_categories';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'name', 'slug', 'sort_order', 'image', 'description', 'created_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'sort_order' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}

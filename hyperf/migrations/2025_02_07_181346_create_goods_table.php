<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('')->comment('商品名称');
            $table->string('sku', 50)->unique()->comment('商品SKU编号');
            $table->decimal('price', 10, 2)->default(0)->comment('商品价格');
            $table->decimal('original_price', 10, 2)->default(0)->comment('商品原价');
            $table->integer('stock')->default(0)->comment('库存数量');
            $table->integer('sales')->default(0)->comment('销量');
            $table->text('description')->nullable()->comment('商品描述');
            $table->string('image')->default('')->comment('商品主图');
            $table->json('gallery')->nullable()->comment('商品轮播图');
            $table->tinyInteger('status')->default(1)->comment('商品状态：1：上架，2：下架');
            $table->unsignedBigInteger('category_id')->default(0)->comment('商品分类ID');
            $table->unsignedBigInteger('brand_id')->default(0)->comment('品牌ID');
            $table->integer('sort')->default(0)->comment('排序值，值越大越靠前');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};

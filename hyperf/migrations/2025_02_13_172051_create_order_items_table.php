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
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('订单商品ID');
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->unsignedBigInteger('category_id')->comment('商品分类ID');
            $table->unsignedBigInteger('brand_id')->comment('商品品牌ID');
            $table->unsignedBigInteger('goods_id')->comment('商品ID');
            $table->string('goods_name')->comment('商品名称');
            $table->decimal('goods_price', 10, 2)->comment('商品单价');
            $table->integer('number')->default(1)->comment('购买数量');
            $table->decimal('total_amount', 10, 2)->comment('商品总金额');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单详情表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

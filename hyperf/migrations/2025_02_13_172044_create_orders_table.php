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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('订单ID');
            $table->string('order_no', 64)->unique()->comment('订单号');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->decimal('total_amount', 12, 2)->comment('订单总金额');
            $table->tinyInteger('status')->default(0)->comment('订单状态，1：待支付，2：已支付，3：已发货，4：已完成，5：已取消');
            $table->tinyInteger('payment_status')->default(0)->comment('支付状态，1：未支付，2：已支付');
            $table->string('payment_method', 32)->default('')->comment('支付方式，微信，支付宝');
            $table->timestamp('payment_at')->nullable()->comment('支付时间');
            $table->bigInteger('address_id')->default(0)->comment('地址id');
            $table->json('address_json')->nullable()->comment('地址json');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('基础订单表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

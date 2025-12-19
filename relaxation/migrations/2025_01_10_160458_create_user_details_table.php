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
        Schema::create('user_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unique()->comment('用户ID');
            $table->decimal('coin', 10, 2)->default(0)->comment('金币');
            $table->decimal('worth', 10, 2)->default(0)->comment('资产');
            $table->tinyInteger('luck')->default(0)->comment('运气，提升获得好东西的概率。最多10');
            $table->string('introduction')->default('')->comment('个人简介');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('用户详情');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};

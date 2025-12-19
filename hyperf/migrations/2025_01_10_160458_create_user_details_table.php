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
            $table->char('id_card', 18)->default('')->comment('身份证号');
            $table->date('birthday')->nullable()->comment('出生日期');
            $table->string('address')->default('')->comment('家庭住址');
            $table->text('bio')->nullable()->comment('个人简介');
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

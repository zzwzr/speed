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
        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('')->comment('品牌名称');
            $table->string('slug')->default('')->comment('分类标识');
            $table->string('logo')->nullable()->comment('品牌logo');
            $table->text('description')->nullable()->comment('品牌描述');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品品牌表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};

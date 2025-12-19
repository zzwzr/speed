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
        Schema::create('good_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('')->comment('分类名称');
            $table->string('slug')->default('')->comment('分类标识');
            // $table->unsignedBigInteger('parent_id')->default(0)->comment('父分类ID，0表示顶级分类');
            $table->integer('sort_order')->default(0)->comment('排序值，值越大越靠前');
            $table->string('image')->nullable()->comment('分类图片');
            $table->text('description')->nullable()->comment('分类描述');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品分类表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('good_categories');
    }
};

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
        Schema::create('clockins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unique()->comment('用户ID');
            $table->bigInteger('')->unique()->comment('用户ID');

            $table->timestamps();
            $table->softDeletes();
            $table->comment('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clockins');
    }
};

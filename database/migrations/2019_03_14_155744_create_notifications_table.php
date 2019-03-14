<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->comment('类型');
            $table->enum('user_type', ['system', 'user'])->comment('接受用户类型：0=系统；1=用户');
            $table->unsignedInteger('resource_id')->comment('关联资源 ID');
            $table->string('resource_type')->comment('关联资源类型');
            $table->text('data')->comment('内容');
            $table->timestamp('read_at')->nullable()->comment('是否已读');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
}

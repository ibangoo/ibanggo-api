<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('area_code')->default('0086')->comment('区号');
            $table->string('phone')->nullable()->unique()->comment('手机');
            $table->string('name')->nullable()->unique()->comment('账号');
            $table->string('email')->nullable()->unique()->comment('邮箱');
            $table->char('password', 60)->nullable()->comment('登录密码');
            $table->char('pay_password', 60)->nullable()->comment('支付密码');
            $table->enum('status', ['enable', 'disable', 'deleted'])->default('enable')->comment('状态 enable=正常，disable=禁用，deleted=删除');
            $table->unsignedInteger('login_times')->default(0)->comment('登录次数');
            $table->ipAddress('last_login_ip')->comment('最后一次登录 IP 地址');
            $table->timestamp('last_login_at')->comment('最后一次登录时间');
            $table->ipAddress('created_with_ip')->comment('创建 IP 地址');
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
        Schema::dropIfExists('accounts');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThirdPartyPlatformAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_party_platform_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->comment('账号 ID');
            $table->unsignedInteger('resource_id')->comment('用户 ID');
            $table->string('resource_type')->comment('用户类型');
            $table->string('platform_id')->comment('平台 open id');
            $table->string('platform_token')->comment('平台 access token');
            $table->enum('type', ['wechat_open_platform', 'wechat_official_account', 'qq'])->comment('平台类型：微信开放平台，微信公众平台，QQ');
            $table->string('nickname')->comment('昵称');
            $table->string('avatar')->comment('头像');
            $table->text('extra')->comment('其他');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('third_party_platform_accounts');
    }
}

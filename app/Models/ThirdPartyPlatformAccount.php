<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ThirdPartyPlatformAccount
 *
 * @package App\Models
 */
class ThirdPartyPlatformAccount extends Model
{
    use SoftDeletes;
    /**
     * @var array
     */
    public static $typeMap = [
        'wechat_open_platform' => '微信开放平台',
        'wechat_official_account' => '微信公众平台',
        'qq' => '腾讯开放平台',
    ];
    /**
     * @var array
     */
    public static $driverMap = [
        'wechat_open_platform' => 'weixinweb',
        'wechat_official_account' => 'weixin',
        'qq' => 'qq',
    ];
    /**
     * @var array
     */
    protected $fillable = [
        'account_id',
        'resource_id',
        'resource_type',
        'platform_id',
        'platform_token',
        'type',
        'nickname',
        'avatar',
        'extra',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

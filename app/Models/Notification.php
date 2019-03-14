<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;

/**
 * Class Notification - 消息通知模型
 *
 * @package App\Models
 */
class Notification extends DatabaseNotification
{
    use SoftDeletes;
    const USER_TYPE_STSTEM = 'system';
    const USER_TYPE_USER = 'user';
    /**
     * @var array
     */
    public static $userTypeMap = [
        self::USER_TYPE_STSTEM => '系统',
        self::USER_TYPE_USER => '用户',
    ];
    /**
     * @var bool
     */
    public $incrementing = true;
    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'user_type',
        'resource_id',
        'resource_type',
        'data',
        'read_at',
    ];

    /**
     * 获取消息通知模型关联资源
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo | mixed
     */
    public function notifiable()
    {
        return $this->resource();
    }

    /**
     * 获取消息通知模型关联资源
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo | mixed
     */
    public function resource()
    {
        return $this->morphTo('resource');
    }
}

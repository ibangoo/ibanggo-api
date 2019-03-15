<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


/**
 * Class Account - 账号模型
 *
 * @package App\Models
 */
class Account extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    /**
     * @var array
     */
    public static $statusMap = [
        'enable' => '正常',
        'disable' => '禁用',
        'deleted' => '删除',
    ];
    /**
     * @var array
     */
    protected $fillable = [
        'area_code',
        'phone',
        'name',
        'email',
        'password',
        'pay_password',
        'status',
        'login_times',
        'last_login_ip',
        'last_login_at',
        'created_with_ip',
    ];
    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'pay_password',
    ];
    /**
     * @var array
     */
    protected $dates = [
        'last_login_at',
    ];

    /**
     * 定义 payload 的 sub 字段返回内容
     *
     * @return mixed
     */
    public function getJWTIdentifier(): int
    {
        return $this->getKey();
    }

    /**
     * 在 payload 中增加自定义内容
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}

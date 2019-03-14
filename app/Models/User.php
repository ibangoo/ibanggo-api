<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User - 用户模型
 *
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    /**
     * @var array
     */
    public static $genderMap = [
        'male' => '男',
        'female' => '女',
        'unknown' => '缺省',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'nickname',
        'avatar',
        'gender',
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

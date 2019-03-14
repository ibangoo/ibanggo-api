<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Account - 账号模型
 *
 * @package App\Models
 */
class Account extends Model
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


/**
 * Class User - 用户模型
 *
 * @package App\Models
 */
class User extends Model
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
}

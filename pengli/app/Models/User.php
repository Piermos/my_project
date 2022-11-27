<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_user','user_id','role_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'password_salt',
    ];

    /**
     * The attributes that should be hidden for serialization. 
     * 隐藏密码与密码盐
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'password_salt',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * 将需要的字段转换成需要的类型
     *
     * @var array<string, string>
     */
    protected $casts = [
        'create_at' => 'date',
        'update_at' => 'timestamp',
    ];

    /**
     * 创建时间格式化
     */
    public function getCreateAtAttribute($value)
    {
        return date('Y-m-d H:i:s',$value);
    }
    /**
     * 更新时间格式化
     */
    public function getUpdateAtAttribute($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

}

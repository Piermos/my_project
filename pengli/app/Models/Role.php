<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'permission_role','role_id','permission_id');
    }

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProxyModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'proxys';

    protected $primaryKey = 'proxy_id';

    // public function team()
    // {
    //     return $this->belongsTo(Post::class);
    // }

    protected $fillable = [
        'proxy_name','job_number','organization_id','department_id','rank_id','id_card_number','gender','mobile','education_id','bank_name','bank_number',
        'referrer','work_type','work_number','status','entry_date'
    ];

    protected $hidden = [
        'password',
    ];


}

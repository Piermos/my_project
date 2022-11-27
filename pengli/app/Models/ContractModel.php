<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'contracts';

    protected $primaryKey = 'contract_id';

    protected $attributes = [
        'is_settlement' => '未结佣',
    ];

    protected $fillable = [
        'policy_number','insurance_company_id','product_id','payment_period','regular_premium','standard_premium','policy_holder','policy_status_id',
        'insurance_date','underwriting_date','contract_delivery_date','receipt_date','return_visit_date','attribution_scheme_id','proxy_id','settlement_rate',
        'settlement_fyc'
    ];
    // protected $fillable = ['*'];

    

}

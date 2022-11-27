<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WagesModel extends Model
{
    use HasFactory;

    protected $table = 'wages';

    protected $primaryKey = 'wages_id';

    protected $attributes = [
        'is_confirm' => 1,
        'fyc' => 0.00,
        'sales_incentives' => 0.00,
        'persistency_commission' => 0.00,
        'recommendation_allowance' => 0.00,
        'management_allowance' => 0.00,
        'quarterly_back_calculation' => 0.00,
        'independent_scheme' => 0.00,
        'scheme_reward' => 0.00,
        'allowance' => 0.00,
        'group_insurance' => 0.00,
        'deduction_amount' => 0.00,
        'pre_tax_amount' => 0.00,
        'individual_income_tax' => 0.00,
        'after_tax_amount' => 0.00,
    ];

    protected $fillable = [
        'commission_month','settlement_month','proxy_id','proxy_name'
    ];
}

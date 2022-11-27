<?php

namespace App\Imports;

use App\Models\ContractModel;
use App\Models\Demo;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Facades\Excel;

class ContractImport implements ToModel,WithStartRow,SkipsFailures
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        // if($row[0] == '保单号'){
        //     return null;
        // }
        // $insuranceCompanyId = DB::table('insurance_companys')->where('insurance_company_name',$row[1])->value('insurance_company_id');    //险司
        // $productId = DB::table('products')->where('product_name',$row[2])->value('insurance_company_id');    //产品
        // $commission = DB::table('commissions')->where('product_id',$productId)->where('payment_period',$row[3])->first();    //佣金表ID
        // $policyStatusId = DB::table('policy_status')->where('policy_status_name',$row[9])->value('policy_status_id');    //状态
        // $proxyId = DB::table('proxys')->where('proxy_name',$row[16])->value('proxy_id');    //代理人

        // $temp = [
        //     'policy_number' => $row[0],                     //保单号
        //     'insurance_company_id' => $insuranceCompanyId,              //保险公司
        //     'product_id' => $productId,                        //产品
        //     'payment_period' => $row[3],                     //交费期间
        //     'regular_premium' => $row[4],                   //期交保费保费
        //     'standard_premium' => $row[5],                  //标准保费
        //     'policy_holder' => $row[6],                  //投保人
        //     'policy_holder_gender' => $row[7],                  //投保人性别
        //     'policy_holder_birth' => $row[8],                  //投保人出生日期
        //     'policy_status_id' => $policyStatusId,                  //保单状态
        //     'insurance_date' => $row[10],                  //录单时间
        //     'underwriting_date' => $row[11],                   //承保时间
        //     'receipt_date' => $row[12],                   //回执时间
        //     'return_visit_date' => $row[13],                   //回访时间
        //     'proxy_id' => $proxyId,                  //业务员
        //     'settlement_rate' => $commission['first_year_rate'],
        //     'settlement_fyc' => $commission['first_year_rate']*$row[4]/100,
        // ];
        // return new ContractModel($temp);
        if($row[0] == '姓名'){
            return null;
        }
        return new Demo([
            'name' => '皮伟'
        ]);
    }

    /**
     * 从第几行数据开始，跳过表头
     *
     * @return integer
     */
    public function startRow(): int
    {
        return 2;
    }

    
    /**
     * 批量一次插入数量
     *
     * @return integer
     */
    public function batchSize(): int
    {
        return 1;
    }
}

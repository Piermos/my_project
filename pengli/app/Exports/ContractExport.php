<?php

namespace App\Exports;

use App\Models\ContractModel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;

class ContractExport implements FromQuery,WithHeadings,Responsable
{
    use Exportable;

    private $fileName = '新契约保单.xlsx';
    
    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;
    
    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/xlsx',
    ];

    public function __construct($where)
    {
        $this->where = $where;
    }

    /**
     * 内容
     *
     * @return void
     */
    public function query()
    {
        $column = [
            'policy_number','insurance_company_name','product_name','regular_premium','payment_period','standard_premium',
            'policy_holder','policy_holder_gender','policy_holder_birth','policy_status_name','insurance_date','underwriting_date',
            'receipt_date','return_visit_date','organization_name','department_name','proxy_name'
        ];
        return ContractModel::query()->where($this->where)
            ->join('products','contracts.product_id','products.product_id')
            ->join('insurance_companys','contracts.insurance_company_id','insurance_companys.insurance_company_id')
            ->join('proxys','contracts.proxy_id','proxys.proxy_id')
            ->join('departments','proxys.department_id','departments.department_id')
            ->join('organizations','proxys.organization_id','organizations.organization_id')
            ->join('policy_status','contracts.policy_status_id','policy_status.policy_status_id')
            ->select($column,'products.product_name','insurance_companys.insurance_company_name',
                    'proxys.proxy_name','proxys.organization_id','proxys.department_id','departments.department_name',
                    'organizations.organization_name','policy_status.policy_status_name'
        );
    }

    /** 
     * 表头
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            '保单号','保险公司','产品','期交保费','交费期间','标准保费','投保人','投保人性别','投保人出生日期','保单状态','投保日期',
            '承保日期','回执日期','回访日期','机构','部门','业务员'
        ];
    }
}

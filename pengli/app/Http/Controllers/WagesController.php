<?php

namespace App\Http\Controllers;

use App\Models\ContractModel;
use App\Models\ProxyModel;
use App\Models\WagesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WagesController extends Controller
{

   
    //寿险已结算
    public function lifeInsuranceSettled(Request $request)
    {
        $requestData = array_filter($request->input());
        if(!isset($requestData['is_settlement'])){
            $requestData['is_settlement'] = 1;
        }
        $where = function($query) use ($requestData){
            $query->where('contracts.policy_status_id' , '=' , 3);
            //其他条件
            foreach($requestData as $key=>$value){
                if($key == 'settlement_date_start' || $key == 'settlement_date_end' || $key == 'page'){
                    continue;
                }
                if($key == 'organization_id' || $key == 'department_id'){
                    $query->where('proxys.'.$key , '=' , $value);
                }else if($key == 'policy_holder'){
                    $query->where('contracts.'.$key , 'like' , '%'.$value.'%');
                }else{
                    $query->where('contracts.'.$key , '=' , $value);
                }
            }
            //日期
            if(isset($requestData['settlement_date_start']) && isset($requestData['settlement_date_end'])){
                $query->whereBetween('contracts.settlement_date',[$requestData['settlement_date_start'],$requestData['settlement_date_end']]);
            }
            if(isset($requestData['settlement_date_start']) && !isset($requestData['settlement_date_end'])){
                $query->where('contracts.settlement_date','>=',$requestData['settlement_date_start']);
            }
            if(!isset($requestData['settlement_date_start']) && isset($requestData['settlement_date_end'])){
                $query->where('contracts.settlement_date','<=',$requestData['settlement_date_end']);
            }
        };
        $contracts = ContractModel::where($where)
            ->join('products','contracts.product_id','products.product_id')
            ->join('insurance_companys','contracts.insurance_company_id','insurance_companys.insurance_company_id')
            ->join('proxys','contracts.proxy_id','proxys.proxy_id')
            ->join('departments','proxys.department_id','departments.department_id')
            ->join('organizations','proxys.organization_id','organizations.organization_id')
            ->join('policy_status','contracts.policy_status_id','policy_status.policy_status_id')
            ->select('contracts.*','products.product_name','insurance_companys.insurance_company_name',
                    'proxys.proxy_name','proxys.organization_id','proxys.department_id','departments.department_name',
                    'organizations.organization_name','policy_status.policy_status_name',
                    )
            ->orderBy('insurance_date','ASC')
            ->paginate(10);

        $contracts = $contracts->appends($requestData);
        $insuranceCompanys = DB::table('insurance_companys')->where('status','正常')->get(['insurance_company_id','insurance_company_name']);     //保司
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        $departments = [];     //部门
        $proxys = [];     //业务员
        if(isset($requestData['organization_id'])){
            $departments = DB::table('departments')->where('organization_id',$requestData['organization_id'])->get(['department_id','department_name']);
        }
        if(isset($requestData['department_id'])){
            $proxys = DB::table('proxys')->where('department_id',$requestData['department_id'])->where('status',1)->get(['proxy_id','proxy_name']);
        }
        return view('wages.life_insurance_settled',compact('contracts','requestData','insuranceCompanys','organizations','departments','proxys'));
    }

    //编辑寿险未结算保单
    public function lifeInsuranceSettledEdit($contractId)
    {
        //保单
        $contract = ContractModel::join('products','contracts.product_id','products.product_id')
            ->join('insurance_companys','contracts.insurance_company_id','insurance_companys.insurance_company_id')
            ->join('proxys','contracts.proxy_id','proxys.proxy_id')
            ->join('departments','proxys.department_id','departments.department_id')
            ->join('organizations','proxys.organization_id','organizations.organization_id')
            ->join('policy_status','contracts.policy_status_id','policy_status.policy_status_id')
            ->select('contracts.*','products.product_name','insurance_companys.insurance_company_name',
                    'proxys.proxy_name','proxys.organization_id','proxys.department_id','departments.department_name',
                    'organizations.organization_name','policy_status.policy_status_name'
                    )
            ->find($contractId);
        return view('wages.life_insurance_unsettled_edit',compact('contract'));
    }

     //新增或编辑保存新契约保单（逻辑）
     public function lifeInsuranceSettledSave(Request $request)
     {
         $data = [
             'code' => '400',
             'message' => '保存失败',
             'data' => []
         ];
         $requestData = array_filter($request->input());
        
        //验证数据

        $requestData['settlement_date'] = date('Y-m-20',strtotime(date('Y-m-d')));
        $requestData['is_settlement'] = 2;
        // $data['data'] = $requestData;
        // return response()->json($data);
         //获取保单
         $contract = ContractModel::find($requestData['contract_id']);
         foreach($requestData as $key=>$value){
             $contract->$key = $value;
         }
         $contract->save();
         $data['code'] = 200;
         $data['message'] = '保存成功';
         
 
         return response()->json($data);
     }


      /**
     * 本期工资明细
     *
     * @param Request $request
     * @return void
     */
    public function currentPayroll(Request $request)
    {
        $requestData = array_filter($request->input());
        
    
        $where = function($query) use ($requestData){
            
            $query->where('wages.is_confirm', '=' , 1); //显示未确定的工资
            //其他条件
            foreach($requestData as $key=>$value){
                if($key == 'page'){
                    continue;
                }
                $query->where('wages.'.$key , '=' , $value);
            }
        };
        $wagess = WagesModel::where($where)
            ->join('proxys','wages.proxy_id','proxys.proxy_id')
            ->join('departments','proxys.department_id','departments.department_id')
            ->join('organizations','proxys.organization_id','organizations.organization_id')
            ->select('wages.*','proxys.proxy_name','proxys.organization_id','proxys.department_id','departments.department_name',
                    'organizations.organization_name')
            ->orderBy('pre_tax_amount','DESC')
            ->paginate(10);
        $wagess = $wagess->appends($requestData);
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        $departments = [];     //部门
        $proxys = [];     //业务员
        if(isset($requestData['organization_id'])){
            $departments = DB::table('departments')->where('organization_id',$requestData['organization_id'])->get(['department_id','department_name']);
        }
        if(isset($requestData['department_id'])){
            $proxys = DB::table('proxys')->where('department_id',$requestData['department_id'])->where('status',1)->get(['proxy_id','proxy_name']);
        }
        $title = $wagess->isEmpty()?'':str_replace('-','年',$wagess[0]->commission_month).'月工资明细';
        return view('wages.current_payroll',compact('wagess','requestData','organizations','departments','proxys','title'));
    }

    /**
     * 生成本期工资明细
     *
     * @return void
     */
    public function generateCurrentPayroll()
    {
        $data = [
            'code' => '200',
            'message' => '操作成功',
            'data' => []
        ];
        //佣金月份
        $commissionMonth = date('Y-m',strtotime('last day of last month',strtotime(date('Y-m'))));
        //检查是否存在当期工资明细
        $isConfirmWagess = WagesModel::where('is_confirm',1)->orWhere('commission_month',$commissionMonth)->get();
        if(!$isConfirmWagess->isEmpty()){
            $data['code'] = 400;
            $data['message'] = '当期工资明细已存在';
            return response()->json($data);
        }
        //生成只带月份与姓名的记录
        $proxys = ProxyModel::where('status',1)->select('proxy_id','proxy_name')->get();
        $proxys = $proxys->toArray();
        foreach($proxys as $proxy){
            $proxy['commission_month'] = $commissionMonth;
            $proxy['settlement_month'] = date('Y-m');
            WagesModel::create($proxy);
        }
        return response()->json($data);
    }

    public function calculationSalary($commissionMonth)
    {
        //获取本期结算保单
        $contracts = ContractModel::where(function($query){
            $query->where('policy_status_id','=',3)->where('is_settlement','=',1);
        })->get();
    }


     /**
      * 工资明细
      *
      * @return void
      */
     public function payroll(Request $request)
     {
        $requestData = array_filter($request->input());
        $where = function($query) use ($requestData){
            //其他条件
            foreach($requestData as $key=>$value){
                if($key == 'page'){
                    continue;
                }
                if($key == 'organization_id' || $key == 'department_id'){
                    $query->where('wages.'.$key , '=' , $value);
                }else if($key == 'proxy_name'){
                    $query->where('wages.'.$key , 'like' , '%'.$value.'%');
                }else{
                    $query->where('wages.'.$key , '=' , $value);
                }
            }
        };
        $wagess = WagesModel::where($where)
            ->orderBy('pre_tax_amount','DESC')
            ->paginate(10);
        $wagess = $wagess->appends($requestData);

        $commissionMonths = WagesModel::select('commission_month')->distinct()->get();
        // dd($settlement_month);
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        $departments = [];     //部门
        $proxys = [];     //业务员
        if(isset($requestData['organization_id'])){
            $departments = DB::table('departments')->where('organization_id',$requestData['organization_id'])->get(['department_id','department_name']);
        }
        if(isset($requestData['department_id'])){
            $proxys = DB::table('proxys')->where('department_id',$requestData['department_id'])->where('status',1)->get(['proxy_id','proxy_name']);
        }
        return view('wages.payroll',compact('wagess','requestData','commissionMonths','organizations','departments','proxys'));
     }


    /**
     * 工资条（页面）
     *
     * @param integer $wagesId
     * @return void
     */
    public function payslip(int $wagesId)
    {
        $wages = WagesModel::find($wagesId);
        return view('wages.payslip',compact('wages'));
    }

    /**
     * 保存工资条（逻辑）
     *
     * @param Request $request
     * @return void
     */
    public function payslipSave(Request $request)
    {
        $data = [
            'code' => '400',
            'message' => '保存失败',
            'data' => []
        ];
        $requestData = array_filter($request->input());
       
        //验证数据
        //获取保单
        $wages = WagesModel::find($requestData['wages_id']);
        foreach($requestData as $key=>$value){
            $wages->$key = $value;
        }
        $wages->save();
        $data['code'] = 200;
        $data['message'] = '保存成功';
        return response()->json($data);
    }
}

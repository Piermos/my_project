<?php

namespace App\Http\Controllers;

use App\Models\ContractModel;
use App\Models\ProxyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    //新契约保单列表
    public function newContractList(Request $request)
    {
        $requestData = array_filter($request->input());
        $where = function($query) use (&$requestData){
            //其他条件
            foreach($requestData as $key=>$value){
                if($key == 'insurance_date_start' || $key == 'insurance_date_end' || $key == 'page'){
                    continue;
                }
                if($key == 'organization_id' || $key == 'department_id'){
                    $query->where('proxys.'.$key , '=' , $value);
                }else if($key == 'policy_holder'){
                    $query->where('contracts.'.$key , 'like' , '%'.$value.'%');
                }else if($key == 'receipt_date' || $key == 'return_visit_date'){
                    if($value == 1){
                        $query->where('contracts.'.$key , '=' , null);
                    }else{
                        $query->where('contracts.'.$key , '<>' , null);
                    }
                }else{
                    $query->where('contracts.'.$key , '=' , $value);
                }
            }
            //日期
            if(isset($requestData['insurance_date_start']) && isset($requestData['insurance_date_end'])){
                $query->whereBetween('contracts.insurance_date',[$requestData['insurance_date_start'],$requestData['insurance_date_end']]);
            }
            if(isset($requestData['insurance_date_start']) && !isset($requestData['insurance_date_end'])){
                $query->where('contracts.insurance_date','>=',$requestData['insurance_date_start']);
            }
            if(!isset($requestData['insurance_date_start']) && isset($requestData['insurance_date_end'])){
                $query->where('contracts.insurance_date','<=',$requestData['insurance_date_end']);
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
            ->orderBy('contract_id','DESC')
            ->paginate(10);
            // dd($contracts);
        $contracts = $contracts->appends($requestData);

        $policyStatus = DB::table('policy_status')->get();     //保单状态
        $insuranceCompanys = DB::table('insurance_companys')->get(['insurance_company_id','insurance_company_name']);     //保司
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        $products = [];     //产品
        $departments = [];     //部门
        $proxys = [];     //业务员
        if(isset($requestData['insurance_company_id'])){
            $products = DB::table('products')->where('insurance_company_id',$requestData['insurance_company_id'])->get(['product_id','product_name']);
        }
        if(isset($requestData['organization_id'])){
            $departments = DB::table('departments')->where('organization_id',$requestData['organization_id'])->get(['department_id','department_name']);
        }
        if(isset($requestData['department_id'])){
            $proxys = DB::table('proxys')->where('department_id',$requestData['department_id'])->get(['proxy_id','proxy_name']);
        }
        // dd($contracts);
        return view('contract.new_contract_list',compact('contracts','requestData','insuranceCompanys','products','policyStatus','organizations','departments','proxys'));
    }

    //新增新契约保单（页面）
    public function newContractAdd()
    {
        $insurance_companys = DB::table('insurance_companys')->get(['insurance_company_id','insurance_company_name']);  //险司
        $products = [];  //产品
        $commissions = [];  //佣金
        $policy_status = DB::table('policy_status')->get();  //保单状态
        $organizations = DB::table('organizations')->get();
        $departments = [];
        $proxys = [];
        $attributionSchemes = DB::table('attribution_schemes')->get(['attribution_scheme_id','attribution_scheme_name']);
        return view('contract.new_contract_add',compact('insurance_companys','products','commissions','policy_status','organizations','departments','proxys','attributionSchemes'));
    }

    //编辑新契约保单（页面）
    public function newContractEdit($contractId)
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
        $insurance_companys = DB::table('insurance_companys')->get(['insurance_company_id','insurance_company_name']);
        $products = DB::table('products')->get(['product_id','product_name']);  //产品
        $commissions = DB::table('commissions')->where('product_id',$contract->product_id)->get(['payment_period']);  
        $policy_status = DB::table('policy_status')->get();  //保单状态
        $organizations = DB::table('organizations')->get();
        $departments = DB::table('departments')->where('organization_id',$contract->organization_id)->get(['department_id','department_name']);
        $proxys = ProxyModel::where('department_id',$contract->department_id)->get(['proxy_id','proxy_name']);
        $attributionSchemes = DB::table('attribution_schemes')->get(['attribution_scheme_id','attribution_scheme_name']);
        return view('contract.new_contract_add',compact('contract','insurance_companys','products','commissions','policy_status','organizations','departments','proxys','attributionSchemes'));
    }

    //新增或编辑保存新契约保单（逻辑）
    public function newContractStore(Request $request)
    {
        $data = [
            'code' => '400',
            'message' => '保存失败',
            'data' => []
        ];
        $requestData = array_filter($request->input());
        //验证数据
       
        //当保单状态为承保时，获得应结算日期
        if($requestData['policy_status_id'] == 3){
            $payableSettlementDateData = $this->getPayableSettlementDate($requestData);

            if($payableSettlementDateData['code'] == 400){
                $data['message'] = $payableSettlementDateData['message'];
                return response()->json($data);
            }
            $requestData['payable_settlement_date'] = $payableSettlementDateData['date'];
        }

        if(!isset($requestData['contract_id'])){       //新增
            $standardPremium = $this->calculateStandardPremium($requestData['regular_premium'],$requestData['payment_period']);    //标保
            $requestData['standard_premium'] = $standardPremium;
            //首年佣金率
            $settlementRate = DB::table('commissions')->where('product_id',$requestData['product_id'])->where('payment_period',$requestData['payment_period'])->value('first_year_rate');
            $requestData['settlement_rate'] = $settlementRate;
            $requestData['settlement_fyc'] = $requestData['regular_premium']*$settlementRate/100;
            //当保单状态非承保时，不保存承保时间
            if($requestData['policy_status_id'] == 1 || $requestData['policy_status_id'] == 2){
                unset($requestData['underwriting_date']);
                unset($requestData['receipt_date']);
                unset($requestData['return_visit_date']);
            }
            //新增
            $contract = ContractModel::create($requestData);
            if(!empty($contract)){
                $data['code'] = 200;
                $data['message'] = '保存成功';
            }
            $data['data'] = $contract;
        }else{      //保存
            $contract = ContractModel::find($requestData['contract_id']);
            foreach($requestData as $key=>$value){
                $contract->$key = $value;
                if($key == 'payment_period' || $key == 'regular_premium'){
                    $standardPremium = $this->calculateStandardPremium($requestData['regular_premium'],$requestData['payment_period']);    //标保
                    $contract->standard_premium = $standardPremium;
                }
            }
            $contract->save();
            $data['code'] = 200;
            $data['message'] = '保存成功';
        }
        

        return response()->json($data);
    }

    /**
     * 待回执回销清单
     */
    public function waitReceiptList(Request $request)
    {
        $requestData = array_filter($request->input());
        $where = function($query) use (&$requestData){
            $query->where('contracts.policy_status_id', '=' , 3)->where('contracts.receipt_date', '=' , null);

            //其他条件
            foreach($requestData as $key=>$value){
                if($key == 'insurance_date_start' || $key == 'insurance_date_end' || $key == 'page'){
                    continue;
                }
                if($key == 'organization_id' || $key == 'department_id'){
                    $query->where('proxys.'.$key , '=' , $value);
                }else if($key == 'policy_holder'){
                    $query->where('contracts.'.$key , 'like' , '%'.$value.'%');
                }else if($key == 'receipt_date' || $key == 'return_visit_date'){
                    if($value == 1){
                        $query->where('contracts.'.$key , '=' , null);
                    }else{
                        $query->where('contracts.'.$key , '<>' , null);
                    }
                }else{
                    $query->where('contracts.'.$key , '=' , $value);
                }
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
            ->orderBy('contract_id','DESC')
            ->paginate(10);
            // dd($contracts);
        $contracts = $contracts->appends($requestData);

        $insuranceCompanys = DB::table('insurance_companys')->get(['insurance_company_id','insurance_company_name']);     //保司
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        $products = [];     //产品
        $departments = [];     //部门
        $proxys = [];     //业务员
        if(isset($requestData['insurance_company_id'])){
            $products = DB::table('products')->where('insurance_company_id',$requestData['insurance_company_id'])->get(['product_id','product_name']);
        }
        if(isset($requestData['organization_id'])){
            $departments = DB::table('departments')->where('organization_id',$requestData['organization_id'])->get(['department_id','department_name']);
        }
        if(isset($requestData['department_id'])){
            $proxys = DB::table('proxys')->where('department_id',$requestData['department_id'])->get(['proxy_id','proxy_name']);
        }

        return view('contract.wait_receipt_list',compact('contracts','requestData','insuranceCompanys','products','organizations','departments','proxys'));
    }

    /**
     * 待回访清单
     */
    public function returnVisitList(Request $request)
    {
        $requestData = array_filter($request->input());
        $where = function($query) use (&$requestData){
            $query->where('contracts.policy_status_id', '=' , 3)->where('contracts.return_visit_date', '=' , null);

            //其他条件
            foreach($requestData as $key=>$value){
                if($key == 'insurance_date_start' || $key == 'insurance_date_end' || $key == 'page'){
                    continue;
                }
                if($key == 'organization_id' || $key == 'department_id'){
                    $query->where('proxys.'.$key , '=' , $value);
                }else if($key == 'policy_holder'){
                    $query->where('contracts.'.$key , 'like' , '%'.$value.'%');
                }else if($key == 'receipt_date' || $key == 'return_visit_date'){
                    if($value == 1){
                        $query->where('contracts.'.$key , '=' , null);
                    }else{
                        $query->where('contracts.'.$key , '<>' , null);
                    }
                }else{
                    $query->where('contracts.'.$key , '=' , $value);
                }
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
            ->orderBy('contract_id','DESC')
            ->paginate(10);
            // dd($contracts);
        $contracts = $contracts->appends($requestData);

        $insuranceCompanys = DB::table('insurance_companys')->get(['insurance_company_id','insurance_company_name']);     //保司
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        $products = [];     //产品
        $departments = [];     //部门
        $proxys = [];     //业务员
        if(isset($requestData['insurance_company_id'])){
            $products = DB::table('products')->where('insurance_company_id',$requestData['insurance_company_id'])->get(['product_id','product_name']);
        }
        if(isset($requestData['organization_id'])){
            $departments = DB::table('departments')->where('organization_id',$requestData['organization_id'])->get(['department_id','department_name']);
        }
        if(isset($requestData['department_id'])){
            $proxys = DB::table('proxys')->where('department_id',$requestData['department_id'])->get(['proxy_id','proxy_name']);
        }

        return view('contract.return_visit_list',compact('contracts','requestData','insuranceCompanys','products','organizations','departments','proxys'));
    }

    /**
     * 获取应结算日期
     *
     * @param [type] $requestData
     * @return void
     */
    public function getPayableSettlementDate($requestData)
    {
        $data = [
            'code' => '400',
            'message' => '',
            'date' => ''
        ];
        
        //检查承保状态下,根据承保时间生成结算时间
        if(!isset($requestData['underwriting_date'])){
            $data['message'] = '承保时间不能为空';
            return $data;
        }
        //若有回访时间但无回执时间，提示错误
        if(isset($requestData['return_visit_date']) && !isset($requestData['receipt_date'])){
            $data['message'] = '回执时间不能为空';
            return $data;
        }
        $payableSettlementDate = date('Y-m-20',strtotime('last day of next month',strtotime($requestData['underwriting_date'])));

        //当回执时间存在时，根据承保时间与犹豫期时间判断是否超期
        if(isset($requestData['receipt_date'])){
            
            $product = DB::table('products')->where('product_id',$requestData['product_id'])->first();
            $hesitationPeriod = $product->hesitation_period;
            //当投保人性别与年龄存在时进行犹豫期是否为30天的判断
            if(isset($requestData['policy_holder_gender']) && isset($requestData['policy_holder_birth'])){
                $age = $this->getAge($requestData['policy_holder_birth']);
                if($age >= 60){
                    $hesitationPeriod = 30;
                }
                //当为信泰达到55岁女性客户，或者年龄达到60岁时，犹豫期为30天
                if($requestData['insurance_company_id'] == 1 && $requestData['policy_holder_gender'] == 2 && $age >= 55){
                    $hesitationPeriod = 30;
                }
            }
            
            $hesitationPeriodLastDate = date('Y-m-d',strtotime('+'.$hesitationPeriod.' day',strtotime($requestData['receipt_date'])));     //犹豫期截止日
            //若回执时间超过应结算日期当月5号，顺延到应结算日期的下一月结算
            if($requestData['receipt_date'] > date('Y-m-05',strtotime($payableSettlementDate))){
                $payableSettlementDate = date('Y-m-20',strtotime('last day of next month',strtotime($payableSettlementDate)));
            }
            //若犹豫期截止日大于应结算日期时，循环判断应结算日期的下一月20号是否大于等于犹豫期截止日期，为是时，该结算日期为应结算日期
            while($hesitationPeriodLastDate > $payableSettlementDate){
                $payableSettlementDate = date('Y-m-20',strtotime('last day of next month',strtotime($payableSettlementDate)));
            }
        }

        //当回访时间存在时，根据回访时间判断是否超期
        if(isset($requestData['receipt_date']) && isset($requestData['return_visit_date'])){
            //若回访日期大于应结算日期时，循环判断应结算日期的下一月20号是否大于等于回访日期，为是时，该结算日期为应结算日期
            while($requestData['return_visit_date'] > $payableSettlementDate){
                $payableSettlementDate = date('Y-m-20',strtotime('last day of next month',strtotime($payableSettlementDate)));
            }
        }
        $data['code'] = 200;
        $data['date'] = $payableSettlementDate;
        return $data;
    }

    //信息检索
    public function newContractAddRetrieval(Request $request)
    {
        $data = [
            'code' => '400',
            'message' => '',
            'data' => []
        ];
        $requestData = array_filter($request->input());
        //根据险司
        if(isset($requestData['insurance_company_id'])){
            $products = DB::table('products')->where('insurance_company_id',$requestData['insurance_company_id'])->get(['product_id','product_name']);
            // if(!$departments->isEmpty()){
                
            // }
            $data['code'] = 200;
            $data['data'] = $products;
        }
        //根据产品
        if(isset($requestData['product_id'])){
            $commissions = DB::table('commissions')->where('product_id',$requestData['product_id'])->get(['commission_id','payment_period']);
            $data['code'] = 200;
            $data['data'] = $commissions;
        }
        //根据机构
        if(isset($requestData['organization_id'])){
            $departments = DB::table('departments')->where('organization_id',$requestData['organization_id'])->get(['department_id','department_name']);
            $data['code'] = 200;
            $data['data'] = $departments;
        }
        //根据部门
        if(isset($requestData['department_id'])){
            $proxys = ProxyModel::where('department_id',$requestData['department_id'])->get(['proxy_id','proxy_name']);
            $data['code'] = 200;
            $data['data'] = $proxys;
        }
        return response()->json($data);
    }

    /**
     * 团财险列表
     *
     * @param Request $request
     * @return void
     */
    public function groupPropertyList(Request $request)
    {
        $requestData = array_filter($request->input());
        $where = function($query) use (&$requestData){
            //其他条件
            foreach($requestData as $key=>$value){
                if($key == 'insurance_date_start' || $key == 'insurance_date_end' || $key == 'page'){
                    continue;
                }
                if($key == 'organization_id' || $key == 'department_id'){
                    $query->where('proxys.'.$key , '=' , $value);
                }else{
                    $query->where('group_propertys.'.$key , '=' , $value);
                }
            }
            //日期
            if(!isset($requestData['insurance_date_start']) || !isset($requestData['insurance_date_end'])){
                $requestData['insurance_date_start'] = date('Y-m-01');
                $requestData['insurance_date_end'] = date('Y-m-d');
            }
            $query->whereBetween('group_propertys.insurance_date',[$requestData['insurance_date_start'],$requestData['insurance_date_end']]);
        };
        $groupPropertys = DB::table('group_propertys')->where($where)
            ->join('proxys','group_propertys.proxy_id','=','proxys.proxy_id')
            ->join('departments','proxys.department_id','=','departments.department_id')
            ->join('organizations','proxys.organization_id','=','organizations.organization_id')
            ->select('group_propertys.*','proxys.proxy_name','proxys.organization_id','proxys.department_id','departments.department_name',
                    'organizations.organization_name')
            ->orderBy('insurance_date','DESC')
            ->paginate(10);
        $groupPropertys = $groupPropertys->appends($requestData);
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        $departments = [];     //部门
        $proxys = [];     //业务员
        if(isset($requestData['organization_id'])){
            $departments = DB::table('departments')->where('organization_id',$requestData['organization_id'])->get(['department_id','department_name']);
        }
        if(isset($requestData['department_id'])){
            $proxys = DB::table('proxys')->where('department_id',$requestData['department_id'])->get(['proxy_id','proxy_name']);
        }
        return view('contract.group_property_list',compact('groupPropertys','requestData','organizations','departments','proxys'));
    }

    /**
     * 新增团财险保单
     *
     * @return void
     */
    public function groupPropertyAdd()
    {
        $organizations = DB::table('organizations')->get();
        $departments = [];
        $proxys = [];
        $attributionSchemes = DB::table('attribution_schemes')->orderBy('scheme_date_start','DESC')->get(['attribution_scheme_id','attribution_scheme_name']);
        return view('contract.group_property_add',compact('organizations','departments','proxys','attributionSchemes'));
    }

    /**
     * 编辑团财险保单（页面）
     *
     * @param [type] $groupPropertyId
     * @return void
     */
    public function groupPropertyEdit($groupPropertyId)
    {
        //保单
        $groupProperty = DB::table('group_propertys')->where('group_property_id',$groupPropertyId)
            ->join('proxys','group_propertys.proxy_id','proxys.proxy_id')
            ->join('departments','proxys.department_id','departments.department_id')
            ->join('organizations','proxys.organization_id','organizations.organization_id')
            ->select('group_propertys.*','proxys.proxy_name','proxys.organization_id','proxys.department_id','departments.department_name',
                    'organizations.organization_name',
                    )
            ->first();
        $organizations = DB::table('organizations')->get();
        $departments = DB::table('departments')->where('organization_id',$groupProperty->organization_id)->get(['department_id','department_name']);
        $proxys = ProxyModel::where('department_id',$groupProperty->department_id)->get(['proxy_id','proxy_name']);
        $attributionSchemes = DB::table('attribution_schemes')->orderBy('scheme_date_start','DESC')->get(['attribution_scheme_id','attribution_scheme_name']);
        return view('contract.group_property_add',compact('groupProperty','organizations','departments','proxys','attributionSchemes'));
    }

    /**
     * 新增或编辑保存团财险保单（页面）
     *
     * @param Request $request
     * @return void
     */
    public function groupPropertyStore(Request $request)
    {
        $data = [
            'code' => '400',
            'message' => '保存失败',
            'data' => []
        ];
        $requestData = array_filter($request->input());
        //验证数据
        $requestData['standard_premium'] = $this->calculateStandardPremium($requestData['regular_premium'],1,$requestData['product_type']);    //标保
        $requestData['settlement_fyc'] = $requestData['regular_premium']*$requestData['settlement_rate']/100;
        if(!isset($requestData['group_property_id'])){       //新增
            if($requestData['product_type'] == '团险'){
                $requestData['payable_settlement_date'] = date('Y-m-20',strtotime('+1 month',strtotime($requestData['insurance_date'])));
            }
            //新增
            $groupProperty = DB::table('group_propertys')->insert($requestData);
            if(!empty($groupProperty)){
                $data['code'] = 200;
                $data['message'] = '保存成功';
            }
            $data['data'] = $groupProperty;
        }else{      //保存
            if($requestData['product_type'] == '团险' && isset($request['insurance_date'])){
                $requestData['payable_settlement_date'] = date('Y-m-20',strtotime('+1 month',strtotime($requestData['insurance_date'])));
            }
            DB::table('group_propertys')->where('group_property_id',$requestData['group_property_id'])->update($requestData);
            $data['code'] = 200;
            $data['message'] = '保存成功';
        }
        return response()->json($data);
    }

    /**
     * 删除团财险保单
     *
     * @param [type] $departmentId
     * @return void
     */
    public function groupPropertyDelete($groupPropertyId)
    {
        $data = [
            'code' => '400',
            'message' => '删除失败',
            'data' => []
        ];
        $res = DB::table('group_propertys')->where('group_property_id',$groupPropertyId)->delete();
        if(!empty($res)){
            $data['code'] = 200;
            $data['message'] = '删除成功';
        }
        return response()->json($data);
    }


    /**
     * 计算标保
     *
     * @param [type] $productId         产品ID
     * @param [type] $regularPremium    期交保费
     * @param [type] $paymentPeriod     交费期间
     * @return void
     */
    public function calculateStandardPremium($regularPremium,$paymentPeriod,$productType=null)
    {
        $standardPremium = 0;
        if(empty($productType)){
            switch($paymentPeriod){
                case 1:
                    $standardPremium = $regularPremium*0.1;
                    break;
                case 3:
                    $standardPremium = $regularPremium*0.3;
                    break;
                case 5:
                    $standardPremium = $regularPremium*0.5;
                    break;
                default:
                    $standardPremium = $regularPremium*1;
            }
        }else{
            if($productType == '车险'){
                $standardPremium = $regularPremium*0.1;
            }
            if($productType == '团险'){
                $standardPremium = $regularPremium*0.2;
            }
        }
        return (float)$standardPremium;
    }

    /**
     * 根据生日计算年龄
     *
     * @param [type] $birthday
     * @return void
     */
    public function getAge($birthday)
    {
        $age = strtotime($birthday);
        if($age === false){
            return false;
        }
        //将出生年月日打到一个数组中
        list($y1,$m1,$d1) = explode('-',date('Y-m-d',$age));
        $now = strtotime('now');
        list($y2,$m2,$d2) = explode('-',date('Y-m-d',$now));
        $age = $y2 - $y1;
        //若月份小于生日，未满一岁
        if((int)($m2.$d2) < (int)($m1.$d1)){
            $age -= 1;
        }
        return $age;
    }
}

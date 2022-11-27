<?php

namespace App\Http\Controllers;

use App\Imports\ImportContract;
use App\Models\ContractModel;
use App\Models\Demo;
use App\Models\ProxyModel;
use App\Models\WagesModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class PersonController extends Controller
{
    //代理人列表
    public function proxyList(Request $request)
    {
        $requestData = array_filter($request->input());
        $where = [];
        foreach($requestData as $key=>$value){
            if($key != 'page'){
                if($key == 'proxy_name') {
                    array_unshift($where, ['proxys.'.$key, 'like', '%' . $value . '%']);
                }else{
                    array_unshift($where,['proxys.'.$key,'=',$value]);
                }
            }
        }
        //获取所有代理人
        $proxys = ProxyModel::where($where)
            ->join('ranks','proxys.rank_id','ranks.rank_id')
            ->join('departments','proxys.department_id','departments.department_id')
            ->join('organizations','proxys.organization_id','organizations.organization_id')
            ->select('proxys.*','ranks.rank_name','departments.department_name','organizations.organization_name')
            ->orderBy('job_number','DESC')
            ->paginate(10);
        $proxys = $proxys->appends($requestData);
        //搜索框数据
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        $departments = [];     //部门
        if(isset($requestData['organization_id'])){
            $departments = DB::table('departments')->where('organization_id',$requestData['organization_id'])->get(['department_id','department_name']);
        }
        
        return view('person.proxy_list',compact('proxys','requestData','organizations','departments'));
    }

    //新增代理人（页面）
    public function proxyAdd()
    {
        //工号
        $job_number = $this->getJobNumber();
        //学历记录
        $educations = DB::table('educations')->get();
        //职级记录
        $ranks = DB::table('ranks')->get();
        // //机构记录
        $organizations = DB::table('organizations')->get();
        return view('person.proxy_add',compact('job_number','educations','ranks','organizations'));
    }

     //新增信息匹配
     public function proxyAddRetrieval(Request $request)
     {
        $data = [
            'code' => '400',
            'message' => '',
            'data' => []
        ];
        $requestData = array_filter($request->input());

        //根据推荐人
        if(isset($requestData['referrer'])){
            $data['code'] = 200;
            $referrer_proxy = ProxyModel::where('proxy_name',$requestData['referrer'])->first();
            if(!empty($referrer_proxy)){
                $departments = DB::table('departments')->where('organization_id',$referrer_proxy->organization_id)->get(['department_id','department_name']);
                $data['data'] = [
                    'organization_id' => $referrer_proxy->organization_id,
                    'department_id' => $referrer_proxy->department_id,
                    'departments' => $departments
                ];
            }else{
                $data['message'] = '推荐人不存在';
                $data['data'] = [
                    'organization_id' => null,
                    'department_id' => null,
                    'departments' => []
                ];
            }
        }
        //根据机构
        if(isset($requestData['organization_id'])){
            $departments = DB::table('departments')->where('organization_id',$requestData['organization_id'])->get(['department_id','department_name']);
            // if(!$departments->isEmpty()){
                
            // }
            $data['code'] = 200;
                $data['data'] = $departments;
        }
        return response()->json($data);
     }

     //编辑代理人（页面）
    public function proxyEdit($proxyId)
    {
        //代理人
        $proxy = ProxyModel::find($proxyId);
        //学历记录
        $educations = DB::table('educations')->get();
        //职级记录
        $ranks = DB::table('ranks')->get(['rank_id','rank_name']);
        //机构记录
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);
        //机构下部门记录
        $departments = DB::table('departments')->where('organization_id',$proxy->organization_id)->get(['department_id','department_name']);
        return view('person.proxy_add',compact('proxy','educations','ranks','organizations','departments'));
    }

    //新增或编辑代理人（逻辑）
    public function proxyStore(Request $request)
    {
        $data = [
            'code' => '400',
            'message' => '保存失败',
            'data' => []
        ];
        

        // $rules = [
        //     'job_number' => 'required',
        //     'proxy_name' => 'bail|required|alpha',
        //     'id_card_number' => 'bail|required|number',
        //     'gender' => 'required|number',
        //     'phone_number' => 'required|number',
        //     'education_id' => 'required|number',
        //     'bank_name' => 'required|alpha',
        //     'bank_number' => 'required|number',
        //     'rank_id' => 'required|number',
        //     'organization_id' => 'required|number',
        //     'work_number' => 'bail|required|number',
        //     'work_type' => 'required|number',
        //     'entry_date' => 'required|number',
        //     'status' => 'required|number',
        // ];
        $requestData = array_filter($request->input());
        if(!isset($requestData['proxy_id'])){       //新增
            $proxy = ProxyModel::create($requestData);
            $proxy->password = Hash::make('123456');
            $proxy->save();
            if(!empty($proxy)){
                $data['code'] = 200;
                $data['message'] = '保存成功';
            }
        }else{      //保存
            //获取proxy
            $proxy = ProxyModel::find($requestData['proxy_id']);
            foreach($requestData as $key=>$value){
                //若值发生变化，则保存
                if($proxy->$key != $value){
                    $proxy->$key = $value;
                }

            }
            $proxy->save();
            $data['code'] = 200;
            $data['message'] = '保存成功';
        }
        
       
        return response()->json($data);
    }

    

    //编辑代理人（逻辑），暂时无用
    public function personUpdate($proxyId)
    {
        
        return '';
    }

    //删除代理人
    public function proxyDelete($proxyId)
    {
        $data = [
            'code' => '400',
            'message' => '删除失败',
            'data' => []
        ];

        $res = ProxyModel::destroy($proxyId);
        if(!empty($res)){
            $data['code'] = 200;
            $data['message'] = '删除成功';
        }
        return response()->json($data);
    }

    //部门列表
    public function departmentList(Request $request)
    {
        $requestData = array_filter($request->input());
        $where = [];
        foreach($requestData as $key=>$value){
            if($key != 'page'){
                if($key == 'department_name') {
                    array_unshift($where, ['departments.'.$key, 'like', '%' . $value . '%']);
                }else{
                    array_unshift($where,['departments.'.$key,'=',$value]);
                }
            }
        }
        //获取所有代理人
        $departments = DB::table('departments')->where($where)
            ->join('organizations','departments.organization_id','organizations.organization_id')
            ->select('departments.*','organizations.organization_name')
            ->paginate(10);
        $departments = $departments->appends($requestData);
        //搜索框数据
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        return view('person.department_list',compact('departments','requestData','organizations'));
    }

    //新增部门（页面）
    public function departmentAdd()
    {
        // //机构记录
        $organizations = DB::table('organizations')->get();
        return view('person.department_add',compact('organizations'));
    }
    //编辑部门（页面）
    public function departmentEdit($departmentId)
    {
        //部门
        $department = DB::table('departments')->where('department_id',$departmentId)->first();
        //机构记录
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);
        return view('person.department_add',compact('department','organizations'));
    }

    //新增/编辑部门（逻辑）
    public function departmentStore(Request $request)
    {
        $data = [
            'code' => '400',
            'message' => '保存失败',
            'data' => []
        ];
        
        $requestData = array_filter($request->input());
        
        if(!isset($requestData['department_id'])){       //新增
            $requestData['current_grade'] = $requestData['initial_grade'];
            $department = DB::table('departments')->insert($requestData);
            if(!empty($department)){
                $data['code'] = 200;
                $data['message'] = '保存成功';
            }
        }else{      //保存
            $department = DB::table('departments')->where('department_id',$requestData['department_id'])->first();
            foreach($requestData as $key=>$value){
                //若值发生变化，则保存
                if($department->$key == $value){
                    unset($requestData[$key]);
                }
            }
            if(!empty($requestData)){
                $department = DB::table('departments')->where('department_id',$department->department_id)->update($requestData);
            }
            
            $data['code'] = 200;
            $data['message'] = '保存成功';
        }
        return response()->json($data);
    }

    //删除部门
    public function departmentDelete($departmentId)
    {
        $data = [
            'code' => '400',
            'message' => '删除失败',
            'data' => []
        ];
        //检查是否有组员
        $proxys = ProxyModel::where('department_id',$departmentId)->get();
        if(!$proxys->isEmpty()){
            $data['message'] = '部门内还有组员';
            return response()->json($data);
        }
        $res = DB::table('departments')->where('department_id',$departmentId)->delete();
        if(!empty($res)){
            $data['code'] = 200;
            $data['message'] = '删除成功';
        }
        return response()->json($data);
    }


    //单位列表
    public function organizationList(Request $request)
    {
        $requestData = array_filter($request->input());
        $where = [];
        foreach($requestData as $key=>$value){
            if($key != 'page'){
                if($key == 'department_name') {
                    array_unshift($where, ['departments.'.$key, 'like', '%' . $value . '%']);
                }else{
                    array_unshift($where,['departments.'.$key,'=',$value]);
                }
            }
        }
        //获取所有代理人
        $departments = DB::table('departments')->where($where)
            ->join('organizations','departments.organization_id','organizations.organization_id')
            ->select('departments.*','organizations.organization_name')
            ->paginate(10);
        $departments = $departments->appends($requestData);
        //搜索框数据
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        return view('person.department_list',compact('departments','requestData','organizations'));
    }

    //新增部门（页面）
    public function departmentAdd()
    {
        // //机构记录
        $organizations = DB::table('organizations')->get();
        return view('person.department_add',compact('organizations'));
    }
    //编辑部门（页面）
    public function departmentEdit($departmentId)
    {
        //部门
        $department = DB::table('departments')->where('department_id',$departmentId)->first();
        //机构记录
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);
        return view('person.department_add',compact('department','organizations'));
    }

    //新增/编辑部门（逻辑）
    public function departmentStore(Request $request)
    {
        $data = [
            'code' => '400',
            'message' => '保存失败',
            'data' => []
        ];
        
        $requestData = array_filter($request->input());
        
        if(!isset($requestData['department_id'])){       //新增
            $requestData['current_grade'] = $requestData['initial_grade'];
            $department = DB::table('departments')->insert($requestData);
            if(!empty($department)){
                $data['code'] = 200;
                $data['message'] = '保存成功';
            }
        }else{      //保存
            $department = DB::table('departments')->where('department_id',$requestData['department_id'])->first();
            foreach($requestData as $key=>$value){
                //若值发生变化，则保存
                if($department->$key == $value){
                    unset($requestData[$key]);
                }
            }
            if(!empty($requestData)){
                $department = DB::table('departments')->where('department_id',$department->department_id)->update($requestData);
            }
            
            $data['code'] = 200;
            $data['message'] = '保存成功';
        }
        return response()->json($data);
    }

    //删除部门
    public function departmentDelete($departmentId)
    {
        $data = [
            'code' => '400',
            'message' => '删除失败',
            'data' => []
        ];
        //检查是否有组员
        $proxys = ProxyModel::where('department_id',$departmentId)->get();
        if(!$proxys->isEmpty()){
            $data['message'] = '部门内还有组员';
            return response()->json($data);
        }
        $res = DB::table('departments')->where('department_id',$departmentId)->delete();
        if(!empty($res)){
            $data['code'] = 200;
            $data['message'] = '删除成功';
        }
        return response()->json($data);
    }

    public function getJobNumber(){
        $jobNumber = ProxyModel::max('job_number');
        if(empty($jobNumber)){
            return '3133100001';
        }
        $jobNumber++;
        while(true){
            $temp = str_split($jobNumber);
            if(!in_array(4,$temp)){     //排除4
                break;
            }
            $jobNumber++;
        }
        return $jobNumber;
    }

    public function test()
    {
        $sheet = [
            '2022/8/10','2022-08-10','2022/8/10','2022-08-10','2022/8/10','2022-08-10','2022/8/10','2022-08-10','2022/8/10','2022-08-10',
        ];

        //投保人生日
        if(!empty($sheet[8])){
            if(strpos($sheet[8],'/')){
                $sheet[8] = str_replace('/','-',$sheet[8]);
            }
            if(date('Y-m-d',strtotime($sheet[8])) != $sheet[8]){
                if(date('Y-n-j',strtotime($sheet[8])) != $sheet[8]){
                    $sign = false;
                    $message = '投保人生日格式错误;';
                }
            }
            $sheet[8] = date('Y-m-d',strtotime($sheet[8]));
        }
        dd($sheet);
        // $date = '2022-8-07';
        // if(!empty($date) && (date('Y-n-j',strtotime($date)) != $date)){
        //     dd(500);
        // }
        // dd(200);
        
    }
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
        $payableSettlementDate = date('Y-m-20',strtotime('+1 month',strtotime($requestData['underwriting_date'])));
        
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
            //当犹豫期大于预期结算日期
            if($payableSettlementDate < $hesitationPeriodLastDate){
                //都在同一月时，应结算日期为前应结算日期的下一月
                if(date('m',strtotime($payableSettlementDate)) == date('m',strtotime($hesitationPeriodLastDate))){
                    $payableSettlementDate = date('Y-m-20',strtotime('+1 month',strtotime($payableSettlementDate)));
                }
                //犹豫期月份大于应结算日期，判断犹豫期日期是否大于当月20日，若小于等于，则应结算日期为前应结算日期的下一月，否则应结算日期为犹豫期的下一月20日
                if(date('m',strtotime($payableSettlementDate)) < date('m',strtotime($hesitationPeriodLastDate))){

                }
            }
            if($payableSettlementDate < $hesitationPeriodLastDate){
                //都在同一月时，应结算日期为前应结算日期的下一月
                if(date('m',strtotime($payableSettlementDate)) == date('m',strtotime($hesitationPeriodLastDate))){
                    $payableSettlementDate = date('Y-m-20',strtotime('+1 month',strtotime($payableSettlementDate)));
                }
                //犹豫期月份大于应结算日期，判断犹豫期日期是否大于当月20日，若小于等于，则应结算日期为前应结算日期的下一月，否则应结算日期为犹豫期的下一月20日
                if(date('m',strtotime($payableSettlementDate)) < date('m',strtotime($hesitationPeriodLastDate))){
                    if($hesitationPeriodLastDate <= date('Y-m-20',strtotime($hesitationPeriodLastDate))){
                        $payableSettlementDate = date('Y-m-20',strtotime('+1 month',strtotime($payableSettlementDate)));
                    }else{
                        $payableSettlementDate = date('Y-m-20',strtotime('+1 month',strtotime($hesitationPeriodLastDate)));
                    }
                }
            }
        }
        //当回访时间存在时，根据回访时间判断是否超期
        if(isset($requestData['receipt_date']) && isset($requestData['return_visit_date'])){
            if($payableSettlementDate < $requestData['return_visit_date']){
                $payableSettlementDate = date('Y-m-20',strtotime('+1 month',strtotime($requestData['return_visit_date'])));

                //都在同一月时，应结算日期为前应结算日期的下一月
                if(date('m',strtotime($payableSettlementDate)) == date('m',strtotime($requestData['return_visit_date']))){
                    $payableSettlementDate = date('Y-m-20',strtotime('+1 month',strtotime($payableSettlementDate)));
                }
                //回访日期月份大于应结算日期，判断犹豫期日期是否大于当月20日，若小于等于，则应结算日期为前应结算日期的下一月，否则应结算日期为回访日期的下一月20日
                if(date('m',strtotime($payableSettlementDate)) < date('m',strtotime($requestData['return_visit_date']))){
                    if($requestData['return_visit_date'] <= date('Y-m-20',strtotime($requestData['return_visit_date']))){
                        $payableSettlementDate = date('Y-m-20',strtotime('+1 month',strtotime($payableSettlementDate)));
                    }else{
                        $payableSettlementDate = date('Y-m-20',strtotime('+1 month',strtotime($requestData['return_visit_date'])));
                    }
                }
            }
        }
        $data['code'] = 200;
        $data['date'] = $payableSettlementDate;
        return $data;
    }

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

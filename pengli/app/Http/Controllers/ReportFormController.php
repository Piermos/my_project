<?php

namespace App\Http\Controllers;

use App\Exports\ContractExport;
use App\Exports\ContractExportT;
use App\Imports\ContractImport;
use App\Imports\ImportContract;
use App\Models\ContractModel;
use App\Models\Demo;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Exceptions\LaravelExcelException;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Writers\CellWriter;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;


class ReportFormController extends Controller
{

    /**
     * 新契约导入
     *
     * @return void
     */
    public function newContractImport()
    {
        $message = '';
        return view('report_form.new_contract_import',compact('message'));
    }

    public function newContractImportControl(Request $request)
    {
        $data = [
            'code' => '400',
            'message' => '操作失败',
            'data' => []
        ];
        if (!$request->hasFile('file')) {
            return $this->response()->error('请选择文件后在提交');
        }
        //获取文件对象
        $oFile = $request->file('file');
        if ($oFile->isValid()) {
            //判断文件扩展是否符合要求
            $aAllowExtension = ['xls', 'csv', 'xlsx'];
            $sFileExtension = $oFile->getClientOriginalExtension();     //文件后缀
            if (!in_array($sFileExtension, $aAllowExtension)) {
                $data['message'] = '您上传的文件后缀不支持，支持' . implode(',', $aAllowExtension);
                return response()->json($data);
            }
            //判断大小是否符合20M
            if ($oFile->getSize() >= 20480000) {
                $data['message'] = '您上传的的文件过大，最大20M';
                return response()->json($data);
            }
            //存储
            $path = $oFile->store('uploads/new_contract');
            //获取数据部分
            $array = Excel::toArray(new ImportContract, $path);
            $sheet1 = $array[0];
            //判断表头是否符合规范
            $aExampleTitle = ['保单号','保险公司','产品','期交保费','交费期间','标准保费','投保人','投保人性别',
                                '投保人出生日期','保单状态','投保日期','承保日期','回执日期','回访日期','机构','部门','业务员'];  //规范的表头
            $headings = $sheet1[0];     //表头
            //判断符合模板表头
            if ( ! empty(array_diff( $headings, $aExampleTitle))) {
                $data['message'] = '上传的表格格式不符合规范，请下载示例后填充数据并上传';
                return response()->json($data);
            }
            unset($sheet1[0]);  //去除表头
            Storage::delete($path); //删除表格
            $contractData = [];
            $sign = true;
            foreach($sheet1 as $key=>$sheet){
                $handleData = $this->handleValue($sheet);
                if(!$handleData['sign']){
                    $sign = $handleData['sign'];
                    $data['message'] = $handleData['message'];
                    break;
                }
                $contractData[] = $handleData['data'];
            }
            if($sign){
                $data['code'] = 200;
                $data['message'] = '上传成功';
                foreach($contractData as $value){
                    ContractModel::create($value);
                }
            }
            
            
        }
        return response()->json($data);
    }

    /**
     * 处理并检查信息
     *
     * @param [type] $sheet
     * @return void
     */
    public function handleValue($sheet){
        $sign = true;
        $message = '';
        //保险公司
        $insuranceCompanyId = DB::table('insurance_companys')->where('insurance_company_name',$sheet[1])->value('insurance_company_id');    //险司
        if(empty($insuranceCompanyId)){
            $sign = false;
            $message = '保险公司错误;';
        }
        //产品
        $productId = DB::table('products')->where('product_name',$sheet[2])->value('insurance_company_id');    //产品
        if(empty($productId)){
            $sign = false;
            $message .= '产品错误;';
        }
        if(!is_numeric($sheet[4])){
            $sign = false;
            $message .= '期交保费格式错误;';
        }
        //佣金
        $commission = DB::table('commissions')->where('product_id',$productId)->where('payment_period',$sheet[4])->first();    //佣金表ID
        $commission = json_decode(json_encode($commission), true);
        if(empty($commission)){
            $sign = false;
            $message .= $commission['first_year_rate'].'交费期间错误;';
        }
        if(!is_numeric($sheet[5])){
            $sign = false;
            $message .= '标准保费格式错误;';
        }
        //投保人生日
        if(!empty($sheet[8])){
            if(strpos($sheet[8],'/')){
                $sheet[8] = str_replace('/','-',$sheet[8]);
            }
            if(date('Y-m-d',strtotime($sheet[8])) != $sheet[8]){
                if(date('Y-n-j',strtotime($sheet[8])) != $sheet[8]){
                    $sign = false;
                    $message .= '投保人生日格式错误;';
                }
            }
            $sheet[8] = date('Y-m-d',strtotime($sheet[8]));
        }
        
        //保单状态
        $policyStatusId = DB::table('policy_status')->where('policy_status_name',$sheet[9])->value('policy_status_id');    //状态
        if(empty($policyStatusId)){
            $sign = false;
            $message .= '保单状态错误;';
        }
        //录单日期
        if(!empty($sheet[10])){
            if(strpos($sheet[10],'/')){
                $sheet[10] = str_replace('/','-',$sheet[10]);
            }
            if(date('Y-m-d',strtotime($sheet[10])) != $sheet[10] && date('Y-n-j',strtotime($sheet[10])) != $sheet[10]){
                $sign = false;
                $message .= '录单日期格式错误;';
            }
            $sheet[10] = date('Y-m-d',strtotime($sheet[10]));
        }
        //承保日期
        if(!empty($sheet[11])){
            if(strpos($sheet[11],'/')){
                $sheet[11] = str_replace('/','-',$sheet[11]);
            }
            if(date('Y-m-d',strtotime($sheet[11])) != $sheet[11] && date('Y-n-j',strtotime($sheet[11])) != $sheet[11]){
                $sign = false;
                $message .= '承保日期格式错误;';
            }
            $sheet[11] = date('Y-m-d',strtotime($sheet[11]));
        }
        //回执日期
        if(!empty($sheet[12])){
            if(strpos($sheet[12],'/')){
                $sheet[12] = str_replace('/','-',$sheet[12]);
            }
            if(date('Y-m-d',strtotime($sheet[12])) != $sheet[12] && date('Y-n-j',strtotime($sheet[12])) != $sheet[12]){
                $sign = false;
                $message .= '回执日期格式错误;';
            }
            $sheet[12] = date('Y-m-d',strtotime($sheet[12]));
        }
        //回访日期
        if(!empty($sheet[13])){
            if(strpos($sheet[13],'/')){
                $sheet[13] = str_replace('/','-',$sheet[13]);
            }
            if(date('Y-m-d',strtotime($sheet[13])) != $sheet[13] && date('Y-n-j',strtotime($sheet[13])) != $sheet[13]){
                $sign = false;
                $message .= '回访日期格式错误;';
            }
            $sheet[13] = date('Y-m-d',strtotime($sheet[13]));
        }

        $organizationId = DB::table('organizations')->where('organization_name',$sheet[14])->value('organization_id');    //机构
        if(empty($organizationId)){
            $sign = false;
            $message .= '机构不存在;';
        }
        $departmentId = DB::table('departments')->where('department_name',$sheet[15])->value('department_id');    //部门
        if(empty($departmentId)){
            $sign = false;
            $message .= '部门不存在;';
        }
        $proxyId = DB::table('proxys')->where('proxy_name',$sheet[16])->value('proxy_id');    //代理人
        if(empty($proxyId)){
            $sign = false;
            $message .= '业务员不存在;';
        }
        $settlementRate = $commission['first_year_rate'];
        $settlementFyc = $settlementRate*$sheet[4]/100;
        $contractData = [
            'policy_number' => $sheet[0],                     //保单号
            'insurance_company_id' => $insuranceCompanyId,              //保险公司
            'product_id' => $productId,                        //产品
            'regular_premium' => $sheet[3],                   //期交保费保费
            'payment_period' => $sheet[4],                     //交费期间
            'standard_premium' => $sheet[5],                  //标准保费
            'policy_holder' => $sheet[6],                  //投保人
            'policy_holder_gender' => $sheet[7],                  //投保人性别
            'policy_holder_birth' => empty($sheet[8])?null:$sheet[8],                  //投保人出生日期
            'policy_status_id' => $policyStatusId,                  //保单状态
            'insurance_date' => empty($sheet[10])?null:$sheet[10],                 //录单时间
            'underwriting_date' => empty($sheet[11])?null:$sheet[11],                   //承保时间
            'receipt_date' => empty($sheet[12])?null:$sheet[12],                   //回执时间
            'return_visit_date' => empty($sheet[12])?null:$sheet[12],                   //回访时间
            'organization_id' => $organizationId,                  //机构
            'department_id' => $departmentId,                  //部门
            'proxy_id' => $proxyId,                  //业务员
            'settlement_rate' => $settlementRate,
            'settlement_fyc' => $settlementFyc,
        ];
        $data = [
            'sign' => $sign,
            'message' => $message,
            'data' => $contractData
        ];
        return $data;
        
    }

    /**
     * 下载新契约保单
     *
     * @param Request $request
     * @return void
     */
    public function newContractExport(Request $request)
    {
        $requestData = array_filter($request->input());
        $insuranceCompanys = DB::table('insurance_companys')->get(['insurance_company_id','insurance_company_name']);
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        $policyStatus = DB::table('policy_status')->get();     //保单状态
        $message = '';
        if(empty($requestData)){
            return view('report_form.new_contract_export',compact('requestData','insuranceCompanys','organizations','policyStatus','message'));
        }else{
            if(!isset($requestData['date_type'])){
                $msg = '请选择日期类型';
                $message = '<div class="color-red"><span>'.date('Y-m-d H:i:s').' '.$msg.'</span></div>';
                return view('report_form.new_contract_export',compact('requestData','insuranceCompanys','organizations','policyStatus','message'));
            }
            if(!isset($requestData['date_start']) || !isset($requestData['date_end'])){
                $msg = '请正确选择日期起始';
                $message = '<div class="color-red"><span>'.date('Y-m-d H:i:s').' '.$msg.'</span></div>';
                return view('report_form.new_contract_export',compact('requestData','insuranceCompanys','organizations','policyStatus','message'));
            }
            
            $where = function($query) use ($requestData){
                if($requestData['date_type'] == 'underwriting'){
                    $query->whereBetween('contracts.insurance_date',[$requestData['date_start'],$requestData['date_end']]);
                }
                $sign = 'contracts.'.$requestData['date_type'].'_date';
                $query->whereBetween($sign,[$requestData['date_start'],$requestData['date_end']]);
                       
                foreach($requestData as $key=>$value){
                    if($key == 'insurance_company_id' || $key == 'organization_id' || $key == 'policy_status_id'){
                        $query->where('contracts.'.$key , '=' , $value);
                    }
                }
            };
            return new ContractExport($where);
        }
        
    }

    //导入文件并保存
    public function importFile(Request $request)
    {
        $data = [
            'code' => '400',
            'message' => '操作失败',
            'data' => []
        ];

        $id = $request->input('id');
        $file = $request->file('file');
        if($id == 'newContract'){       //上传新契约
            $fileExtension = $file->extension();
            //检查文件后缀
            $allwoExtensions = ['xlsx','xls'];
            if(!in_array($fileExtension,$allwoExtensions)){
                $data['message'] = '请上传excel文件';
                return response()->json($data);
            }
            
        }
        //保存方案文件
        if($id == 'saveSchemeDocument'){       //保存方案文件
            $fileName = $file->getClientOriginalName();     //原文件名
            $path = '方案文件';
            $destinationPath = 'uploads/'.$path;
            is_dir($destinationPath) or mkdir($destinationPath,0777,true);      //若无文件夹则上传
            $newPath = $file->storeAs($destinationPath,$fileName);
            if(!empty($newPath)){
                $data['code'] = 200;
                $data['message'] = '操作成功';
            }
        }
        return response()->json($data);
    }


}

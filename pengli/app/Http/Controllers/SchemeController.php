<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchemeController extends Controller
{
    //方案列表
    public function schemeList()
    {
        $schemes = DB::table('schemes')->orderBy('scheme_id','DESC')->paginate(10);;     //方案
        return view('scheme.scheme_list',compact('schemes'));
    }

    /**
     * 方案新增
     */
    public function schemeAdd()
    {
        return view('scheme.scheme_add');
    }

    /**
     * 方案编辑
     */
    public function schemeEdit($schemeId)
    {
        $scheme = DB::table('schemes')->where('scheme_id',$schemeId)->first();
        return view('scheme.scheme_add',compact('scheme'));
    }


    /**
     * 方案新增或修改保存
     */
    public function schemeStore(Request $request)
    {
        $data = [
            'code' => '400',
            'message' => '保存失败',
            'data' => []
        ];
        $requestData = array_filter($request->input());
        //验证数据
        if(isset($requestData['scheme_id'])){
            $where = ['scheme_id' => $requestData['scheme_id']];
        }else{
            $where = ['scheme_name' => $requestData['scheme_name']];
        }
        $res = DB::table('schemes')->updateOrInsert($where,$requestData);
        if(!empty($res)){
            $data['code'] = 200;
            $data['message'] = '保存成功';
        }
        return response()->json($data);
    }

    /**
     * 方案任务分解
     */
    public function decomposeGoals($schemeId)
    {
        $scheme = DB::table('schemes')->where('scheme_id',$schemeId)->first();
        $schemeName = $scheme->scheme_name;
        //检查任务分解表中是否创建
        $schemeTragets = DB::table('scheme_traget')->where('scheme_id',$schemeId)
                                ->join('organizations','scheme_traget.organization_id','organizations.organization_id')
                                ->select('scheme_traget.*','organizations.organization_name')
                                ->get();
        if($schemeTragets->isEmpty()){
            //获取单位集
            $organizations = DB::table('organizations')->where('status',1)->get();
            $arr = [];
            foreach($organizations as $organization){
                $arr[] = [
                    'scheme_id' => $schemeId,
                    'organization_id' => $organization->organization_id
                ];
            }
            //生成新的方案单位记录
            DB::table('scheme_traget')->insert($arr);
            $schemeTragets = DB::table('scheme_traget')->where('scheme_id',$schemeId)
                                ->join('organizations','scheme_traget.organization_id','organizations.organization_id')
                                ->select('scheme_traget.*','organizations.organization_name')
                                ->get();
        }

        return view('scheme.scheme_decompose_goals',compact('schemeName','schemeTragets'));
    }

    /**
     * 方案任务分解保存
     */
    public function decomposeGoalsStore($schemeId,$role)
    {
        $scheme = DB::table('schemes')->where('scheme_id',$schemeId)->first();
        return view('scheme.scheme_decompose_goals.blade',compact('scheme'));
    }

    public function schemeDelete($schemeId)
    {
        $data = [
            'code' => '400',
            'message' => '删除失败',
            'data' => []
        ];
        $res = DB::table('schemes')->where('scheme_id',$schemeId)->delete();
        if(!empty($res)){
            $data['code'] = 200;
            $data['message'] = '删除成功';
        }
        return response()->json($data);

    }


    /**
     * 公司各单位方案达成数据
     */
    public function companyData(Request $request)
    {
        $requestData = array_filter($request->input());
        //获取当前方案
        if(empty($requestData)){

        }
        //如果
        $organizations = [];
        
        $schemes = DB::table('schemes')->orderBy('scheme_start_date','DESC')->get(['scheme_id','scheme_name']);     //方案
        $organizations = DB::table('organizations')->paginate(10);
        foreach($organizations->items() as &$organization){
            $organization->premium_target = 80000000;
        }
        return view('scheme.scheme_company',compact('organizations','requestData','schemes'));

    }

    

    /**
     * 机构方案达成数据
     */
    public function organizationData(Request $request)
    {
        $requestData = array_filter($request->input());
        $departments = [];
        //获取当前方案
        if(empty($requestData)){

        }
        $departments = DB::table('departments')
            ->paginate(10);
        $schemes = DB::table('schemes')->orderBy('scheme_start_date','DESC')->get(['scheme_id','scheme_name']);     //方案
        $organizations = DB::table('organizations')->get(['organization_id','organization_name']);     //机构
        return view('scheme.scheme_organization',compact('departments','requestData','schemes','organizations'));

    }
}

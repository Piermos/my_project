<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //险司列表
    public function insuranceCompanyList(Request $request)
    {
        $requestData = array_filter($request->input());
        $where = function($query) use ($requestData){
            //其他条件
            foreach($requestData as $key=>$value){
                if($key == 'page'){
                    continue;
                }
                if($key == 'insurance_company_name'){
                    $query->where('insurance_companys.'.$key , 'like' , '%' .$value. '%');
                }else{
                    $query->where('insurance_companys.'.$key , '=' , $value);
                }
            }
        };
        $insuranceCompanys = DB::table('insurance_companys')->where($where)
            ->paginate(10);
            // dd($insuranceCompanys);
        return view('product.insurance_company_list',compact('insuranceCompanys','requestData'));
    }

    //产品列表
    public function productList(Request $request)
    {
        $requestData = array_filter($request->input());
        $where = [];
        foreach($requestData as $key=>$value){
            if($key != 'page'){
                if($key == 'product_name') {
                    array_unshift($where, ['products.'.$key, 'like', '%' . $value . '%']);
                }else{
                    array_unshift($where,['products.'.$key,'=',$value]);
                }
            }
        }
        $products = DB::table('products')->where($where)
            ->join('insurance_companys','products.insurance_company_id','insurance_companys.insurance_company_id')
            ->join('product_categorys','products.product_category_id','product_categorys.product_category_id')
            ->select('products.*','insurance_companys.insurance_company_name','product_categorys.product_category_name')
            ->paginate(10);
        $products->appends($requestData);
        $insuranceCompanys = DB::table('insurance_companys')->get();
        $productCategorys = DB::table('product_categorys')->get();
        // dd($insuranceCompanys);
        return view('product.product_list',compact('products','requestData','insuranceCompanys','productCategorys'));
    }

    /**
     * 新增产品（页面）
     *
     * @return void
     */
    public function productAdd()
    {
        //保险公司
        $insuranceCompanys = DB::table('insurance_companys')->get();
        $productCategorys = DB::table('product_categorys')->get();
        return view('product.product_add',compact('insuranceCompanys','productCategorys'));
    }

    //编辑产品（页面）
    public function productEdit($productId)
    {
        $product = DB::table('products')->where('product_id',$productId)
            ->join('insurance_companys','products.insurance_company_id','insurance_companys.insurance_company_id')
            ->select('products.*','insurance_companys.insurance_company_name')
            ->first();
        //检查产品是否存在

        //产品
        $commissions = DB::table('commissions')->where('commissions.product_id',$productId)->get();
        $insuranceCompanys = DB::table('insurance_companys')->get();
        $productCategorys = DB::table('product_categorys')->get();
        return view('product.product_add',compact('commissions','product','insuranceCompanys','productCategorys'));
    }

    //新增或编辑保存产品
    public function productSave(Request $request)
    {
        $data = [
            'code' => '400',
            'message' => '保存失败',
            'data' => []
        ];

        $requestData = array_filter($request->input(), 'strlen');   //保留数字0
        $productId = isset($requestData['product_id'])?$requestData['product_id']:'';
        $productName = $requestData['product_name'];
        $insuranceCompanyId = $requestData['insurance_company_id'];
        $productCategoryId = $requestData['product_category_id'];
        $productStatus = $requestData['product_status'];
        $productData = [
            'product_name' => $productName,
            'insurance_company_id' => $insuranceCompanyId,
            'product_category_id' => $productCategoryId,
            'product_status' => $productStatus,
        ];
        
        if(empty($productId)){
            $products = DB::table('products')->where('product_name',$productName)->get();
            if($products->isEmpty()){
                $productId = DB::table('products')->insertGetId($productData);
            }else{
                $data['message'] = '该产品已存在';
                return response()->json($data);
            }
        }else{
            $products = DB::table('products')->where('product_id',$productId)->update($productData);
        }
        $temp = [];
        foreach($requestData as $key=>$value){
            if($key == 'product_id' || $key == 'product_name' || $key == 'insurance_company_id' || $key == 'product_category_id' || $key == 'product_status'){
                continue;
            }
            $commission_id = substr($key,0,strpos($key, '_'));
            $colume = substr($key,strpos($key, '_')+1);
            if(!isset($temp[$commission_id]['product_id'])){
                $temp[$commission_id]['product_id'] = $productId;
            }
            $temp[$commission_id][$colume] = $value;
        }

        if(isset($temp['new'])){      //新建
            $res = DB::table('commissions')->insert($temp);
            if(!empty($res)){
                $data['code'] = 200;
                $data['message'] = '保存成功';
            }
        }else{      //编辑
            foreach($temp as $key=>$value){
                $res = DB::table('commissions')->where('commission_id',$key)->update($value);
            }
            $data['code'] = 200;
            $data['message'] = '保存成功';
        }
        return response()->json($data);
    }
}

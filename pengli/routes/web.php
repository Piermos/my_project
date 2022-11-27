<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InsurancePolicyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportFormController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\WagesController;
use App\Models\User;
use Illuminate\Http\Request;

Route::get('index/{id}',[IndexController::class,'index']);

Route::get('/test',[PersonController::class,'test']);

//登录页面
Route::get('/login',[LoginController::class,'login']);
//登录行为
Route::post('/login',[LoginController::class,'doLogin']);
//登出
Route::get('/logout',[LoginController::class,'logout']);

//首页
Route::get('/',[IndexController::class,'home'])->middleware('checkLogin');
Route::get('/home',[IndexController::class,'home'])->middleware('checkLogin');
Route::get('/welcome',[IndexController::class,'welcome'])->middleware('checkLogin');

//保单管理
Route::prefix('/contract')->group(function(){
    //新契约保单列表
    Route::get('/new_contract/list',[ContractController::class,'newContractList']);
    //新增新契约保单（页面）
    Route::get('/new_contract/add',[ContractController::class,'newContractAdd']);
    //编辑新契约保单（页面）
    Route::get('/new_contract/list/{contractId}/edit',[ContractController::class,'newContractEdit']);
    //新增与编辑信息检索
    Route::post('/new_contract/add/retrieval',[ContractController::class,'newContractAddRetrieval']);
    //新增或编辑保存保单（逻辑）
    Route::post('/new_contract/list',[ContractController::class,'newContractStore']);
    //待回执回销列表
    Route::get('/new_contract/wait_receipt_list',[ContractController::class,'waitReceiptList']);
    //待回访列表
    Route::get('/new_contract/return_visit_list',[ContractController::class,'returnVisitList']);

    //团财险保单列表
    Route::get('/group_property/list',[ContractController::class,'groupPropertyList']);
    //新增团财险保单（页面）
    Route::get('/group_property/add',[ContractController::class,'groupPropertyAdd']);
    //编辑新契约保单（页面）
    Route::get('/group_property/list/{groupPropertyId}/edit',[ContractController::class,'groupPropertyEdit']);
    //新增或编辑保存团财险保单（逻辑）
    Route::post('/group_property/list',[ContractController::class,'groupPropertyStore']);
    //删除团财险保单
    Route::delete('/group_property/delete/{groupPropertyId}',[ContractController::class,'groupPropertyDelete']);
});

//工资管理
Route::prefix('/wages')->group(function(){
    //本期工资明细
    Route::get('/current_payroll',[WagesController::class,'currentPayroll']);
    //生成本期工资
    Route::post('/generate_current_payroll',[WagesController::class,'generateCurrentPayroll']);
    //工资数据刷新

    //确定工资明细

    //工资单
    Route::get('/payroll',[WagesController::class,'payroll']);
    //工资条
    Route::get('/payroll/{wagesId}/payslip',[WagesController::class,'payslip']);
    //工资单保存(逻辑)
    Route::post('/payroll',[WagesController::class,'payslipSave']);
    
    //寿险结算
    Route::get('/life_insurance/settled',[WagesController::class,'lifeInsuranceSettled']);
    //寿险未结算编辑
    Route::get('/life_insurance/settled/{contractId}/edit',[WagesController::class,'lifeInsuranceSettledEdit']);
    //寿险未结算编辑（逻辑）
    Route::post('/life_insurance/settled',[WagesController::class,'lifeInsuranceSettledSave']);
    
});

//方案管理
Route::prefix('/scheme')->group(function(){
    //方案列表
    Route::get('/list',[SchemeController::class,'schemeList']);
    //方案新增页面
    Route::get('/add',[SchemeController::class,'schemeAdd']);
    //方案编辑页面
    Route::get('/{scheme_id}/edit',[SchemeController::class,'schemeEdit']);
    //方案编辑/新增保存
    Route::post('/list',[SchemeController::class,'schemeStore']);
    //方案任务分解页面
    Route::get('/{scheme_id}/decompose_goals',[SchemeController::class,'decomposeGoals']);
    //方案任务分解保存
    Route::post('/decompose_goals',[SchemeController::class,'decomposeGoalsStore']);
    //删除
    Route::delete('/{groupPropertyId}/delete',[SchemeController::class,'schemeDelete']);
    

    //公司各单位方案达成
    Route::get('/company_data',[SchemeController::class,'companyData']);
   
    //单位各团队方案达成
    Route::get('/organization_data',[SchemeController::class,'organizationData']);
    
});

//报表中心
Route::prefix('/report_form')->group(function(){
    //新契约保单导入
    Route::get('/new_contract/import',[ReportFormController::class,'newContractImport']);
    Route::post('/new_contract/import',[ReportFormController::class,'newContractImportControl']);
    //新契约保单导出
    Route::get('/new_contract/export',[ReportFormController::class,'newContractExport']);
    
});

//人员管理
Route::prefix('/person')->group(function(){
    //代理人列表
    Route::get('/proxy/list',[PersonController::class,'proxyList']);
    //新增与编辑代理人信息检索
    Route::post('/proxy/add/retrieval',[PersonController::class,'proxyAddRetrieval']);
    //新增代理人（页面）
    Route::get('/proxy/add',[PersonController::class,'proxyAdd']);
    //编辑代理人（页面）
    Route::get('/proxy/list/{proxyId}/edit',[PersonController::class,'proxyEdit']);
    //新增或编辑代理人（逻辑）
    Route::post('/proxy/list',[PersonController::class,'proxyStore']);
    // //编辑代理人（逻辑）
    // Route::put('/proxy/list/{proxyId}',[PersonController::class,'personUpdate']);
    //删除代理人
    Route::delete('/proxy/list/delete/{proxyId}',[PersonController::class,'proxyDelete']);

    //部门列表
    Route::get('/department/list',[PersonController::class,'departmentList']);
    //新增部门（页面）
    Route::get('/department/add',[PersonController::class,'departmentAdd']);
    //编辑部门（页面）
    Route::get('/department/list/{departmentId}/edit',[PersonController::class,'departmentEdit']);
    //新增部门（逻辑）
    Route::post('/department/list',[PersonController::class,'departmentStore']);
    //删除部门
    Route::delete('/department/list/delete/{departmentId}',[PersonController::class,'departmentDelete']);

    //单位列表
    Route::get('/organization/list',[PersonController::class,'organizationList']);
    //新增单位（页面）
    Route::get('/organization/add',[PersonController::class,'organizationAdd']);
    //编辑单位（页面）
    Route::get('/organization/list/{organizationId}/edit',[PersonController::class,'organizationEdit']);
    //新增单位（逻辑）
    Route::post('/organization/list',[PersonController::class,'organizationStore']);
    //删除部门
    Route::delete('/organization/list/delete/{organizationId}',[PersonController::class,'organizationDelete']);

});

//产品管理
Route::prefix('/product')->group(function(){
    //险司列表
    Route::get('/insurance_company/list',[ProductController::class,'insuranceCompanyList']);
    //产品列表
    Route::get('/list',[ProductController::class,'productList']);
    //新增产品（页面）
    Route::get('/add',[ProductController::class,'productAdd']);
    //编辑产品（页面）
    Route::get('/list/{productId}/edit',[ProductController::class,'productEdit']);
    //新增或编辑佣金
    Route::post('/list',[ProductController::class,'productSave']);
});



//权限管理
Route::prefix('/permission')->group(function(){
    //角色列表
    Route::get('/role',[PermissionController::class,'roleList']);
    //权限分配
    Route::get('/authorization/{roleId}',[PermissionController::class,'authorization']);     //路由参数，需要通过$request->route('id')来获取，或者直接注入参数
    //权限分配保存
    Route::post('/authorization',[PermissionController::class,'authorizationSave']);
});



//添加角色
Route::post('/permission/role',[PermissionController::class,'roleStore']);

//
Route::prefix('/insurance_policy')->group(function(){
    //保单列表
    Route::get('/lists',[InsurancePolicyController::class,'lists']);
    //保单详情
    Route::get('/lists/{insurancePolicy}',[InsurancePolicyController::class,'details']);
    //创建保单（至页面）
    Route::get('/lists/create',[InsurancePolicyController::class,'create']);
    //创建保单（逻辑）
    Route::post('/lists',[InsurancePolicyController::class,'store']);
    //编辑保单(至页面)
    Route::get('/lists/{insurancePolicy}/edit',[InsurancePolicyController::class,'edit']);
    //编辑保单（逻辑）
    Route::put('/lists/{insurancePolicy}',[InsurancePolicyController::class,'update']);
    //删除保单
    Route::delete('/lists/delete/{insurancePolicy}',[InsurancePolicyController::class,'delete']);

});








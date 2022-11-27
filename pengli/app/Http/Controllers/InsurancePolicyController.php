<?php

namespace App\Http\Controllers;

use App\Models\InsurancePolicyModel;
use Illuminate\Http\Request;

class InsurancePolicyController extends Controller
{
    //保单列表
    public function lists()
    {
        // $insurancePllicy = InsurancePolicyModel::find(1);
        // dd($insurancePllicy);
        return view('login/login');
    }

    //保单详情
    public function details()
    {
        return 'details';
    }

    //创建保单（至页面）
    public function create()
    {
        return 'create';
    }

    //创建保单（逻辑）
    public function store()
    {
        return 'store';
    }

    //编辑保单(至页面)
    public function edit()
    {
        return 'edit';
    }

    //编辑保单（逻辑）
    public function update()
    {
        return 'update';
    }

    //删除保单
    public function delete()
    {
        return 'delete';
    }
}

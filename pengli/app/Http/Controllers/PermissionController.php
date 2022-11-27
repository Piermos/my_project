<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    //角色列表
    public function roleList()
    {
        $roles = Role::get();
        return view('permission.role',compact('roles'));
    }

    //角色授权
    public function authorization(Request $request,$roleId)
    {
        
        $role = Role::find($roleId);
        $permissions = Permission::orderByRaw('level ASC')->get();   //所有的权限节点
        $perms = [];     //权限节点分类放入数组
        foreach($permissions as $permission){
            if($permission->level == 1){
                $perms[$permission->group][$permission->id] = $permission->toArray();
            }else{
                $perms[$permission->group][$permission->pid]['son'][] = $permission->toArray();
            }
        }
        //角色所属权限
        $own_perms = $role->permissions;
        $role_perm_ids = [];
        foreach($own_perms as $perm){
            $role_perm_ids[] = $perm->id;
        }
        return view('permission.authorization',compact('role','perms','role_perm_ids'));
    }

    //角色授权保存
    public function authorizationSave(Request $request)
    {
        $return = [
            'code' => 200,
            'message' => '保存成功'
        ];
        $data = $request->input();
        //获得role
        // $role = Role::find($data['id']);
        //删除所有权限
        DB::table('permission_role')->where('role_id',$data['id'])->delete();
        try{
            $insertData = [];
            foreach($data['permission_ids'] as $perm){
                $insertData[] = [
                    'role_id' => $data['id'],
                    'permission_id' => $perm
                ];
            }
            DB::table('permission_role')->insert($insertData);
        }catch(Exception $e){
            $return['code'] = 400;
            $return['code'] = '保存失败';
        }
        
        return response()->json($return);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    //
    public function index($id)
    {
        $res = User::find($id);
        $permission = [];
        // foreach($res as $role){
        //     $permission[] = Role::find($role->id)->permission;
        // }
        $res->load('role.permission');  //加载集合中所有模型的给定关联关系
        // if(in_array($access,$p)){

        // }
        $permission = $res->role[0]->permission;
        return $permission;
    }

    //首页
    public function home()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        //获取当前用户对应的角色
        $roles = $user->roles;
        //根据用户的角色，查找对应的权限
        $pers = [];
        foreach($roles as $role){
            $permissions = $role->permissions;
            foreach($permissions as $permission){
                $pers[$permission->id] = $permission;
            }
        }
        //对权限列表进行处理，分类放置
        $nodes = [];
        foreach($pers as $per){
            if($per->level == 1){
                $nodes[$per->group][] = $per->toArray();
            }
        }
        return view('index.home',['nodes'=>$nodes]);
    }

    /**
     * 递归函数，实现无限级分类列表
     * @param $list
     * @param int $pid
     * @param int $level
     * @return array
     */
    public function get_cate_list($list,$pid=0,$level=1)
    {
        static $tree = array();
        foreach ($list as $row){
            if($row['pid'] == $pid){
                $row['level'] = $level;
                $tree[] = $row;
                $this->get_cate_list($list,$row['id'],$level+1);
            }
        }
        return $tree;
    }

    //内容页
    public function welcome()
    {
        return view('index.welcome');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //登录页面
    public function login()
    {
        if(Auth::check()){
            return redirect('/home');
        }
        return view('login.login');
    }

    //登录行为
    public function doLogin(Request $request)
    {
        //验证
        $rules = [
            'username' => 'required | alpha',
            'password' => 'required | min:6'
        ];
        $message = [
            'username.required' => '用户名不能为空',
            'username.alpha' => '用户名必须为字符',
            'password.required' => '密码不能为空',
            'password.min' => '密码不能少于6位'
            
        ];
        
        $validator = Validator::make($request->all(),$rules,$message);
        if($validator->fails()){
            return redirect('login')->withErrors($validator)->withInput();
        }
        //逻辑
        $validated = $validator->validated();   //获取验证通过的参数
        if(Auth::attempt($validated)){
            $request->session()->regenerate();  //重新生成session
            //重定向到首页
            return redirect()->intended('home');    //将用户重定向到他们试图访问的 URL，然后被认证中间件截获
        }

        return back()->withErrors('用户名或密码错误')->withInput();
    }

    //登出
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();  //设置session过期
        $request->session()->regenerateToken(); //重新生成CSRG令牌
        return redirect('/login');

    }

}

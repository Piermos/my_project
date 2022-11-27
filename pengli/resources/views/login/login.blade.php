<!doctype html>
<html  class="x-admin-sm">
<head>
	<meta charset="UTF-8">
	<title>鹏利保代后台管理</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/login.css">
    <link rel="stylesheet" href="/css/xadmin.css">
<!--    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>-->
    <script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<body class="login-bg">
    <div class="login layui-anim layui-anim-up">
        <div class="message">后台管理系统登录</div>
        <div id="darkbannerwrap"></div>
        
        <form method="post" class="layui-form" action="/login">
            @csrf
            <input type="text" name="username" placeholder="用户名" autocomplete="off" lay-verify="required" class="layui-input" value="{{ old('username') }}">
            <hr class="hr15">
            <input type="password" name="password" placeholder="密码"  lay-verify="required"  class="layui-input" value="{{ old('password') }}">
            <hr class="hr15">
<!--            <input type="hidden" name="uniqid" id="uniqid" placeholder="验证码标识"  class="layui-input">-->
<!--            <input type="text" name="captcha" placeholder="验证码"  autocomplete="off" lay-verify="required" class="layui-input" style="width: 50%;display: inline-block">-->
<!--            <img id="captchaImg" src="" onclick="reloadImg()" alt="" style="width: 45%;display: inline-block">-->
<!--            <hr class="hr15">-->
            
            @if($errors->any())
                <div style="color:red">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>* {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- <input type="submit" value="'登录"> -->
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
    </div>

    <script type="text/javascript" src="/js/base.js"></script>
    <script src="/lib/layui/layui.js" charset="utf-8"></script>
    <script>
        $(function  () {
            layui.use('form', function(){
                $ = layui.jquery;
                var layer = layui.layer;
                var form = layui.form;
                // dologin();
                //用户名控件获取焦点
                // $('#username').focus();
                //回车登录
                $("input[name='username']")[0].focus();
                $('input').keydown(function (e) {
                    if (e.keyCode == 13){
                        dologin();
                    }
                });
            });
            //reloadImg();

        })
        
        
    </script>
    <!-- 底部结束 -->

</body>
</html>
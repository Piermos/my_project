<!DOCTYPE html>
<html class="x-admin-sm">
    
<head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.2</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/xadmin.css">
    <script type="text/javascript" src="/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/xadmin.js"></script>
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <style>
        button.layui-btn{
            width: 100px;margin-top: 50px;
        }
        .layui-form{
            text-align: center;
        }
        .layui-form .layui-form-item{
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">
            <input type="hidden" id="department_id" name="department_id" value="{{!empty($department)?$department->department_id:''}}" >
            <div class="layui-form-item">
                <label for="department_name" class="layui-form-label">
                    <span class="x-red">*</span>部门名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="department_name" name="department_name" lay-verify="required" value="{{!empty($department)?$department->department_name:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>部门主管
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="leader" name="leader" lay-verify="required" value="{{!empty($department)?$department->leader:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="initial_grade" class="layui-form-label">
                    <span class="x-red">*</span>部门职级</label>
                <div class="layui-input-inline">
                    <select id="initial_grade" name="initial_grade" class="valid" lay-verify="required" lay-search>
                        <option value="">请选择</option>
                        <option value="营业部" {{!empty($department)&&$department->initial_grade=='营业部'?'selected':''}}>营业部</option>
                        <option value="筹备营业部" {{!empty($department)&&$department->initial_grade=='筹备营业部'?'selected':''}}>筹备营业部</option>
                    </select>
                </div>
                @if(!empty($department))
                <label for="current_grade" class="layui-form-label">
                    <span class="x-red">*</span>当前职级</label>
                <div class="layui-input-inline">
                    <select id="current_grade" name="current_grade" class="valid" lay-verify="required" lay-search>
                        <option value="">请选择</option>
                        <option value="营业部" {{!empty($department)&&$department->current_grade=='营业部'?'selected':''}}>营业部</option>
                        <option value="筹备营业部" {{!empty($department)&&$department->current_grade=='筹备营业部'?'selected':''}}>筹备营业部</option>
                    </select>
                </div>
                @endif
            </div>
            <div class="layui-form-item">
                <label for="mechanism_id" class="layui-form-label">
                    <span class="x-red">*</span>机构</label>
                <div class="layui-input-inline">
                    <select id="organization_id" name="organization_id" class="valid" lay-filter="organization_id" lay-verify="required" lay-search>
                        <option value="">请选择机构</option>
                        @foreach($organizations as $organization)
                        <option value="{{$organization->organization_id}}" {{!empty($department)&&$department->organization_id==$organization->organization_id?'selected':''}}>{{$organization->organization_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="entry_date" class="layui-form-label">
                    <span class="x-red">*</span>入职日期
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="entry_date" name="entry_date" lay-verify="required" value="{{!empty($department)?$department->entry_date:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="department_status" class="layui-form-label">
                    <span class="x-red">*</span>部门状态</label>
                <div class="layui-input-inline">
                    <select id="department_status" name="department_status" class="valid" lay-verify="required">
                        <option value="1" {{!empty($department)&&$department->department_status == 1?'selected':''}}>正常</option>
                        <option value="2" {{!empty($department)&&$department->department_status == 2?'selected':''}}>撤销</option>
                    </select>
                </div>
            </div>

            <div class="layui-form-item" style="text-align: center;">
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    增加
                </button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/js/base.js"></script>
<script>
    layui.use(['form', 'layer','laydate'], function() {
        $ = layui.jquery;
        var form = layui.form,
            layer = layui.layer;
            laydate = layui.laydate;
        //执行一个laydate实例
        laydate.render({
            elem: '#entry_date', //指定元素
            @if(empty($department))
            value: new Date(),
            @endif
        });


        //根据身份证号码选择性别
        $("#id_card").blur(function(){
            var id_card = $("#id_card").val();
            if(id_card.length != 18){
                return false;
            }
            var curValue = id_card.substr(16,1);
            var gender = curValue%2 == 0?2:1;

            var radio = document.getElementsByName("gender");
            for (var i = 0; i < radio.length; i++) {
                if(gender == radio[i].value){
                    $(radio[i]).next().click();
                }
            }
        });

        //监听提交
        form.on('submit(add)', function(data) {
            $.ajax({
                url:  '/person/department/list',
                data: data.field,
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                success:function(data) {
                    if(data.code == 200){
                        layer.msg(data.message,{icon:1});
                        setTimeout(function () {
                            @if(!empty($department))
                            xadmin.close();
                            xadmin.father_reload();
                            @else
                            xadmin.del_tab();
                            @endif
                        },500);
                    }else{
                        // layer.msg(data.message,{icon:2});
                        console.log(data);
                    }
                },
                error:function(data){
                    layer.msg('服务器连接失败');
                }
            });
            
            //刷新tab
            return false;       //禁止提交
        });

    });
</script>
</body>

</html>

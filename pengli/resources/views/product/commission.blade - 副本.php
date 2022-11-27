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
            <div class="layui-form-item">
                <label for="department_name" class="layui-form-label">
                    <span class="x-red">*</span>部门名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="department_name" name="department_name" lay-verify="required" value="信泰如意尊" 
                        autocomplete="off" class="layui-input">
                </div>
                <label for="department_name" class="layui-form-label">
                    <span class="x-red">*</span>部门名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="department_name" name="department_name" lay-verify="required" value="信泰如意尊" 
                        autocomplete="off" class="layui-input">
                </div>
            </div>
            
        </form>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                
                    <table class="layui-table layui-form">
                        <thead>
                            <tr>
                                
                                <th>缴费年期</th>
                                <th>第一年佣金率</th>
                                <th>第二年佣金率</th>
                                <th>第三年佣金率</th>
                                <th>第四年佣金率</th>
                                <th>第五年佣金率</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commissions as $commission)
                            <tr>
                                <!-- <td>{{$commission->product_name}}</td>
                                <td>{{$commission->insurance_company_name}}</td> -->
                                <td>{{$commission->payment_period}}</td>
                                <td>{{$commission->first_year_rate}}%</td>
                                <td>{{$commission->second_year_rate}}%</td>
                                <td>{{$commission->third_year_rate}}%</td>
                                <td>{{$commission->fourth_year_rate}}%</td>
                                <td>{{$commission->fifth_year_rate}}%</td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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

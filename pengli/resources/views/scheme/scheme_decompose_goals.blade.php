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
        .layui-form th{
            text-align: center;
        }
        .layui-form .layui-input{
            text-align: center;
        }
    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">
            <div class="layui-form-item">
                <label for="scheme_name" class="layui-form-label">
                    方案
                </label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$schemeName}}" autocomplete="off" readonly class="layui-input">
                </div>  
            </div>
            <table  class="layui-table">
                <thead>
                    <tr>
                        <th>单位</th>
                        <th>总任务保费</th>
                        <th>健康险保费</th>
                        <th>出单人力</th>
                    </tr>
                </thead>                    
                <tbody>
                    @foreach($schemeTragets as $schemeTraget)
                    <tr>
                        <td>
                            <input type="text" class="layui-input" value="{{$schemeTraget->organization_name}}" readonly>
                        </td>
                        <td>
                            <input type="number" onblur="formatNum(this.val())" name="{{$schemeTraget->scheme_target_id}}_scheme_target_premium" class="layui-input" value="{{$schemeTraget->scheme_target_premium}}" autocomplete="off">
                        </td>
                        <td>
                            <input type="number" name="{{$schemeTraget->scheme_target_id}}_scheme_health_target_premium" class="layui-input" value="{{$schemeTraget->scheme_health_target_premium}}" autocomplete="off">
                        </td>
                        <td>
                            <input type="number" name="{{$schemeTraget->scheme_target_id}}_scheme_target_manpower" class="layui-input" value="{{$schemeTraget->scheme_target_manpower}}" autocomplete="off">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
    
            <div class="layui-form-item" style="text-align: center;">
<!--                <label for="L_repass" class="layui-form-label">-->
<!--                </label>-->
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    保存
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
            elem: '#scheme_start_date', //指定元素
        });
        laydate.render({
            elem: '#scheme_end_date', //指定元素
        });
        laydate.render({
            elem: '#underwriting_end_date', //指定元素
        });
        laydate.render({
            elem: '#receipt_end_date', //指定元素
        });

        function formatNum(num){
            if(!/^(\+|-)?\d+(\.\d+)?$/.test(num)){
            return num;
            }
            var re = new RegExp().compile("(\\d)(\\d{3})(,|\\.|$)");
            num += "";
            while(re.test(num))
            num = num.replace(re, "$1,$2$3")
            return num;
        }

        function toThousandsFormat(number) {
            number = number.toString().replace(/,/g, '')
            number = parseFloat(number).toFixed(2).toString()
            number = number.replace(/(\d)(?=(\d{3})+\.)/g, function ($0, $1) {
                        return $1 + ','
                    })
            return number
        }

        //监听提交
        form.on('submit(add)', function(data) {
            xadmin.del_tab();
            $.ajax({
                url:  '/scheme/list',
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
                            xadmin.close();
                            xadmin.father_reload();
                        },500);
                    }else{
                        console.log(data.message);
                        layer.msg(data.message,{icon:2});
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

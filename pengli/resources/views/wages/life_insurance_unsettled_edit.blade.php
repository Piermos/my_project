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
        .layui-form .layui-form-item{
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">
            <input type="hidden" name="contract_id" value="{{$contract->contract_id}}" >
            <div class="layui-form-item">
                <label for="insurance_company_id" class="layui-form-label">
                    险司</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$contract->insurance_company_name}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="product_id" class="layui-form-label">
                    产品</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$contract->product_name}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="product_id" class="layui-form-label">
                    期交保费</label>
                <div class="layui-input-inline">
                    <input type="text" id="regular_premium" value="{{$contract->regular_premium}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="product_id" class="layui-form-label">
                    交费期</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$contract->payment_period}}" autocomplete="off" class="layui-input" readonly>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="product_id" class="layui-form-label">
                    录单时间</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$contract->insurance_date}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="product_id" class="layui-form-label">
                    承保时间</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$contract->underwriting_date}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="product_id" class="layui-form-label">
                    回执时间</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$contract->receipt_date}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="product_id" class="layui-form-label">
                    回访时间</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$contract->return_visit_date}}" autocomplete="off" class="layui-input" readonly>
                </div> 
            </div>
            <div class="layui-form-item">
                <label for="settlement_rate" class="layui-form-label">
                    <span class="x-red">*</span>佣金率</label>
                <div class="layui-input-inline">
                    <input type="text" id="settlement_rate" name="settlement_rate" value="{{$contract->settlement_rate}}" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
                <label for="product_id" class="layui-form-label">
                   FYC</label>
                <div class="layui-input-inline">
                    <input type="text" id="settlement_fyc" name="settlement_fyc" value="{{$contract->settlement_fyc}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="settlement_date" class="layui-form-label">
                    <span class="x-red">*</span>结算时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="settlement_date" name="settlement_date" lay-verify="required" autocomplete="off" class="layui-input" readonly>
                </div>
            </div>
            <div class="layui-form-item" style="text-align: center;">
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    结算
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

        //结算日期
        var insurance_date = laydate.render({
            elem: '#settlement_date', //指定元素
            value: new Date(new Date().setDate(20)),
            max:0,
        });
        

        $('#settlement_rate').on('input',function(e){
            var regular_premium = Number($('#regular_premium').val());
            var settlement_rate = Number(e.delegateTarget.value);
            var fyc = Math.floor(regular_premium * settlement_rate / 100);
            $('#settlement_fyc').val(fyc);
        })
        
        //监听提交
        form.on('submit(add)', function(data) {
            
            $.ajax({
                url:  '/wages/life_insurance/unsettled',
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

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
            <input type="hidden" id="scheme_id" name="scheme_id" value="{{!empty($scheme)?$scheme->scheme_id:''}}" >
            <div class="layui-form-item">
                <label for="scheme_name" class="layui-form-label">
                    <span class="x-red">*</span>方案名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="scheme_name" name="scheme_name" lay-verify="required" value="{{!empty($scheme)?$scheme->scheme_name:''}}" 
                     autocomplete="off" class="layui-input">
                </div>
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>开始时间
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="scheme_start_date" name="scheme_start_date" lay-verify="required" value="{{!empty($scheme)?$scheme->scheme_start_date:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>结束时间
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="scheme_end_date" name="scheme_end_date" lay-verify="required" value="{{!empty($scheme)?$scheme->scheme_end_date:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>承保截止时间
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="underwriting_end_date" name="underwriting_end_date" lay-verify="required" value="{{!empty($scheme)?$scheme->underwriting_end_date:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>回执截止时间
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="receipt_end_date" name="receipt_end_date" lay-verify="required" value="{{!empty($scheme)?$scheme->receipt_end_date:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="scheme_name" class="layui-form-label">
                    <span class="x-red">*</span>旅游保费
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="travel_premium" name="travel_premium" lay-verify="required" value="{{!empty($scheme)?$scheme->travel_premium:''}}" 
                     autocomplete="off" class="layui-input">
                </div>
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>额外加点(%)
                </label>
                <div class="layui-input-inline">
                    <input type="number" id="add_ratio" name="add_ratio" lay-verify="required" value="{{!empty($scheme)?$scheme->add_ratio:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                
            </div>
            <div class="layui-form-item">
                <label for="travel_insurance_limit" class="layui-form-label">
                    旅游方案险种
                </label>
                <div class="layui-input-inline">
                    <input type="radio" name="travel_insurance_limit" value="1" lay-skin="primary" title="所有险种" checked {{!empty($scheme) && $scheme->travel_insurance_limit == 1?'checked':''}}>
                    <input type="radio" name="travel_insurance_limit" value="2" lay-skin="primary" title="资金险" {{!empty($scheme) && $scheme->travel_insurance_limit == 2?'checked':''}}>
                </div>
                <label for="is_car_insurance" class="layui-form-label">
                    是否要求车险
                </label>
                <div class="layui-input-inline">
                    <input type="radio" name="is_car_insurance" value="1" lay-skin="primary" title="不要求" checked {{!empty($scheme) && $scheme->is_car_insurance == 1?'checked':''}}>
                    <input type="radio" name="is_car_insurance" value="2" lay-skin="primary" title="要求" {{!empty($scheme) && $scheme->is_car_insurance == 2?'checked':''}}>
                </div>
            </div>
            <hr>
            <span>资金险系数</span>
            <div class="layui-form-item">
                
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>趸交
                </label>
                <div class="layui-input-inline">
                    <input type="number" name="capital_insurance_ratio_1" lay-verify="required" value="{{!empty($scheme)?$scheme->capital_insurance_ratio_1:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>3年交
                </label>
                <div class="layui-input-inline">
                    <input type="number" name="capital_insurance_ratio_3" lay-verify="required" value="{{!empty($scheme)?$scheme->capital_insurance_ratio_3:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>5年交
                </label>
                <div class="layui-input-inline">
                    <input type="number" name="capital_insurance_ratio_5" lay-verify="required" value="{{!empty($scheme)?$scheme->capital_insurance_ratio_5:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>10年交及以上
                </label>
                <div class="layui-input-inline">
                    <input type="number" name="capital_insurance_ratio_10" lay-verify="required" value="{{!empty($scheme)?$scheme->capital_insurance_ratio_10:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <span>健康险系数</span>
            <div class="layui-form-item">
                
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>5年交
                </label>
                <div class="layui-input-inline">
                    <input type="number" name="health_insurance_ratio_5" lay-verify="required" value="{{!empty($scheme)?$scheme->health_insurance_ratio_5:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>10年交
                </label>
                <div class="layui-input-inline">
                    <input type="number" name="health_insurance_ratio_10" lay-verify="required" value="{{!empty($scheme)?$scheme->health_insurance_ratio_10:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>15年交
                </label>
                <div class="layui-input-inline">
                    <input type="number" name="health_insurance_ratio_15" lay-verify="required" value="{{!empty($scheme)?$scheme->health_insurance_ratio_15:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>19年交及以上
                </label>
                <div class="layui-input-inline">
                    <input type="number" name="health_insurance_ratio_19" lay-verify="required" value="{{!empty($scheme)?$scheme->health_insurance_ratio_19:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <span>其他险种系数</span>
            <div class="layui-form-item">
                
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>意外险
                </label>
                <div class="layui-input-inline">
                    <input type="number" name="accident_insurance_ratio" lay-verify="required" value="{{!empty($scheme)?$scheme->accident_insurance_ratio:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>团险及安盛
                </label>
                <div class="layui-input-inline">
                    <input type="number" name="group_insurance_ratio" lay-verify="required" value="{{!empty($scheme)?$scheme->group_insurance_ratio:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>车险
                </label>
                <div class="layui-input-inline">
                    <input type="number" name="car_insurance_ratio" lay-verify="required" value="{{!empty($scheme)?$scheme->car_insurance_ratio:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
            </div>
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

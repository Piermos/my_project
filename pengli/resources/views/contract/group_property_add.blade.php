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
            <input type="hidden" name="group_property_id" value="{{!empty($groupProperty)?$groupProperty->group_property_id:''}}" >
            <div class="layui-form-item">
                <label for="product_type" class="layui-form-label">
                    <span class="x-red">*</span>险种</label>
                <div class="layui-input-inline">
                    <select id="product_type" name="product_type" class="valid" lay-verify="required" lay-search>
                        <option value="">请选择</option>
                        <option value="团险" {{!empty($groupProperty)&&$groupProperty->product_type == '团险'?'selected':''}}>团险</option>
                        <option value="车险" {{!empty($groupProperty)&&$groupProperty->product_type == '车险'?'selected':''}}>车险</option>
                    </select>
                </div>
                <label for="product_name" class="layui-form-label">
                    <span class="x-red">*</span>产品
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="product_name" lay-verify="required" value="{{!empty($groupProperty)?$groupProperty->product_name:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                <label for="regular_premium" class="layui-form-label">
                    <span class="x-red">*</span>保费
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="regular_premium" lay-verify="required" value="{{!empty($groupProperty)?$groupProperty->regular_premium:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                <label for="insurance_date" class="layui-form-label">
                    <span class="x-red">*</span>投保日期
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="insurance_date" name="insurance_date" lay-verify="required" value="{{!empty($groupProperty)?$groupProperty->insurance_date:''}}"
                     autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
               
                <label for="policy_number" class="layui-form-label">
                    保单号
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="policy_number" value="{{!empty($groupProperty)?$groupProperty->policy_number:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                <label for="policy_holder" class="layui-form-label">
                    投保人
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="policy_holder" value="{{!empty($groupProperty)?$groupProperty->policy_holder:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                <label for="insured" class="layui-form-label">
                    被保人
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="insured" value="{{!empty($groupProperty)?$groupProperty->insured:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                <label for="settlement_rate" class="layui-form-label">
                    结算佣金率
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="settlement_rate" value="{{!empty($groupProperty)?$groupProperty->settlement_rate:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                
            </div>
            <div class="layui-form-item">
                <label for="organization" class="layui-form-label">
                    机构</label>
                <div class="layui-input-inline">
                    <select class="valid" lay-filter="organization" lay-search>
                        <option value="">请选择</option>
                        @foreach($organizations as $organization)
                        <option value="{{$organization->organization_id}}" {{!empty($groupProperty)&&$groupProperty->organization_id == $organization->organization_id?'selected':''}}>{{$organization->organization_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="department" class="layui-form-label">
                    部门</label>
                <div class="layui-input-inline">
                    <select id="department" class="valid" lay-filter="department" lay-search>
                        <option value="">请选择</option>
                        @foreach($departments as $department)
                        <option value="{{$department->department_id}}" {{!empty($groupProperty)&&$groupProperty->department_id == $department->department_id?'selected':''}}>{{$department->department_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="proxy" class="layui-form-label">
                    <span class="x-red">*</span>业务员</label>
                <div class="layui-input-inline">
                    <select id="proxy" name="proxy_id" class="valid" lay-filter="proxy" lay-verify="required" lay-search>
                        <option value="">请选择</option>
                        @foreach($proxys as $proxy)
                        <option value="{{$proxy->proxy_id}}" {{!empty($groupProperty)&&$groupProperty->proxy_id == $proxy->proxy_id?'selected':''}}>{{$proxy->proxy_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="attribution_scheme" class="layui-form-label">
                    归属方案</label>
                <div class="layui-input-inline">
                    <select name="attribution_scheme_id" class="valid" lay-filter="proxy" lay-search>
                        <option value="">请选择</option>
                        @foreach($attributionSchemes as $attributionScheme)
                        <option value="{{$attributionScheme->attribution_scheme_id}}" {{!empty($groupProperty)&&$groupProperty->attribution_scheme_id == $attributionScheme->attribution_scheme_id?'selected':''}}>{{$attributionScheme->attribution_scheme_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="is_settlement" class="layui-form-label">
                    <span class="x-red">*</span>是否结算</label>
                <div class="layui-input-inline">
                    <select name="is_settlement" class="valid" lay-verify="required" lay-search>
                        <option value="1" {{!empty($groupProperty)&&$groupProperty->is_settlement == 1?'selected':''}}>1</option>
                        <option value="2" {{!empty($groupProperty)&&$groupProperty->is_settlement == 2?'selected':''}}>2</option>
                    </select>
                </div>
                <label for="insurance_date" class="layui-form-label">
                    结算日期
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="actual_settlement_date" name="actual_settlement_date" value="{{!empty($groupProperty)?$groupProperty->actual_settlement_date:''}}"
                     autocomplete="off" class="layui-input">
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
        //投保日期
        laydate.render({
            elem: '#insurance_date', //指定元素
        });
        //结算日期
        laydate.render({
            elem: '#actual_settlement_date', //指定元素
        });

        //监控机构更改
        form.on('select(organization)', function(data){
            var organization_id = data.value;
            
            $('#department').empty();   //清空department
            var temp_node = document.createElement('option');
            temp_node.value = '';
            temp_node.innerText = '请选择';
            $('#department').append(temp_node);
            form.render();
            
            $.ajax({
                url:  '/contract/new_contract/add/retrieval',
                data: {
                    'organization_id': organization_id,
                },
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                success:function(data) {
                    if(data.code == 200){
                        var departments = data.data;
                        for(var i=0;i<departments.length;i++){
                            var department_node = document.createElement('option');
                            department_node.value = departments[i]['department_id'];
                            department_node.innerText = departments[i]['department_name'];
                            $('#department').append(department_node);
                        }
                        form.render();
                    }
                    
                },
                error:function(data){
                    layer.msg('服务器连接失败');
                }
            });
        });
        //监控部门更改
        form.on('select(department)', function(data){
            var department_id = data.value;
            
            $('#proxy').empty();
            var temp_node = document.createElement('option');
            temp_node.value = '';
            temp_node.innerText = '请选择';
            $('#proxy').append(temp_node);
            form.render();
            
            $.ajax({
                url:  '/contract/new_contract/add/retrieval',
                data: {
                    'department_id': department_id,
                },
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                success:function(data) {
                    if(data.code == 200){
                        var proxys = data.data;
                        for(var i=0;i<proxys.length;i++){
                            var proxy_node = document.createElement('option');
                            proxy_node.value = proxys[i]['proxy_id'];
                            proxy_node.innerText = proxys[i]['proxy_name'];
                            $('#proxy').append(proxy_node);
                        }
                        form.render();
                    }
                    
                },
                error:function(data){
                    layer.msg('服务器连接失败');
                }
            });
        });

        //监听提交
        form.on('submit(add)', function(data) {
            $.ajax({
                url:  '/contract/group_property/list',
                data: data.field,
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                success:function(data) {
                    // console.log(data);
                    // return;
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
            return false;       //禁止提交
        });

    });
</script>
</body>

</html>

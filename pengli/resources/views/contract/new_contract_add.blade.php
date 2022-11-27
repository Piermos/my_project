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
            <input type="hidden" name="contract_id" value="{{!empty($contract)?$contract->contract_id:''}}" >
            <div class="layui-form-item">
                <label for="insurance_company_id" class="layui-form-label">
                    <span class="x-red">*</span>险司</label>
                <div class="layui-input-inline">
                    <select id="insurance_company" name="insurance_company_id" class="valid" lay-filter="insurance_company" lay-verify="required" lay-search>
                        <option value="">请选择</option>
                        @foreach($insurance_companys as $insurance_company)
                        <option value="{{$insurance_company->insurance_company_id}}" {{!empty($contract)&&$contract->insurance_company_id == $insurance_company->insurance_company_id?'selected':''}}>{{$insurance_company->insurance_company_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="product_id" class="layui-form-label">
                    <span class="x-red">*</span>产品</label>
                <div class="layui-input-inline">
                    <select id="product" name="product_id" class="valid" lay-filter="product" lay-search>
                        <option value="">请选择</option>
                        @foreach($products as $product)
                        <option value="{{$product->product_id}}" {{!empty($contract)&&$contract->product_id == $product->product_id?'selected':''}}>{{$product->product_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="payment_period" class="layui-form-label">
                    <span class="x-red">*</span>交费期间</label>
                <div class="layui-input-inline">
                    <select id="payment_period" name="payment_period" class="valid" lay-filter="payment_period" lay-search>
                        <option value="">请选择</option>
                        @foreach($commissions as $commission)
                        <option value="{{$commission->payment_period}}" {{!empty($contract)&&$contract->payment_period == $commission->payment_period?'selected':''}}>{{$commission->payment_period}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="regular_premium" class="layui-form-label">
                    <span class="x-red">*</span>期交保费
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="regular_premium" lay-verify="required" value="{{!empty($contract)?$contract->regular_premium:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                
            </div>
            <div class="layui-form-item">
                <label for="insurance_date" class="layui-form-label">
                    <span class="x-red">*</span>投保日期
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="insurance_date" name="insurance_date" lay-verify="required" value="{{!empty($contract)?$contract->insurance_date:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                <label for="underwriting_date" class="layui-form-label">
                    承保日期
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="underwriting_date" name="underwriting_date" value="{{!empty($contract)?$contract->underwriting_date:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                <label for="receipt_date" class="layui-form-label">
                    回执日期
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="receipt_date" name="receipt_date" value="{{!empty($contract)?$contract->receipt_date:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                <label for="return_visit_date" class="layui-form-label">
                    回访日期
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="return_visit_date" name="return_visit_date" value="{{!empty($contract)?$contract->return_visit_date:''}}"
                     autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="policy_number" class="layui-form-label">
                    保单号
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="policy_number" value="{{!empty($contract)?$contract->policy_number:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                <label for="policy_holder" class="layui-form-label">
                    投保人
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="policy_holder" value="{{!empty($contract)?$contract->policy_holder:''}}"
                     autocomplete="off" class="layui-input">
                </div>
                <label for="policy_holder_gender" class="layui-form-label">
                    投保人性别
                </label>
                <div class="layui-input-inline">
                   
                    <input type="radio" name="policy_holder_gender" value="1" lay-skin="primary" title="男" {{!empty($contract) && $contract->policy_holder_gender == 1?'checked':''}}>
                    <input type="radio" name="policy_holder_gender" value="2" lay-skin="primary" title="女" {{!empty($contract) && $contract->policy_holder_gender == 2?'checked':''}}>
                </div>
                <label for="policy_holder_birth" class="layui-form-label">
                    投保人生日
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="policy_holder_birth" name="policy_holder_birth" value="{{!empty($contract)?$contract->policy_holder_birth:''}}"
                     autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="organization" class="layui-form-label">
                    <span class="x-red">*</span>机构</label>
                <div class="layui-input-inline">
                    <select class="valid" lay-filter="organization" lay-search>
                        <option value="">请选择</option>
                        @foreach($organizations as $organization)
                        <option value="{{$organization->organization_id}}" {{!empty($contract)&&$contract->organization_id == $organization->organization_id?'selected':''}}>{{$organization->organization_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="department" class="layui-form-label">
                    <span class="x-red">*</span>部门</label>
                <div class="layui-input-inline">
                    <select id="department" class="valid" lay-filter="department" lay-search>
                        <option value="">请选择</option>
                        @foreach($departments as $department)
                        <option value="{{$department->department_id}}" {{!empty($contract)&&$contract->department_id == $department->department_id?'selected':''}}>{{$department->department_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="proxy" class="layui-form-label">
                    <span class="x-red">*</span>业务员</label>
                <div class="layui-input-inline">
                    <select id="proxy" name="proxy_id" class="valid" lay-filter="proxy" lay-verify="required" lay-search>
                        <option value="">请选择</option>
                        @foreach($proxys as $proxy)
                        <option value="{{$proxy->proxy_id}}" {{!empty($contract)&&$contract->proxy_id == $proxy->proxy_id?'selected':''}}>{{$proxy->proxy_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="attribution_scheme" class="layui-form-label">
                    归属方案</label>
                <div class="layui-input-inline">
                    <select name="attribution_scheme_id" class="valid" lay-filter="proxy" lay-search>
                        <option value="">请选择</option>
                        @foreach($attributionSchemes as $attributionScheme)
                        <option value="{{$attributionScheme->attribution_scheme_id}}" {{!empty($contract)&&$contract->attribution_scheme_id == $attributionScheme->attribution_scheme_id?'selected':''}}>{{$attributionScheme->attribution_scheme_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="policy_status_id" class="layui-form-label">
                    <span class="x-red">*</span>保单状态</label>
                <div class="layui-input-inline">
                    <select name="policy_status_id" class="valid" lay-search>
                        @foreach($policy_status as $status)
                        <option value="{{$status->policy_status_id}}" {{!empty($contract)&&$contract->policy_status_id == $status->policy_status_id?'selected':''}}>{{$status->policy_status_name}}</option>
                        @endforeach
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
        //投保日期
        var insurance_date = laydate.render({
            elem: '#insurance_date', //指定元素
            max:0,
            done:function(value,date){
                underwriting_date.config.min ={
                    year:date.year,
                    month:date.month-1,
                    date: date.date,
                };
            }
        });
        //承保日期
        var underwriting_date = laydate.render({
            elem: '#underwriting_date', //指定元素
            max:0,
            min:$("#insurance_date").val(),
            done:function(value,date){
                receipt_date.config.min ={
                    year:date.year,
                    month:date.month-1,
                    date: date.date,
                };
            }
        });
        //回执日期
        var receipt_date = laydate.render({
            elem: '#receipt_date', //指定元素
            max:0,
            min:$("#underwriting_date").val(),
            done:function(value,date){
                return_visit_date.config.min ={
                    year:date.year,
                    month:date.month-1,
                    date: date.date,
                };
            }
        });
        //回访日期
        var return_visit_date = laydate.render({
            elem: '#return_visit_date', //指定元素
            max:0,
            min:$("#receipt_date").val(),
        });
        //投保人出生日期
        laydate.render({
            elem: '#policy_holder_birth', //指定元素
        });

        //监控险司更改
        form.on('select(insurance_company)', function(data){
            var insurance_company_id = data.value;
            $('#product').empty();   //清空department
            var temp_node = document.createElement('option');
            temp_node.value = '';
            temp_node.innerText = '请选择';
            $('#product').append(temp_node);
            form.render();
            
            $.ajax({
                url:  '/contract/new_contract/add/retrieval',
                data: {
                    'insurance_company_id': insurance_company_id,
                },
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                success:function(data) {
                    if(data.code == 200){
                        var products = data.data;
                        for(var i=0;i<products.length;i++){
                            var product_node = document.createElement('option');
                            product_node.value = products[i]['product_id'];
                            product_node.innerText = products[i]['product_name'];
                            $('#product').append(product_node);
                        }
                        form.render();
                    }
                    
                },
                error:function(data){
                    layer.msg('服务器连接失败');
                }
            });
        });

        //监控产品更改
        form.on('select(product)', function(data){
            var product_id = data.value;
            
            $('#payment_period').empty();
            var temp_node = document.createElement('option');
            temp_node.value = '';
            temp_node.innerText = '请选择';
            $('#payment_period').append(temp_node);
            form.render();
            
            $.ajax({
                url:  '/contract/new_contract/add/retrieval',
                data: {
                    'product_id': product_id,
                },
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                success:function(data) {
                    if(data.code == 200){
                        var commissions = data.data;
                        for(var i=0;i<commissions.length;i++){
                            var payment_period_node = document.createElement('option');
                            payment_period_node.value = commissions[i]['payment_period'];
                            payment_period_node.innerText = commissions[i]['payment_period'];
                            $('#payment_period').append(payment_period_node);
                        }
                        form.render();
                    }
                    
                },
                error:function(data){
                    layer.msg('服务器连接失败');
                }
            });
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
                url:  '/contract/new_contract/list',
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

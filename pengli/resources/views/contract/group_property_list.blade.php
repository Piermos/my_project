<!DOCTYPE html>
<html class="x-admin-sm">
    
    <head>
        <meta charset="UTF-8">
        <title>鹏利保代</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="/css/font.css">
        <link rel="stylesheet" href="/css/xadmin.css">
        <!-- <link rel="stylesheet" href="/css/pagination.css"> -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script type="text/javascript" src="/js/jquery.min.js"></script>
        <script type="text/javascript" src="/js/jq-paginator.js"></script>
        <script src="/lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="/js/xadmin.js"></script>
        <style>
            /*表格内容居中*/
            .layui-table th,tr{
                text-align: center;
            }
            /*分页元素*/
            .my-pag{
                float: left;margin-right: 10px;
            }
            .my-pag span{
                line-height: 70px;color:#337AB7
            }
        </style>
    </head>
    
    <body>
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="#">首页</a>
                <a href="#">团财业务管理</a>
                <a>
                    <cite>团财新契约保单</cite></a>
            </span>
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
                <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
            </a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <form class="layui-form layui-col-space5" action="{{$groupPropertys->path()}}">
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="policy_number" value="{{isset($requestData['policy_number'])?$requestData['policy_number']:''}}"  placeholder="请输入保单号" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="product_type" lay-search>
                                        <option value="">请选择类型</option>
                                        <option value="团险" {{isset($requestData['product_type']) && ($requestData['product_type'] == '团险') ? 'selected' : ''}}>团险</option>
                                        <option value="车险" {{isset($requestData['product_type']) && ($requestData['product_type'] == '车险') ? 'selected' : ''}}>车险</option>
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="organization_id" lay-filter="organization" lay-search>
                                        <option value="">请选择机构</option>
                                        @foreach($organizations as $organization)
                                        <option value="{{$organization->organization_id}}" {{isset($requestData['organization_id']) && ($requestData['organization_id'] == $organization->organization_id) ? 'selected' : ''}}>{{$organization->organization_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select id="department" name="department_id" lay-filter="department" lay-search>
                                        <option value="">请选择部门</option>
                                        @foreach($departments as $department)
                                        <option value="{{$department->department_id}}" {{isset($requestData['department_id']) && ($requestData['department_id'] == $department->department_id) ? 'selected' : ''}}>{{$department->department_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select id="proxy" name="proxy_id" lay-search>
                                        <option value="">请选择业务员</option>
                                        @foreach($proxys as $proxy)
                                        <option value="{{$proxy->proxy_id}}" {{isset($requestData['proxy_id']) && ($requestData['proxy_id'] == $proxy->proxy_id) ? 'selected' : ''}}>{{$proxy->proxy_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" id="insurance_date_start" name="insurance_date_start" placeholder="投保开始时间" autocomplete="off" class="layui-input"
                                    value="{{isset($requestData['insurance_date_start'])?$requestData['insurance_date_start']:''}}"  lay-verify="required">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" id="insurance_date_end" name="insurance_date_end" placeholder="投保结束时间" autocomplete="off" class="layui-input"
                                    value="{{isset($requestData['insurance_date_end'])?$requestData['insurance_date_end']:''}}" lay-verify="required">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <button type="submit" lay-submit class="layui-btn">查询</button>
                                </div>
                                
                            </form>
                        </div>
                        <div class="layui-card-body ">
                                <div class="layui-inline layui-show-xs-block">
                                    <button type="button" class="layui-btn" onclick="xadmin.open('新增','/contract/group_property/add')">新增</button>
                                </div>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                                <thead>
                                    <tr>
                                        <th>保单号</th>
                                        <th>产品类型</th>
                                        <th>产品名称</th>
                                        <th>规模保费</th>
                                        <th>投保人</th>
                                        <th>被保人</th>
                                        <th>投保时间</th>
                                        <th>业务员</th>
                                        <th>部门</th>
                                        <th>机构</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupPropertys as $groupProperty)
                                    <tr>
                                        <td>{{empty($groupProperty->policy_number)?'-':$groupProperty->policy_number}}</td>
                                        <td>{{$groupProperty->product_type}}</td>
                                        <td>{{$groupProperty->product_name}}</td>
                                        <td>{{number_format($groupProperty->regular_premium,2)}}</td>
                                        <td>{{empty($groupProperty->policy_holder)?'-':$groupProperty->policy_holder}}</td>
                                        <td>{{empty($groupProperty->insured)?'-':$groupProperty->insured}}</td>
                                        <td>{{$groupProperty->insurance_date}}</td>
                                        <td>{{$groupProperty->proxy_name}}</td>
                                        <td>{{$groupProperty->department_name}}</td>
                                        <td>{{$groupProperty->organization_name}}</td>
                                        <td class="td-manage">
                                            <button class="layui-btn" onclick="xadmin.open('编辑','/contract/group_property/list/{{$groupProperty->group_property_id}}/edit')">编辑</button>
                                            <button class="layui-btn layui-btn-normal layui-btn-danger" onclick="group_property_del(this,{{$groupProperty->group_property_id}})">删除</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            <div id="tip" style="width: 100%;text-align: center;color: grey;margin-top: 30px;display: {{$groupPropertys->isEmpty()?'':'none'}}">
                                <span>--无数据--</span>
                            </div>
                        </div>
                        {{ $groupPropertys->links()}}
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        layui.use(['laydate', 'form'], function() {
            $ = layui.jquery;
            var form = layui.form,
                layer = layui.layer,
                laydate = layui.laydate;

            //投保日期
            var insurance_date_start = laydate.render({
                elem: '#insurance_date_start', //指定元素
                // value: new Date(new Date().setDate(1)),     //每个月的1号
                max:0,
                done:function(value,date){
                    if(value && (value > $("#insurance_date_end").val())){
                        $("#insurance_date_end").val(value);
                    }
                    insurance_date_end.config.min ={
                        year:date.year,
                        month:date.month-1,
                        date: date.date,
                    };
                }
            });
            var insurance_date_end = laydate.render({
                elem: '#insurance_date_end', //指定元素
                // value: new Date(),
                max:0,
                min:$("#insurance_date_start").val()
            });

            form.on('select(organization)', function(data){
                var organization_id = data.value;
                $('#department').empty();   //清空department
                var temp_node = document.createElement('option');
                temp_node.value = '';
                temp_node.innerText = '请选择部门';
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
            form.on('select(department)', function(data){
                var department_id = data.value;
                $('#proxy').empty();   //清空proxy
                var temp_node = document.createElement('option');
                temp_node.value = '';
                temp_node.innerText = '请选择业务员';
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
        });

        //删除
        function group_property_del(obj, id) {
            layer.confirm('确认要删除吗？',
            function(index) {
                //发异步删除数据
                $.ajax({
                    url: '/contract/group_property/delete/'+id,
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'delete',
                    dataType: 'json',
                    success:function(data) {
                        if(data.code == 200){
                            // layer.msg(data.message,{icon:1});
                            $(obj).parents("tr").remove();
                            layer.msg(data.message, {
                                icon: 1,
                                time: 1000
                            });
                        }else{
                            layer.msg(data.message,{icon:2});
                        }
                    },
                    error:function(data){
                        layer.msg('服务器连接失败');
                    }
                });
                
            });
        }
        
    </script>

</html>
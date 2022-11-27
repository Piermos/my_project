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
                <a href="#">工资管理</a>
                <a>
                    <cite>寿险已结算保单</cite></a>
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
                            <form class="layui-form layui-col-space5" action="{{$contracts->path()}}">
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="policy_holder" value="{{isset($requestData['policy_holder'])?$requestData['policy_holder']:''}}"  placeholder="投保人姓名" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="insurance_company_id" lay-search>
                                        <option value="">请选择保司</option>
                                        @foreach($insuranceCompanys as $insuranceCompany)
                                        <option value="{{$insuranceCompany->insurance_company_id}}" {{isset($requestData['insurance_company_id']) && ($requestData['insurance_company_id'] == $insuranceCompany->insurance_company_id) ? 'selected' : ''}}>{{$insuranceCompany->insurance_company_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="is_settlement" lay-search>
                                        <option value="1" {{isset($requestData['is_settlement']) && ($requestData['is_settlement'] == 1) ? 'selected' : ''}}>待结算</option>
                                        <option value="2" {{isset($requestData['is_settlement']) && ($requestData['is_settlement'] == 2) ? 'selected' : ''}}>已结算</option>
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
                                        <option value="">请先选择部门</option>
                                        @foreach($departments as $department)
                                        <option value="{{$department->department_id}}" {{isset($requestData['department_id']) && ($requestData['department_id'] == $department->department_id) ? 'selected' : ''}}>{{$department->department_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select id="proxy" name="proxy_id" lay-search>
                                        <option value="">请先选择代理人</option>
                                        @foreach($proxys as $proxy)
                                        <option value="{{$proxy->proxy_id}}" {{isset($requestData['proxy_id']) && ($requestData['proxy_id'] == $proxy->proxy_id) ? 'selected' : ''}}>{{$proxy->proxy_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" id="settlement_date_start" name="settlement_date_start" placeholder="结算开始时间" autocomplete="off" class="layui-input"
                                    value="{{isset($requestData['settlement_date_start'])?$requestData['settlement_date_start']:''}}" >
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" id="settlement_date_end" name="settlement_date_end" placeholder="结算结束时间" autocomplete="off" class="layui-input"
                                    value="{{isset($requestData['settlement_date_end'])?$requestData['settlement_date_end']:''}}">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <button type="submit" lay-submit class="layui-btn">查询</button>
                                </div>
                                
                            </form>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                                <thead>
                                    <tr>
                                        <th>险司</th>
                                        <th>产品</th>
                                        <th>投保人</th>
                                        <th>期交保费</th>
                                        <th>交费期</th>
                                        <th>回执时间</th>
                                        <th>回访时间</th>
                                        <th>应结算时间</th>
                                        <th>结算时间</th>
                                        <th>佣金率</th>
                                        <th>FYC</th>
                                        <th>业务员</th>
                                        <th>部门</th>
                                        <th>机构</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contracts as $contract)
                                    <tr>
                                        <td>{{$contract->insurance_company_name}}</td>
                                        <td>{{$contract->product_name}}</td>
                                        <td>{{$contract->policy_holder}}</td>
                                        <td>{{number_format($contract->regular_premium)}}</td>
                                        <td>{{$contract->payment_period}}</td>
                                        <td>{{empty($contract->receipt_date)?'-':$contract->receipt_date}}</td>
                                        <td>{{empty($contract->return_visit_date)?'-':$contract->return_visit_date}}</td>
                                        <td>{{empty($contract->payable_settlement_date)?'-':$contract->payable_settlement_date}}</td>
                                        <td>{{empty($contract->settlement_date)?'-':$contract->settlement_date}}</td>
                                        <td>{{$contract->settlement_rate}}%</td>
                                        <td>{{$contract->settlement_fyc}}</td>
                                        <td>{{$contract->proxy_name}}</td>
                                        <td>{{$contract->department_name}}</td>
                                        <td>{{$contract->organization_name}}</td>
                                        <td><span style="color:{{$contract->is_settlement == 2?'green':''}}">{{$contract->is_settlement==1?'待结算':'已结算'}}</span></td>
                                        <td class="td-manage">
                                            @if($contract->is_settlement == 1 && $contract->payable_settlement_date < date('Y-m-d') && !empty($contract->receipt_date) && !empty($contract->return_visit_date))
                                            <button class="layui-btn" onclick="xadmin.open('结算','/wages/life_insurance/settled/{{$contract->contract_id}}/edit')">结算</button>
                                            @else
                                            <button class="layui-btn layui-btn-disabled">结算</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            <div id="tip" style="width: 100%;text-align: center;color: grey;margin-top: 30px;display: {{$contracts->isEmpty()?'':'none'}}">
                                <span>--无数据--</span>
                            </div>
                        </div>
                        {{ $contracts->links()}}
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
            var underwriting_date_start = laydate.render({
                elem: '#underwriting_date_start', //指定元素
                // value: new Date(new Date().setDate(1)),     //每个月的1号
                max:0,
                done:function(value,date){
                    // if(value && (value > $("#underwriting_date_end").val())){
                    //     $("#underwriting_date_end").val(value);
                    // }
                    underwriting_date_end.config.min ={
                        year:date.year,
                        month:date.month-1,
                        date: date.date,
                    };
                }
            });
            var underwriting_date_end = laydate.render({
                elem: '#underwriting_date_end', //指定元素
                // value: new Date(),
                max:0,
                min:$("#underwriting_date_start").val()
            });

            form.on('select(organization)', function(data){
                var organization_id = data.value;
                $('#department').empty();   //清空department
                var temp_node = document.createElement('option');
                temp_node.value = '';
                if(organization_id){
                    temp_node.innerText = '请选择部门';
                }else{
                    temp_node.innerText = '请先选择机构';
                }
                
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
        
    </script>

</html>
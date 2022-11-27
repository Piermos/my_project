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
            .color-red{
                color:red
            }
            .color-green{
                color:green
            }
        </style>
    </head>
    
    <body>
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="#">首页</a>
                <a href="#">产品管理</a>
                <a>
                    <cite>产品列表</cite></a>
            </span>
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
                <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
            </a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15" style="text-align: center;">
                <form class="layui-form layui-form-pane" action="/report_form/new_contract/export">
                    <div class="layui-form-item">
                        <label for="insurance_company_id" class="layui-form-label">
                            险司</label>
                        <div class="layui-input-inline">
                            <select name="insurance_company_id" lay-search>
                                <option value="">请选择</option>
                                @foreach($insuranceCompanys as $insuranceCompany)
                                <option value="{{$insuranceCompany->insurance_company_id}}" {{isset($requestData['insurance_company_id']) && ($requestData['insurance_company_id'] == $insuranceCompany->insurance_company_id) ? 'selected' : ''}}>{{$insuranceCompany->insurance_company_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="organization_id" class="layui-form-label">
                            机构</label>
                        <div class="layui-input-inline">
                            <select name="organization_id" lay-filter="organization_id" lay-search>
                                <option value="">请选择</option>
                                @foreach($organizations as $organization)
                                <option value="{{$organization->organization_id}}" {{isset($requestData['organization_id']) && ($requestData['organization_id'] == $organization->organization_id) ? 'selected' : ''}}>{{$organization->organization_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="policy_status_id" class="layui-form-label">
                            保单状态</label>
                        <div class="layui-input-inline">
                            <select name="policy_status_id">
                                <option value="">请选择</option>
                                @foreach($policyStatus as $value)
                                <option value="{{$value->policy_status_id}}" {{isset($requestData['policy_status_id']) && $requestData['policy_status_id'] == $value->policy_status_id ?'selected' : ''}}>{{$value->policy_status_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="policy_status_id" class="layui-form-label">
                            <span class="x-red">*</span>日期类型
                        </label>
                        <div class="layui-input-inline">
                            <select name="date_type" lay-verify="required">
                                <option value="">请选择</option>
                                <option value="insurance" {{isset($requestData['date_type']) && $requestData['date_type'] == 'insurance' ?'selected' : ''}}>录单日期</option>
                                <option value="underwriting" {{isset($requestData['date_type']) && $requestData['date_type'] == 'underwriting' ?'selected' : ''}}>承保日期</option>
                            </select>
                        </div>
                        <label for="date_start" class="layui-form-label">
                            开始日期</label>
                        <div class="layui-input-inline">
                            <input type="text" id="date_start" name="date_start" value="{{isset($requestData['date_start'])?$requestData['date_start']:''}}"
                            autocomplete="off" class="layui-input">
                        </div>
                        <label for="insurance_date_end" class="layui-form-label">
                            结束日期</label>
                        <div class="layui-input-inline">
                            <input type="text" id="date_end" name="date_end" value="{{isset($requestData['date_end'])?$requestData['date_end']:''}}"
                            autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline layui-show-xs-block">
                        <button class="layui-btn" lay-filter="export" lay-submit="">
                            下载数据
                        </button>
                    </div>
                </form>
                
            </div>
            <hr>
            <div class="layui-card-body" id="message">
                {!!$message!!}
            </div>
        </div>
    </body>
    <script>
        layui.use(['laydate', 'form', 'laydate'],function() {
            $ = layui.jquery;
            var form = layui.form,
                layer = layui.layer,
                laydate = layui.laydate;

            //录单时间
            var date_start = laydate.render({
                elem: '#date_start', //指定元素
                value: new Date(new Date().setDate(1)),     //每个月的1号
                max:0,
                done:function(value,date){
                    if(value && (value>$("#date_end").val())){
                        $("#date_end").val(value);
                    }
                    date_end.config.min ={
                        year:date.year,
                        month:date.month-1,
                        date: date.date,
                    };
                }
            });
            var date_end = laydate.render({
                elem: '#date_end', //指定元素
                value: new Date(),
                max:0,
                min:$("#date_start").val()
            });

            form.on('submit(export)', function(data){
                var message = '<div class="color-green"><span>正在下载</span></div>'
                $('#message')[0].innerHTML = message;
            });

        });

        /*用户-删除*/
        function department_del(obj, id) {
            layer.confirm('确认要删除吗？',
            function(index) {
                //发异步删除数据
                
                $.ajax({
                    url: '/person/department/list/delete/'+id,
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'delete',
                    dataType: 'json',
                    success:function(data) {
                        if(data.code == 200){
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
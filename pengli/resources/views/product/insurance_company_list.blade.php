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
                <a href="#">人员管理</a>
                <a>
                    <cite>部门列表</cite></a>
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
                            <form class="layui-form layui-col-space5" action="{{$insuranceCompanys->path()}}">
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="insurance_company_name" value="{{isset($requestData['insurance_company_name'])?$requestData['insurance_company_name']:''}}"  placeholder="请输入险司名称" autocomplete="off" class="layui-input">
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
                                        <th>险司名称</th>
                                        <th>合作开始时间</th>
                                        <th>合作结束时间</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($insuranceCompanys as $insuranceCompany)
                                    <tr>
                                        <td>{{$insuranceCompany->insurance_company_name}}</td>
                                        <td>{{$insuranceCompany->cooperation_date_start}}</td>
                                        <td>{{$insuranceCompany->cooperation_date_end}}</td>
                                        <td>{{$insuranceCompany->status}}</td>
                                        <td class="td-manage">
                                            <button class="layui-btn" onclick="xadmin.open('编辑','/person/department/list/{{$insuranceCompany->insurance_company_id}}/edit')">编辑</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            <div id="tip" style="width: 100%;text-align: center;color: grey;margin-top: 30px;display: {{$insuranceCompanys->isEmpty()?'':'none'}}">
                                <span>--无数据--</span>
                            </div>
                        </div>
                        {{ $insuranceCompanys->links()}}
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        layui.use(['laydate', 'form'],function() {
            $ = layui.jquery;
            var form = layui.form,
                layer = layui.layer;

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
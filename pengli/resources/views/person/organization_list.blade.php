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
                    <cite>单位列表</cite></a>
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
                            <form class="layui-form layui-col-space5" action="{{$departments->path()}}">
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="department_name" value="{{isset($requestData['department_name'])?$requestData['department_name']:''}}"  placeholder="请输入部门名称" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="organization_id" lay-filter="organization_id" lay-search>
                                        <option value="">请选择机构</option>
                                        @foreach($organizations as $organization)
                                        <option value="{{$organization->organization_id}}" {{isset($requestData['organization_id']) && ($requestData['organization_id'] == $organization->organization_id) ? 'selected' : ''}}>{{$organization->organization_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="grade">
                                        <option value="">请选择部门职级</option>
                                        <option value="营业部" {{isset($requestData['grade']) && $requestData['grade']=='营业部' ?'selected' : ''}}>营业部</option>
                                        <option value="筹备营业部" {{isset($requestData['grade']) && $requestData['grade']=='筹备营业部' ?'selected' : ''}}>筹备营业部</option>
                                    </select>
                                </div>
                                
                                <div class="layui-inline layui-show-xs-block">
                                    <button type="submit" lay-submit class="layui-btn">查询</button>
                                </div>
                                <!-- <div class="layui-inline layui-show-xs-block">
                                    <button type="button" class="layui-btn" onclick="xadmin.del_tab()">重置</button>
                                </div> -->
                            </form>
                        </div>
                        <div class="layui-card-body ">
                                <div class="layui-inline layui-show-xs-block">
                                    <button type="button" class="layui-btn" onclick="xadmin.open('新增','/person/department/add')">新增</button>
                                </div>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                                <thead>
                                    <tr>
                                        <th>部门名称</th>
                                        <th>部门入司职级</th>
                                        <th>部门当前职级</th>
                                        <th>部门主管</th>
                                        <th>所属机构</th>
                                        <th>部门状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($departments as $department)
                                    <tr>
                                        <td>{{$department->department_name}}</td>
                                        <td>{{$department->initial_grade}}</td>
                                        <td>{{$department->current_grade}}</td>
                                        <td>{{$department->leader}}</td>
                                        <td>{{$department->organization_name}}</td>
                                        <td>{{$department->department_status==1?'正常':'撤销'}}</td>
                                        <td class="td-manage">
                                            <button class="layui-btn" onclick="xadmin.open('编辑','/person/department/list/{{$department->department_id}}/edit')">编辑</button>
                                            <!-- <button class="layui-btn layui-btn-normal layui-btn-danger" onclick="department_del(this,{{$department->department_id}})">删除</button> -->
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            <div id="tip" style="width: 100%;text-align: center;color: grey;margin-top: 30px;display: {{$departments->isEmpty()?'':'none'}}">
                                <span>--无数据--</span>
                            </div>
                        </div>
                        {{ $departments->links()}}
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
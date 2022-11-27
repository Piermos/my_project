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
                    <cite>代理人列表</cite></a>
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
                            <form class="layui-form layui-col-space5" action="{{$proxys->path()}}">
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="job_number" value="{{isset($requestData['job_number'])?$requestData['job_number']:''}}"  placeholder="请输入工号" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" id="name" name="proxy_name" value="{{isset($requestData['proxy_name'])?$requestData['proxy_name']:''}}" placeholder="请输入姓名" autocomplete="off" class="layui-input" onkeyup="this.value=this.value.replace(/[, ]/g,'')">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="mobile" value="{{isset($requestData['mobile'])?$requestData['mobile']:''}}"  placeholder="请输入手机号码" autocomplete="off" class="layui-input">
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
                                    <select id="department" name="department_id" lay-search>
                                        <option value="">请选择部门</option>
                                        @foreach($departments as $department)
                                        <option value="{{$department->department_id}}" {{isset($requestData['department_id']) && ($requestData['department_id'] == $department->department_id) ? 'selected' : ''}}>{{$department->department_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="status">
                                        <option value="">请选择状态</option>
                                        <option value="1" {{isset($requestData['status']) && $requestData['status']==1 ?'selected' : ''}}>在职</option>
                                        <option value="2" {{isset($requestData['status']) && $requestData['status']==2 ?'selected' : ''}}>离职</option>
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="work_type">
                                        <option value="">请选择工作性质</option>
                                        <option value="1" {{isset($requestData['work_type']) && $requestData['work_type']==1 ?'selected' : ''}}>全职</option>
                                        <option value="2" {{isset($requestData['work_type']) && $requestData['work_type']==2 ?'selected' : ''}}>兼职</option>
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
                                <button type="button" class="layui-btn" onclick="xadmin.open('新增','/person/proxy/add')">新增</button>
                            </div>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                                <thead>
                                    <tr>
                                        <th>鹏利工号</th>
                                        <th>姓名</th>
                                        <th>手机号码</th>
                                        <th>职级</th>
                                        <th>部门</th>
                                        <th>机构</th>
                                        <th>性质</th>
                                        <th>入职日期</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($proxys as $proxy)
                                    <tr>
                                        <td>{{$proxy->job_number}}</td>
                                        <td>{{$proxy->proxy_name}}</td>
                                        <td>{{$proxy->mobile}}</td>
                                        <td>{{$proxy->rank_name}}</td>
                                        <td>{{$proxy->department_name}}</td>
                                        <td>{{$proxy->organization_name}}</td>
                                        <td>
                                            {{$proxy->work_type==1?'全职':'兼职'}}
                                        </td>
                                        <td>{{$proxy->entry_date}}</td>
                                        <td class="td-status">
                                            
                                            @if ($proxy->status == 1)
                                            <span style="color: rgb(21,150,136)">在职</span>
                                            @else
                                            <span style="color: grey">离职</span>
                                            @endif
                                        </td>
                                        <td class="td-manage">
                                            <button class="layui-btn" onclick="xadmin.open('编辑','/person/proxy/list/{{$proxy->proxy_id}}/edit')">编辑</button>
                                            <button class="layui-btn layui-btn-normal layui-btn-danger" onclick="proxy_del(this,{{$proxy->proxy_id}})">删除</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            <div id="tip" style="width: 100%;text-align: center;color: grey;margin-top: 30px;display: {{$proxys->isEmpty()?'':'none'}}">
                                <span>--无数据--</span>
                            </div>
                        </div>
                        {{ $proxys->links()}}
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


            form.on('select(organization_id)', function(data){
                var organization_id = data.value;
                $('#department').empty();   //清空department
                var temp_node = document.createElement('option');
                temp_node.value = '';
                temp_node.innerText = '请选择部门';
                $('#department').append(temp_node);
                form.render();
                
                $.ajax({
                    url:  '/person/proxy/add/retrieval',
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
        });

        /*用户-删除*/
        function proxy_del(obj, id) {
            layer.confirm('确认要删除吗？',
            function(index) {
                //发异步删除数据
                
                $.ajax({
                    url: '/person/proxy/list/delete/'+id,
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
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
                <a href="#">方案管理</a>
                <a>
                    <cite>方案列表</cite></a>
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
                            <form class="layui-form layui-col-space5" action="{{$schemes->path()}}">
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="scheme_name" value="{{isset($requestData['scheme_name'])?$requestData['scheme_name']:''}}"  placeholder="请输入方案名称" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <button type="submit" lay-submit class="layui-btn">查询</button>
                                </div>
                            </form>
                            
                        </div>
                        <div class="layui-card-body ">
                            <div class="layui-inline layui-show-xs-block">
                                <button type="button" class="layui-btn" onclick="xadmin.open('新增','/scheme/add')">新增</button>
                            </div>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                                <thead>
                                    <tr>
                                        <th>方案名称</th>
                                        <th>开始时间</th>
                                        <th>结束时间</th>
                                        <th>承保截止时间</th>
                                        <th>回执回销<br/>截止时间</th>
                                        <th>资金险系数</th>
                                        <th>健康险系数</th>
                                        <th>其他险种<br/>系数</th>
                                        <th>旅游保费</th>
                                        <th>旅游保费<br/>要求险种</th>
                                        <th>额外加点奖励</th>
                                        <th>是否要求车险</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schemes as $scheme)
                                    <tr>
                                        <td>{{$scheme->scheme_name}}</td>
                                        <td>{{$scheme->scheme_start_date}}</td>
                                        <td>{{$scheme->scheme_end_date}}</td>
                                        <td>{{$scheme->underwriting_end_date}}</td>
                                        <td>{{$scheme->receipt_end_date}}</td>
                                        <td>
                                            趸交：{{$scheme->capital_insurance_ratio_1}}
                                            <br/>
                                            3年交：{{$scheme->capital_insurance_ratio_3}}
                                            <br/>
                                            5年交：{{$scheme->capital_insurance_ratio_5}}
                                            <br/>
                                            10年交及以上：{{$scheme->capital_insurance_ratio_10}}
                                        </td>
                                        <td>
                                            5年交：{{$scheme->health_insurance_ratio_5}}
                                            <br/>
                                            10年交：{{$scheme->health_insurance_ratio_10}}
                                            <br/>
                                            15年交：{{$scheme->health_insurance_ratio_15}}
                                            <br/>
                                            19年交及以上：{{$scheme->health_insurance_ratio_19}}
                                        </td>
                                        <td>
                                            意外险：{{$scheme->accident_insurance_ratio}}
                                            <br/>
                                            团险：{{$scheme->group_insurance_ratio}}
                                            <br/>
                                            车险：{{$scheme->car_insurance_ratio}}
                                            <br/>
                                        </td>
                                        <td>{{$scheme->travel_premium}}</td>
                                        <td>{{$scheme->travel_insurance_limit==1?'所有险种':'资金险'}}</td>
                                        <td>{{$scheme->add_ratio}}%</td>
                                        <td>{{$scheme->is_car_insurance==1?'否':'是'}}</td>
                                        <td class="td-manage">
                                            <button class="layui-btn" onclick="xadmin.open('编辑','/scheme/{{$scheme->scheme_id}}/edit')">编辑</button>
                                            <button class="layui-btn" onclick="xadmin.open('目标分解','/scheme/{{$scheme->scheme_id}}/decompose_goals')">目标分解</button>
                                            <button class="layui-btn layui-btn-normal layui-btn-danger" onclick="del(this,{{$scheme->scheme_id}})">删除</button>
                                            
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            <div id="tip" style="width: 100%;text-align: center;color: grey;margin-top: 30px;display: {{$schemes->isEmpty()?'':'none'}}">
                                <span>--无数据--</span>
                            </div>
                        </div>
                        {{ $schemes->links()}}
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
        function del(obj, id) {
            layer.confirm('确认要删除吗？',
            function(index) {
                //发异步删除数据
                
                $.ajax({
                    url: '/scheme/'+id+'/delete/',
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
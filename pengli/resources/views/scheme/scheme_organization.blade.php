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

            .layui-table{
                width: 80%;margin-left: 10%;
            }
            /** 表格格式 */
            .layui-table .my-table-title th{
                width: 100%;text-align: center;font-size:22px;font-weight: bold;line-height: 35px;
                background-color: rgb(255,255,255);color: rgb(255,0,0);margin-bottom: -5px;border: none;
            }
            /**表头 */
            .layui-table .my-table-head th{
                font-size:14px;font-weight: bold;padding: 8px 0px;
                background-color:rgb(192,80,77);color: rgb(255,255,255);border: 1px solid black;
            }
            .layui-table .my-table-body td{
                font-size:14px;font-weight: bold;color: black;border: 1px solid black;
            }
        </style>
    </head>
    
    <body>
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="#">首页</a>
                <a href="#">业务管理</a>
                <a>
                    <cite>新契约保单</cite></a>
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
                                    <select name="scheme_id">
                                        <option value="">请选择方案</option>
                                        @foreach($schemes as $value)
                                        <option value="{{$value->scheme_id}}" {{isset($requestData['scheme_id']) && $requestData['scheme_id'] == $value->scheme_id ?'selected' : ''}}>{{$value->scheme_name}}</option>
                                        @endforeach
                                    </select>
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
                                    <input type="text" name="insurance_start_date" value="{{isset($requestData['insurance_start_date'])?$requestData['insurance_start_date']:''}}"  placeholder="录单开始时间" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="insurance_end_date" value="{{isset($requestData['insurance_end_date'])?$requestData['insurance_end_date']:''}}"  placeholder="录单结束时间" autocomplete="off" class="layui-input">
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
                            <table class="layui-table layui-form" lay-size="sm">
                                <thead class="my-table-title">
                                    <tr>
                                        <th colspan="14">鹏利保代2022开门红二阶段本级二区部门业务追踪表
                                            <br>
                                            <span style="font-size: 14px;">统计时间&nbsp;:&nbsp;&nbsp;<span>2022-10-15</span>&nbsp;:&nbsp;&nbsp;序时进度&nbsp;:&nbsp;&nbsp;<span>80%</span></span>
                                        </th>
                                    </tr>

                                </thead>
                                <thead class="my-table-head">
                                    <tr>
                                        <th>排名</th>
                                        <th>部门</th>
                                        <th>保底目标</th>
                                        <th>冲刺目标</th>
                                        <th>健康险目标</th>
                                        <th>今日预收</th>
                                        <th>已达成<br/>方案保费</th>
                                        <th>已达成<br/>标准保费</th>
                                        <th>保底目标<br/>达成率</th>
                                        <th>保底目标<br/>保费差额</th>
                                        <th>已达成<br/>健康险保费</th>
                                        <th>健康险<br/>达成率</th>
                                        <th>健康险<br/>目标差额</th>
                                        <th>出单人力</th>
                                    </tr>
                                </thead>
                                <tbody class="my-table-body">
                                    @foreach($departments as $department)
                                    <tr>
                                        <td>1</td>
                                        <td>飞燕部</td>
                                        <td>10000000</td>
                                        <td>1600000</td>
                                        <td>160000</td>
                                        <td>100000</td>
                                        <td>800000</td>
                                        <td>500000</td>
                                        <td>80%</td>
                                        <td>200000</td>
                                        <td>80000</td>
                                        <td>50%</td>
                                        <td>800000</td>
                                        <td>4</td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                                
                                <tfoot class="my-table-head">
                                    <tr>
                                        <th colspan="2">合计</th>
                                        <th>10000000</th>
                                        <th>1600000</th>
                                        <th>160000</th>
                                        <th>100000</th>
                                        <th>800000</th>
                                        <th>500000</th>
                                        <th>80%</th>
                                        <th>200000</th>
                                        <th>80000</th>
                                        <th>50%</th>
                                        <th>800000</th>
                                        <th>4</th>
                                    </tr>
                                </tfoot>
                                
                            </table>
                            <hr>
                            <table class="layui-table layui-form" lay-size="sm">
                                <thead class="my-table-title">
                                    <tr>
                                        <th colspan="14">鹏利保代2022开门红二阶段本级二区个人业务追踪表
                                            <br>
                                            <span style="font-size: 14px;">统计时间&nbsp;:&nbsp;&nbsp;<span>2022-10-15</span>&nbsp;:&nbsp;&nbsp;序时进度&nbsp;:&nbsp;&nbsp;<span>80%</span></span>
                                        </th>
                                    </tr>

                                </thead>
                                <thead class="my-table-head">
                                    <tr>
                                        <th>排名</th>
                                        <th>部门</th>
                                        <th>业务伙伴</th>
                                        <th>已达成<br/>方案保费</th>
                                        <th>已达成<br/>旅游保费</th>
                                        <th>已达成<br/>健康险保费</th>
                                        <th>已达成<br/>旅游名额</th>
                                        <th>距下一名额<br/>保费差额</th>
                                        <th>加点奖励</th>
                                        <th>车险件数</th>
                                    </tr>
                                </thead>
                                <tbody class="my-table-body">
                                    @foreach($departments as $department)
                                    <tr>
                                        <td>1</td>
                                        <td>飞燕部</td>
                                        <td>陈琴燕</td>
                                        <td>800000</td>
                                        <td>800000</td>
                                        <td>80000</td>
                                        <td>4</td>
                                        <td>200000</td>
                                        <td>158</td>
                                        <td>1</td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                                
                                <tfoot class="my-table-head">
                                    <tr>
                                        <th colspan="3">合计</th>
                                        <th>800000</th>
                                        <th>800000</th>
                                        <th>80000</th>
                                        <th>4</th>
                                        <th>200000</th>
                                        <th>158</th>
                                        <th>1</th>
                                        
                                    </tr>
                                </tfoot>
                                
                            </table>
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

        function exportExcel(){
            console.log();
            window.open('/file/export');
        }
    </script>

</html>
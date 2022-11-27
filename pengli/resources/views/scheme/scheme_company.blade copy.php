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
                font-size:16px;font-weight: bold;padding: 8px 0px;
                background-color:rgb(192,80,77);color: rgb(255,255,255);border: 1px solid black;
            }
            .layui-table .my-table-body td{
                font-size:16px;font-weight: bold;color: black;border: 1px solid black;
            }
            /* .layui-table .my-table-foot th{
                font-size:14px;font-weight: bold;padding: 6px 0px;border: 1px solid black;
                background-color:rgb(255,255,0);color: rgb(255,0,0);
            } */
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
                            <form class="layui-form layui-col-space5" action="{{$organizations->path()}}">
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="scheme_id">
                                        <option value="">请选择方案</option>
                                        @foreach($schemes as $scheme)
                                        <option value="{{$scheme->scheme_id}}" {{isset($requestData['scheme_id']) && $requestData['scheme_id'] == $scheme->scheme_id ?'selected' : ''}}>{{$scheme->scheme_name}}</option>
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
                            <table class="layui-table layui-form">
                                <thead class="my-table-title">
                                    <tr>
                                        <th colspan="10">鹏利保代2022开门红二阶段业务追踪表</th>
                                    </tr>
                                </thead>
                                <thead class="my-table-head">
                                    <tr>
                                        <th rowspan="2">排名</th>
                                        <th rowspan="2">部门</th>
                                        <th colspan="3">今日预收</th>
                                        <th colspan="3">累计预收</th>
                                        <th rowspan="2">寿险出单人力</th>
                                        <th rowspan="2">合计</th>
                                    </tr>
                                    <tr>
                                        <th>寿险</th>
                                        <th>车险</th>
                                        <th>团险</th>
                                        <th>寿险</th>
                                        <th>车险</th>
                                        <th>团险</th>
                                    </tr>
                                </thead>
                                <tbody class="my-table-body">
                                    @foreach($organizations as $organization)
                                    <tr>
                                        <td>1</td>
                                        <td></td>
                                        <td>10,000,000</td>
                                        <td>20000</td>
                                        <td>50000</td>
                                        <td>10,000,000</td>
                                        <td>20,000</td>
                                        <td>50,000</td>
                                        <td>1</td>
                                        <td>10,000,000</td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                                
                                <tfoot class="my-table-head">
                                    <tr>
                                        <th colspan="2" rowspan="2">合计</th>
                                        <th>100,000</th>
                                        <th>100,000</th>
                                        <th>100,000</th>
                                        <th>100,000</th>
                                        <th>100,000</th>
                                        <th>100,000</th>
                                        <th rowspan="2">6</th>
                                        <th rowspan="2">200000</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">200000</th>
                                        <th colspan="3">200000</th>
                                    </tr>
                                </tfoot>
                                
                            </table>
                            <hr>
                            <table class="layui-table layui-form">
                                <thead class="my-table-title">
                                    <tr>
                                        <th colspan="10">鹏利保代2022开门红二阶段业务追踪表</th>
                                    </tr>
                                </thead>
                                <thead class="my-table-head">
                                    <tr>
                                        <th>排名</th>
                                        <th>单位</th>
                                        <th>总任务目标</th>
                                        <th>已达成折算保费</th>
                                        <th>达成率</th>
                                        <th>目标差额</th>
                                        <th>出单人力</th>
                                        <th>达成荣誉</th>
                                    </tr>
                                </thead>
                                <tbody class="my-table-body">
                                    @foreach($organizations as $department)
                                    <tr>
                                        <td>1</td>
                                        <td>本级二区</td>
                                        <td>1,200,000</td>
                                        <td>1,580,000</td>
                                        <td>138%</td>
                                        <td>已达成</td>
                                        <td>2</td>
                                        <td>优秀机构</td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                                
                                <tfoot class="my-table-head">
                                    <tr>
                                        <th colspan="2" rowspan="2">合计</th>
                                        <th>20,000,000</th>
                                        <th>30,000,000</th>
                                        <th>150%</th>
                                        <th>已达成</th>
                                        <th>2</th>
                                        <th>-</th>
                                    </tr>
                                </tfoot>
                                
                            </table>

                        </div>
                        {{ $organizations->links()}}
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
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
                <a href="#">产品管理</a>
                <a>
                    <cite>产品列表</cite></a>
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
                            <form class="layui-form layui-col-space5" action="{{$products->path()}}">
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="product_name" value="{{isset($requestData['product_name'])?$requestData['product_name']:''}}"  placeholder="请输入险种名称" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="insurance_company_id" lay-filter="insurance_company_id" lay-search>
                                        <option value="">请选择险司</option>
                                        @foreach($insuranceCompanys as $insuranceCompany)
                                        <option value="{{$insuranceCompany->insurance_company_id}}" {{isset($requestData['insurance_company_id']) && ($requestData['insurance_company_id'] == $insuranceCompany->insurance_company_id) ? 'selected' : ''}}>{{$insuranceCompany->insurance_company_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="product_category_id">
                                        <option value="">请选择产品类别</option>
                                        @foreach($productCategorys as $productCategory)
                                        <option value="{{$productCategory->product_category_id}}" {{isset($requestData['product_category_id']) && ($requestData['product_category_id'] == $productCategory->product_category_id) ? 'selected' : ''}}>{{$productCategory->product_category_name}}</option>
                                        @endforeach
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
                                    <button type="button" class="layui-btn" onclick="xadmin.open('新增','/product/add','','',true)">新增</button>
                                </div>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                                <thead>
                                    <tr>
                                        <th>产品名称</th>
                                        <th>所属险司</th>
                                        <th>险种类型</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{$product->insurance_company_name}}</td>
                                        <td>{{$product->product_category_name}}</td>
                                        <td class="td-status">
                                            @if ($product->product_status == 1)
                                            <span class="layui-btn layui-btn-mini">在售</span></td>
                                            @else
                                            <span class="layui-btn layui-btn-normal layui-btn-mini layui-btn-disabled">已停售</span></td>
                                            @endif
                                        </td>
                                
                                        <td class="td-manage">
                                            <button class="layui-btn layui-btn-normal" onclick="xadmin.open('产品详情','/product/list/{{$product->product_id}}/edit')">查看佣金</button>
                                            <!-- <button class="layui-btn layui-btn-normal layui-btn-danger" onclick="department_del(this,{{$product->product_id}})">删除</button> -->
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            <div id="tip" style="width: 100%;text-align: center;color: grey;margin-top: 30px;display: {{$products->isEmpty()?'':'none'}}">
                                <span>--无数据--</span>
                            </div>
                        </div>
                        {{ $products->links()}}
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
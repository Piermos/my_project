<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>鹏利保代</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/xadmin.css">
<!--    <link rel="stylesheet" href="../css/pagination.css">-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="../lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/xadmin.js"></script>
    <script type="text/javascript" src="/js/jquery.min.js"></script>
<!--    <script type="text/javascript" src="../js/jquery.pagination.js"></script>-->
    <script type="text/javascript" src="../js/jq-paginator.js"></script>
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
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body layui-table-body layui-table-main">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>角色名称</th>
                            <th>状态</th>
                            <th>操作</th></tr>
                        </thead>
                        <tbody id="list">
                        @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td class="td-status">
                                @if ($role->status == 1)
                                <span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span></td>
                                @else
                                <span class="layui-btn layui-btn-normal layui-btn-mini layui-btn-disabled">已停用</span></td>
                                @endif
                            </td>
                            <td>
                                <button class="layui-btn" onclick="xadmin.open('编辑','/permission/authorization/{{$role->id}}')"><i class="layui-icon">&#xe642;</i>授权</button>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>

                    </table>
                    <div id="tip" style="width: 100%;text-align: center;color: grey;margin-top: 30px;display: none">
                        <span>--无数据--</span>
                    </div>

                </div>
                <div class="layui-card-body ">
                    <div class="my-pag">
                        <ul id="pagination" class="pagination"></ul>
                    </div>
                    <div class="my-pag">
                        <span id="total_page"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="../js/base.js"></script>
<script>    

</script>

</html>
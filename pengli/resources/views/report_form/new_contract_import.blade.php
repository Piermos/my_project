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
                <form class="layui-form layui-form-pane">
                    <div class="layui-form-item">
                    <button type="button" class="layui-btn" id="import">
                        <i class="layui-icon">&#xe67c;</i>上传保单
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
        layui.use(['laydate', 'form', 'upload'],function() {
            $ = layui.jquery;
            var form = layui.form,
                layer = layui.layer,
                upload = layui.upload;

            var uploadImport = upload.render({
                elem: '#import' //绑定元素
                ,url: '/report_form/new_contract/import' //上传接口
                ,headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                ,data:{
                    id:'newContract'
                }
                ,accept: 'file'
                ,before: function(obj){
                    var message = '<div class="color-green"><span>正在上传，请稍后...</span></div>'
                    $('#message')[0].innerHTML = message;
                    var load = layer.load(); //上传loading
                }
                ,done: function(res){
                    console.log(res);
                    var message = '<div class="color-green"><span>'+res.message+'</span></div>'
                    $('#message')[0].innerHTML = message;
                    layer.closeAll('loading'); //上传loading
                }
                ,error: function(){
                    console.log(500);
                    layer.closeAll('loading');
                }
            });

            form.on('submit(import)', function(data){
                var message = '<div class="color-green"><span>正在上传</span></div>'
                $('#message')[0].innerHTML = message;
            });

        });

    </script>

</html>
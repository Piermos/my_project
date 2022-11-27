<!DOCTYPE html>
<html class="x-admin-sm">
  
  <head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.2</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/xadmin.css">
    <script type="text/javascript" src="/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/xadmin.js"></script>
  </head>
  
  <body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form action="" method="post" class="layui-form layui-form-pane">
                <input type="hidden" name="id" value="{{$role->id}}">
                <div class="layui-form-item">
                    <label for="name" class="layui-form-label">
                        角色名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" required="" lay-verify="required" value="{{$role->name}}"
                        autocomplete="off" class="layui-input" readonly>
                    </div>
                </div>
                <!-- <div class="layui-form-item">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-block">
                    <input type="radio" name="status" value="1" lay-skin="primary" title="启用" {{$role->status==1?'checked=""':''}}>
                    <input type="radio" name="status" value="2" lay-skin="primary" title="禁用" {{$role->status==2?'checked=""':''}}>
                    </div>
                </div> -->
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">
                        拥有权限
                    </label>
                    <table  class="layui-table layui-input-block">
                        <tbody>
                            @foreach($perms as $key=>$perm)
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" name="like1[write]" lay-skin="primary" lay-filter="father" title="{{$key}}">
                                </td>
                                @if(!empty($perm))
                                <td>
                                    @foreach($perm as $v)
                                    <div class="layui-input-block">
                                        <input name="permission_ids[]" lay-skin="primary" type="checkbox" value="{{$v['id']}}" title="{{$v['title']}}" {{in_array($v['id'],$role_perm_ids)?'checked':''}}> 
                                        <br/> &emsp;&emsp;&emsp;
                                        @if(isset($v['son']))
                                        @foreach($v['son'] as $p)
                                        <input name="permission_ids[]" lay-skin="primary" type="checkbox" value="{{$p['id']}}" title="{{$p['title']}}" {{in_array($p['id'],$role_perm_ids)?'checked':''}}> 
                                        @endforeach
                                        @endif
                                    </div>
                                    @endforeach
                                   
                                </td>
                               @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="layui-form-item" style="text-align: center;">
                    <button class="layui-btn" lay-submit="" lay-filter="add">保&emsp;存</button>
              </div>
            </form>
        </div>
    </div>
    <script>
        layui.use(['form','layer'], function(){
            $ = layui.jquery;
          var form = layui.form
          ,layer = layui.layer;
        
          //自定义验证规则
          form.verify({
            nikename: function(value){
              if(value.length < 5){
                return '昵称至少得5个字符啊';
              }
            }
            ,pass: [/(.+){6,12}$/, '密码必须6到12位']
            ,repass: function(value){
                if($('#L_pass').val()!=$('#L_repass').val()){
                    return '两次密码不一致';
                }
            }
          });

          //监听提交
          form.on('submit(add)', function(data){
            // console.log(data.field);
            //发异步，把数据提交给php
            $.ajax({
                url:  '/permission/authorization',
                data: data.field,
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                success:function(data) {
                    layer.msg(data.message, {icon: 6,time:500},function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                },
                error:function(data){
                    layer.msg('服务器连接失败');
                }
            });
            return false;
          });


        form.on('checkbox(father)', function(data){

            if(data.elem.checked){
                $(data.elem).parent().siblings('td').find('input').prop("checked", true);
                form.render(); 
            }else{
               $(data.elem).parent().siblings('td').find('input').prop("checked", false);
                form.render();  
            }
        });
          
          
        });
    </script>
    
  </body>

</html>
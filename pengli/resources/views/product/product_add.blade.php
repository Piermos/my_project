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
    <style>
        /*表格内容居中*/
        .layui-table th,tr{
                text-align: center;
            }
        .layui-input{
            border: none;margin: auto;text-align: center;
        }
    </style>
  </head>
  
  <body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form ">
                <input type="hidden" name="product_id" value="{{isset($product)?$product->product_id:''}}">
                <div class="layui-form-item">
                    <label for="insurance_company_id" class="layui-form-label">
                        <span class="x-red">*</span>保险公司
                    </label>
                    <div class="layui-input-inline">
                        <select class="valid" name="insurance_company_id" lay-verify="required" lay-search>
                            <option value="">请选择</option>
                            @foreach($insuranceCompanys as $insuranceCompany)
                            <option value="{{$insuranceCompany->insurance_company_id}}"  {{isset($product) && $product->insurance_company_id == $insuranceCompany->insurance_company_id? 'selected':''}}>{{$insuranceCompany->insurance_company_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="product_name" class="layui-form-label">
                        <span class="x-red">*</span>产品名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" name="product_name" lay-verify="required" value="{{isset($product)?$product->product_name:''}}"
                        autocomplete="off" class="layui-input" style="width: 190px;">
                    </div>
                    <label for="product_category_id" class="layui-form-label">
                        <span class="x-red">*</span>产品类型
                    </label>
                    <div class="layui-input-inline">
                        <select class="valid" name="product_category_id" lay-verify="required" lay-search>
                            <option value="">请选择</option>
                            @foreach($productCategorys as $productCategory)
                            <option value="{{$productCategory->product_category_id}}" {{isset($product) && $product->product_category_id == $productCategory->product_category_id? 'selected':''}}>{{$productCategory->product_category_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="product_status" class="layui-form-label">
                        <span class="x-red">*</span>产品状态
                    </label>
                    <div class="layui-input-inline">
                        <select class="valid" name="product_status" lay-verify="required" lay-search>
                            <option value="1" {{isset($product) && $product->product_status == 1? 'selected':''}}>在售</option>
                            <option value="2" {{isset($product) && $product->product_status == 2? 'selected':''}}>停售</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="hesitation_period" class="layui-form-label">
                        <span class="x-red">*</span>犹豫期天数
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" name="hesitation_period" lay-verify="required" value="{{isset($product)?$product->hesitation_period:''}}"
                        autocomplete="off" class="layui-input" style="width: 190px;">
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <table  class="layui-table layui-input-block">
                        <thead>
                            <tr>
                                <th>佣金率</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="layui-input-block">
                                        <table  class="layui-table">
                                            <thead>
                                                <tr>
                                                    <th>缴费期间（年）</th>
                                                    <th>首佣（%）</th>
                                                    <th>第二年（%）</th>
                                                    <th>第三年（%）</th>
                                                    <th>第四年（%）</th>
                                                    <th>第五年（%）</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody id="demo">
                                                @if(!isset($commissions) || $commissions->isEmpty())
                                                <tr>
                                                    <td>
                                                        <input type="text" name="new_payment_period" class="layui-input" value="" autocomplete="off">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="new_first_year_rate" class="layui-input" value="" autocomplete="off">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="new_second_year_rate" class="layui-input" value="" autocomplete="off">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="new_third_year_rate" class="layui-input" value="" autocomplete="off">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="new_fourth_year_rate" class="layui-input" value="" autocomplete="off">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="new_fifth_year_rate" class="layui-input" value="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                @else
                                                @foreach($commissions as $commission)
                                                <tr>
                                                    <td>
                                                        <input type="text" name="{{$commission->commission_id}}_payment_period" class="layui-input" value="{{$commission->payment_period}}" autocomplete="off">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="{{$commission->commission_id}}_first_year_rate" class="layui-input" value="{{$commission->first_year_rate}}" autocomplete="off">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="{{$commission->commission_id}}_second_year_rate" class="layui-input" value="{{$commission->second_year_rate}}" autocomplete="off">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="{{$commission->commission_id}}_third_year_rate" class="layui-input" value="{{$commission->third_year_rate}}" autocomplete="off">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="{{$commission->commission_id}}_fourth_year_rate" class="layui-input" value="{{$commission->fourth_year_rate}}" autocomplete="off">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="{{$commission->commission_id}}_fifth_year_rate" class="layui-input" value="{{$commission->fifth_year_rate}}" autocomplete="off">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif
                                                
                                            </tbody>

                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @if(!isset($commissions) || $commissions->isEmpty())
                    <button type="button" class="layui-btn" onclick="addTable()">新增一行</button>
                    @endif
                </div>
                
                <div class="layui-form-item" style="text-align: center;">
                    <button class="layui-btn" style="width: 100px;" lay-submit="" lay-filter="add">保&emsp;存</button>
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
            //发异步，把数据提交给php
            $.ajax({
                url:  '/product/list',
                data: data.field,
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                success:function(data) {
                    if(data.code == 200){
                        layer.msg(data.message,{icon:1});
                        setTimeout(function () {
                            xadmin.close();
                            xadmin.father_reload();
                        },500);
                    }else{
                        layer.msg(data.message,{icon:2});
                    }
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
        
        function addTable(){
            var trNodes = $("#demo")[0].getElementsByTagName('tr');
            var count = trNodes.length+1;
            
            var tr= '<tr>'+
                        '<td><input type="text" name="'+count+'_payment_period" class="layui-input" value="" autocomplete="off"></td>' +
                        '<td><input type="text" name="'+count+'_first_rate" class="layui-input" value="" autocomplete="off"></td>' +
                        '<td><input type="text" name="'+count+'_second_year_rate" class="layui-input" value="" autocomplete="off">' +
                        '<td><input type="text" name="'+count+'_third_year_rate" class="layui-input" value="" autocomplete="off"></td>' +
                        '<td><input type="text" name="'+count+'_fourth_year_rate" class="layui-input" value="" autocomplete="off"></td>' +
                        '<td><input type="text" name="'+count+'_fifth_year_rate" class="layui-input" value="" autocomplete="off"></td>' +
                    '</tr>';
            $("#demo").append(tr);
            // form.render();

        }
    </script>
    
  </body>

</html>
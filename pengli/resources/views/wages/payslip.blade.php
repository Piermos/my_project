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
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <style>
        button.layui-btn{
            width: 100px;margin-top: 50px;
        }
        .layui-form .layui-form-item{
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">
            <input type="hidden" name="wages_id" value="{{$wages->wages_id}}">
            <div class="layui-form-item">
                <label for="proxy_name" class="layui-form-label">
                    代理人</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$wages->proxy_name}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="organization_name" class="layui-form-label">
                    机构</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$wages->organization_name}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="department_name" class="layui-form-label">
                    部门</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$wages->department_name}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="id_card_number" class="layui-form-label">
                    身份证</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$wages->id_card_number}}" autocomplete="off" class="layui-input" readonly>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="bank_name" class="layui-form-label">
                    开户银行</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$wages->bank_name}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="bank_number" class="layui-form-label">
                    银行卡号</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$wages->bank_number}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="referrer" class="layui-form-label">
                    推荐人</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$wages->referrer}}" autocomplete="off" class="layui-input" readonly>
                </div> 
                <label for="assessment_rank_name" class="layui-form-label">
                    考核职级</label>
                <div class="layui-input-inline">
                    <input type="text" value="{{$wages->assessment_rank_name}}" autocomplete="off" class="layui-input" readonly>
                </div>
                
            </div>
            <div class="layui-form-item">
                <label for="fyc" class="layui-form-label">
                   FYC</label>
                <div class="layui-input-inline">
                    <input type="text" name="fyc" value="{{$wages->fyc}}" autocomplete="off" class="layui-input" readonly>
                </div>
                <label for="sales_incentives" class="layui-form-label">
                   销售奖励</label>
                <div class="layui-input-inline">
                    <input type="text" name="sales_incentives" value="{{$wages->sales_incentives}}" autocomplete="off" class="layui-input">
                </div>
                <label for="persistency_commission" class="layui-form-label">
                   续期佣金</label>
                <div class="layui-input-inline">
                    <input type="text" name="persistency_commission" value="{{$wages->persistency_commission}}" autocomplete="off" class="layui-input">
                </div>
                <label for="recommendation_allowance" class="layui-form-label">
                   推荐奖励</label>
                <div class="layui-input-inline">
                    <input type="text" name="recommendation_allowance" value="{{$wages->recommendation_allowance}}" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="management_allowance" class="layui-form-label">
                   管理津贴</label>
                <div class="layui-input-inline">
                    <input type="text" name="management_allowance" value="{{$wages->management_allowance}}" autocomplete="off" class="layui-input">
                </div>
                <label for="quarterly_back_calculation" class="layui-form-label">
                   季度回算</label>
                <div class="layui-input-inline">
                    <input type="text" name="quarterly_back_calculation" value="{{$wages->quarterly_back_calculation}}" autocomplete="off" class="layui-input">
                </div>
                <label for="independent_scheme" class="layui-form-label">
                   独立方案</label>
                <div class="layui-input-inline">
                    <input type="text" name="independent_scheme" value="{{$wages->independent_scheme}}" autocomplete="off" class="layui-input">
                </div>
                <label for="scheme_reward" class="layui-form-label">
                   方案奖励</label>
                <div class="layui-input-inline">
                    <input type="text" name="scheme_reward" value="{{$wages->scheme_reward}}" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="allowance" class="layui-form-label">
                   津贴</label>
                <div class="layui-input-inline">
                    <input type="text" name="allowance" value="{{$wages->allowance}}" autocomplete="off" class="layui-input">
                </div>
                <label for="group_insurance" class="layui-form-label">
                   团险</label>
                <div class="layui-input-inline">
                    <input type="text" name="group_insurance" value="{{$wages->group_insurance}}" autocomplete="off" class="layui-input">
                </div>
                <label for="deduction_amount" class="layui-form-label">
                   扣款</label>
                <div class="layui-input-inline">
                    <input type="text" name="deduction_amount" value="{{$wages->deduction_amount}}" autocomplete="off" class="layui-input">
                </div>
                <label for="pre_tax_amount" class="layui-form-label">
                   税前金额</label>
                <div class="layui-input-inline">
                    <input type="text" name="pre_tax_amount" value="{{$wages->pre_tax_amount}}" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="individual_income_tax" class="layui-form-label">
                   个人所得税</label>
                <div class="layui-input-inline">
                    <input type="text" name="individual_income_tax" value="{{$wages->individual_income_tax}}" autocomplete="off" class="layui-input">
                </div>
                <label for="after_tax_amount" class="layui-form-label">
                   税后金额</label>
                <div class="layui-input-inline">
                    <input type="text" name="after_tax_amount" value="{{$wages->after_tax_amount}}" autocomplete="off" class="layui-input">
                </div>
                <label for="commission_month" class="layui-form-label">
                   佣金月</label>
                <div class="layui-input-inline">
                    <input type="text" name="commission_month" value="{{$wages->commission_month}}" autocomplete="off" class="layui-input" readonly>
                </div>
            </div>
            <div class="layui-form-item" style="text-align: center;">
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    结算
                </button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/js/base.js"></script>
<script>
    layui.use(['form', 'layer','laydate'], function() {
        $ = layui.jquery;
        var form = layui.form,
            layer = layui.layer;
            laydate = layui.laydate;
        
        //监听提交
        form.on('submit(add)', function(data) {
            
            $.ajax({
                url:  '/wages/payroll',
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
            
            //刷新tab
            return false;       //禁止提交
        });

    });
</script>
</body>

</html>

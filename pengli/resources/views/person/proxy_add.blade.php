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
            <input type="hidden" id="proxy_id" name="proxy_id" value="{{!empty($proxy)?$proxy->proxy_id:''}}" >
            <div class="layui-form-item">
                <label for="job_number" class="layui-form-label">
                    <span class="x-red">*</span>工号
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="job_number" name="job_number" lay-verify="required" value="{{isset($job_number)?$job_number:$proxy->job_number}}"
                     autocomplete="off" class="layui-input">
                </div>
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>姓名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="proxy_name" name="proxy_name" lay-verify="required" value="{{!empty($proxy)?$proxy->proxy_name:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="id_card" class="layui-form-label">
                    <span class="x-red">*</span>身份证号码
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="id_card" name="id_card_number" lay-verify="required|identity" value="{{!empty($proxy)?$proxy->id_card_number:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label class="layui-form-label"><span class="x-red">*</span>性别</label>
                <div class="layui-input-inline">
                    <input type="radio" id="gender0" name="gender" value="1" lay-skin="primary" title="男" {{empty($proxy)||$proxy->gender==1?'checked':''}}>
                    <input type="radio" id="gender1" name="gender" value="2" lay-skin="primary" title="女" {{!empty($proxy)&&$proxy->gender==2?'checked':''}}>
                </div>
            </div>
            <div class="layui-form-item">
                
                <label for="mobile" class="layui-form-label">
                    <span class="x-red">*</span>手机
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="mobile" name="mobile" lay-verify="required|phone" value="{{!empty($proxy)?$proxy->mobile:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="education" class="layui-form-label">
                    <span class="x-red">*</span>学历</label>
                <div class="layui-input-inline">
                    <select id="education" name="education_id" class="valid" lay-verify="required" lay-search>
                        <option value="">请选择</option>
                        @foreach($educations as $education)
                        <option value="{{$education->education_id}}" {{!empty($proxy)&&$proxy->education_id==$education->education_id?'selected':''}}>{{$education->education_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="bank_name" class="layui-form-label">
                    <span class="x-red">*</span>开户行
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="bank_name" name="bank_name" lay-verify="required" value="{{!empty($proxy)?$proxy->bank_name:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="bank_number" class="layui-form-label">
                    <span class="x-red">*</span>银行卡号
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="bank_number" name="bank_number" lay-verify="required" value="{{!empty($proxy)?$proxy->bank_number:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="rank_id" class="layui-form-label">
                    <span class="x-red">*</span>职级</label>
                <div class="layui-input-inline">
                    <select id="rank_id" name="rank_id" class="valid" lay-verify="required" lay-search>
                        <option value="">请选择</option>
                        @foreach($ranks as $rank)
                        <option value="{{$rank->rank_id}}" {{!empty($proxy)&&$proxy->rank_id==$rank->rank_id?'selected':''}}>{{$rank->rank_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="referrer" class="layui-form-label">
                    推荐人
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="referrer" name="referrer" value="{{!empty($proxy)?$proxy->referrer:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="mechanism_id" class="layui-form-label">
                    <span class="x-red">*</span>机构</label>
                <div class="layui-input-inline">
                    <select id="organization_id" name="organization_id" class="valid" lay-filter="organization_id" lay-verify="required" lay-search>
                        <option value="">请选择</option>
                        @foreach($organizations as $organization)
                        <option value="{{$organization->organization_id}}" {{!empty($proxy)&&$proxy->organization_id==$organization->organization_id?'selected':''}}>{{$organization->organization_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="department_id" class="layui-form-label">
                    <span class="x-red">*</span>部门</label>
                <div class="layui-input-inline">
                    <select id="department" name="department_id" class="valid" lay-verify="required" lay-search>
                        <option value="">请选择</option>
                        @if(!empty($departments))
                        @foreach($departments as $department)
                        <option value="{{$department->department_id}}" {{!empty($proxy)&&$proxy->department_id==$department->department_id?'selected':''}}>{{$department->department_name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="work_number" class="layui-form-label">
                    <span class="x-red">*</span>展业证号
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="work_number" name="work_number" lay-verify="required" value="{{!empty($proxy)?$proxy->work_number:''}}" 
                           autocomplete="off" class="layui-input">
                </div>

                <label class="layui-form-label">
                    <span class="x-red">*</span>工作性质
                </label>
                <div class="layui-input-inline">
                    <input type="radio" id="work_type1" name="work_type" value="1" lay-skin="primary" title="全职" {{empty($proxy)||$proxy->work_type==1?'checked':''}}>
                    <input type="radio" id="work_type2" name="work_type" value="2" lay-skin="primary" title="兼职" {{!empty($proxy)&&$proxy->work_type==2?'checked':''}}>
                </div>
                <label for="entry_date" class="layui-form-label">
                    <span class="x-red">*</span>入职日期
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="entry_date" name="entry_date" lay-verify="required" value="{{!empty($proxy)?$proxy->entry_date:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label class="layui-form-label">
                    <span class="x-red">*</span>状态
                </label>
                <div class="layui-input-inline">
                    <input type="radio" id="job_type1" name="status" value="1" lay-skin="primary" title="在职" {{empty($proxy)||$proxy->status==1?'checked':''}}>
                    <input type="radio" id="job_type2" name="status" value="2" lay-skin="primary" title="离职" {{!empty($proxy)&&$proxy->status==2?'checked':''}}>
                </div>
                
            </div>
            <div class="layui-form-item">
                @if(!empty($proxy))
                <label for="sunshine_number" class="layui-form-label">
                    阳光工号
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="sunshine_number" name="sunshine_number" value="{{!empty($proxy)?$proxy->sunshine_number:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="ruitai_number" class="layui-form-label">
                    瑞泰工号
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="ruitai_number" name="ruitai_number" value="{{!empty($proxy)?$proxy->ruitai_number:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                <label for="life_number" class="layui-form-label">
                    生命工号
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="life_number" name="life_number" value="{{!empty($proxy)?$proxy->life_number:''}}" 
                           autocomplete="off" class="layui-input">
                </div>
                @endif
            </div>
            <div class="layui-form-item" style="text-align: center;">
<!--                <label for="L_repass" class="layui-form-label">-->
<!--                </label>-->
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    增加
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
        //执行一个laydate实例
        laydate.render({
            elem: '#entry_date', //指定元素
            value: new Date(),
        });


        //根据身份证号码选择性别
        $("#id_card").blur(function(){
            var id_card = $("#id_card").val();
            if(id_card.length != 18){
                return false;
            }
            var curValue = id_card.substr(16,1);
            var gender = curValue%2 == 0?2:1;

            var radio = document.getElementsByName("gender");
            for (var i = 0; i < radio.length; i++) {
                if(gender == radio[i].value){
                    $(radio[i]).next().click();
                }
            }
        });

        //根据推荐人选择机构与部门
        $('#referrer').blur(function () {
            var referrer = $("#referrer").val();
            $.ajax({
                url:  '/person/proxy/add/retrieval',
                data: {
                    'referrer': referrer,
                },
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                success:function(data) {
                    if(data.code == 200){
                        $('#organization_id').val(data.data['organization_id']);    //选中归属机构
                        $('#department').empty();   //清空department
                        var temp_node = document.createElement('option');
                        temp_node.value = '';
                        temp_node.innerText = '请选择部门';
                        $('#department').append(temp_node);
                        var departments = data.data.departments;
                        for(var i=0;i<departments.length;i++){
                            var department_node = document.createElement('option');
                            department_node.value = departments[i]['department_id'];
                            department_node.innerText = departments[i]['department_name'];
                            $('#department').append(department_node);
                        }
                        $('#department').val(data.data['department_id']);        //
                        form.render();
                        if(data.message){
                            layer.msg(data.message);
                        }
                    }else{
                        
                    }
                },
                error:function(data){
                    layer.msg('服务器连接失败');
                }
            });
        });

        //监控组织更改
        form.on('select(organization_id)', function(data){
            var organization_id = data.value;
            $('#department').empty();   //清空department
            var temp_node = document.createElement('option');
            temp_node.value = '';
            temp_node.innerText = '请选择';
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

        //监听提交
        form.on('submit(add)', function(data) {
            $.ajax({
                url:  '/person/proxy/list',
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

var domain = 'http://www.pengli.com';
var baidu = 'https://www.baidu.com';

//获取url指定参数值
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r == null)
        return null;

    return unescape(r[2]);
}

//菜单显示
function showAuth(auth_list) {
    var menuNode = $('#nav');
    for(var i = 0;i < auth_list.length;i++){
        var auth_name = auth_list[i]['auth_name'];
        var auth_son = auth_list[i]['son'];
        var node = document.createElement('li');
        var str = '<a href="javascript:;">' +
            '<i class="iconfont left-nav-li" lay-tips="' + auth_name + '">&#xe6b8;</i>' +
            '<cite>' + auth_name + '</cite>' +
            '<i class="iconfont nav_right">&#xe697;</i>' +
            '</a>';
        node.innerHTML = str;
        if(auth_son.length > 0){
            var son_ul_node = document.createElement('ul');
            son_ul_node.className = 'sub-menu';

            for(var j = 0;j < auth_son.length;j++){
                var son_auth_name = auth_son[j]['auth_name'];
                var son_auth_path = auth_son[j]['path'];
                var sun_li_node = document.createElement('li');
                var son_str = '<a onclick="xadmin.add_tab(\'' + son_auth_name + '\',\''+son_auth_path+'\')" target="">' +
                    '<i class="iconfont">&#xe6a7;</i>' +
                    '<cite>' + son_auth_name + '</cite>' +
                    '</a>';
                sun_li_node.innerHTML = son_str;
                son_ul_node.append(sun_li_node);
            }
            node.append(son_ul_node);
        }
        menuNode.append(node);
    }

}

/*代理人列表显示*/
function showAgentList(data) {
    var parent = $('#list');
    $('#list')[0].innerHTML = '';
    if(data.length > 0){
        $('#tip')[0].style.display = 'none';
        for(var i = 0;i < data.length;i++){
            var node = document.createElement('tr');
            var str = '<td>' + data[i]['job_number'] + '</td>' +
                '<td>' + data[i]['name'] + '</td>' +
                '<td>' + data[i]['mobile'] + '</td>' +
                '<td>' + data[i]['rank'] + '</td>' +
                '<td>' + data[i]['referee_person'] + '</td>' +
                '<td>' + data[i]['department'] + '</td>' +
                '<td>' + data[i]['mechanism'] + '</td>' +
                '<td>' + data[i]['entry_date'] + '</td>' +
                // '<td><a onclick="xadmin.open(\'编辑\',\'insurance-job-show.html?id='+data[i]['id']+'\')" href="javascript:;">查看</a></td>' +
                '<td>' + data[i]['status'] + '</td>' +
                '<td class="td-manage">' +
                '<a title="编辑" onclick="xadmin.open(\'编辑\',\'agent-add.html?id='+data[i]['id']+'\')" href="javascript:;">编辑</a>' +
                // '<a onclick="xadmin.open(\'离职\',\'member-password.html\',600,400)" title="离职" href="javascript:;"><i class="layui-icon">&#xe631;</i></a>' +
                // '<a title="删除" onclick="member_del(this,\'要删除的id\')" href="javascript:;"><i class="layui-icon">&#xe640;</i></a>' +
                '</td>';
            node.innerHTML = str;
            parent.append(node);
        }
    }else{
        $('#tip')[0].style.display = '';
    }
}

/*部门列表显示*/
function showDepartmentList(data) {
    var parent = $('#list');
    $('#list')[0].innerHTML = '';
    if(data.length > 0){
        $('#tip')[0].style.display = 'none';
        for(var i = 0;i < data.length;i++){
            var node = document.createElement('tr');
            var str = '<td>' + data[i]['name'] + '</td>' +
                '<td>' + data[i]['level'] + '</td>' +
                '<td>' + data[i]['mechanism'] + '</td>' +
                '<td>' + data[i]['status'] + '</td>' +
                '<td class="td-manage">' +
                '<a title="编辑"  onclick="xadmin.open(\'编辑\',\'organize-add.html?type=department&id='+data[i]['id']+'\',600,400)" href="javascript:;">编辑</a>' +
                // '<a title="删除" onclick="member_del(this,\'要删除的id\')" href="javascript:;"><i class="layui-icon">&#xe640;</i></a>' +
                '</td>';
            node.innerHTML = str;
            parent.append(node);
        }
        $('#page')[0].style.display = '';
    }else{
        $('#tip')[0].style.display = '';
        $('#page')[0].style.display = 'none';

    }
}
/*机构列表显示*/
function showMechanismList(data) {
    var parent = $('#list');
    $('#list')[0].innerHTML = '';
    if(data.length > 0){
        $('#tip')[0].style.display = 'none';
        for(var i = 0;i < data.length;i++){
            var node = document.createElement('tr');
            var str = '<td>' + data[i]['name'] + '</td>' +
                '<td>' + data[i]['level'] + '</td>' +
                '<td>' + data[i]['create_time'] + '</td>' +
                '<td class="td-manage">' +
                '<a title="编辑"  onclick="xadmin.open(\'编辑\',\'organize-add.html?type=mechanism&id='+data[i]['id']+'\',600,400)" href="javascript:;">编辑</a>' +
                // '<a title="删除" onclick="member_del(this,\'要删除的id\')" href="javascript:;"><i class="layui-icon">&#xe640;</i></a>' +
                '</td>';
            node.innerHTML = str;
            parent.append(node);
        }
        $('#page')[0].style.display = '';
    }else{
        $('#tip')[0].style.display = '';
        $('#page')[0].style.display = 'none';
    }
}

/*重置查询条件*/
function reset(){
    $('form').reset();
}


/**
 * 下载模板
 */
function download_template(name) {
    $('#download_demo').attr('disabled','disabled');
    var index = layer.msg('正在下载中....');
    var temp_form = $("<form>");
    temp_form.attr("style","display:none");
    temp_form.attr("target","");
    temp_form.attr("method","post");
    temp_form.attr("action",domain + '/download/excel');  // + "T_academic_essay/DownloadZipFile.do"
    var input1 = $("<input>");
    input1.attr("type","hidden");
    input1.attr("name","table_name");
    input1.attr("value",name);
    $("body").append(temp_form);
    temp_form.append(input1);
    temp_form.submit();
    temp_form.remove();
}
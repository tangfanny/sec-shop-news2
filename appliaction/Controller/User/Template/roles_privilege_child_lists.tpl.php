<?php include $this->admin_tpl("header"); ?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH;?>EasyUI/themes/haidaoblue/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH;?>EasyUI/themes/icon.css">
<script type="text/javascript" src="<?php echo JS_PATH;?>EasyUI/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>EasyUI/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>EasyUI/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>EasyUI/hd_default_config.js"></script>
<div class="content">
    <div class="site">
        <a href="#">用户设置</a> > 角色权限管理
    </div>
    <span class="line_white"></span>
    <div class="goods mt10">
        <div class="search_ind">
            <input type="hidden" name="m" value="<?php echo GROUP_NAME ?>">
            <input type="hidden" name="c" value="<?php echo MODULE_NAME ?>">
            <input type="hidden" name="a" value="<?php echo ACTION_NAME ?>">
            <span style="margin-right: 10px;">权限搜索 </span>
            <input id="keyword" class="easyui-textbox" name="keyword" style="width:280px;height: 26px;" prompt="输入权限名称/控制器名/方法名进行查找">
            <a id="search" href="#" class="easyui-linkbutton" style="height: 26px;padding-right: 10px;">查询</a>
        </div>
        <dl class="mt10">
            <dt><p>
                <a href="<?php echo  U('lists?type=1')?>"  <?php if ($type == 1) { echo "class='hover'"; }; ?>>主权限</a>
                <a href="<?php echo  U('lists?type=2')?>" <?php if ($type == 2) { echo "class='hover'";}; ?>>子权限</a>
            </p>
            </dt>
        </dl>
        <div class="login mt10" style="border: none;border-right: 1px solid #e6e6e6;">
            <table id="roles_privilege_lists" style="width:100%;"></table>
        </div>

        <div class="clear"></div>
        <?php include $this->admin_tpl('copyright') ?>
    </div>
    <script type="text/javascript">
        var dom = $('#roles_privilege_lists');
        var pageSize = <?php echo PAGE_SIZE?>;
        var dataurl = '<?php echo U('lists')?>';
        var addurl = '<?php echo U('childUpdate')?>';
        var editurl = '<?php echo U('childUpdate')?>';
        var delurl = '<?php echo U('delete')?>';
        var accessurl = '<?php echo U('access')?>';
        var delroles = '<?php echo U('delroles')?>';
        var keyword = '<?php echo $keyword; ?>';
        var type = <?php echo $type?>;
        $(function(){
            dom.datagrid({
                url:dataurl,
                striped:true,
                width:'100%',
                checkOnSelect:true,
                singleSelect:true,
                fitColumns:true,
                queryParams:{
                    keyword:keyword,
                    type:type
                },
                pagination:true,
                toolbar:[
                    {
                        id:'addrow',
                        text:'添加',
                        iconCls:'icon-add',
                        handler: function () {
                            window.location.href = addurl+ '&type=' + '<?php echo $type; ?> ';
                        }
                    },'-'
                ],
                pageSize:pageSize,
                pageList: [pageSize,pageSize*2,pageSize*4],//可以设置每页记录条数的列表
                columns:[[
                    {field:'title',title:'权限名称',width:'15%',align:'center'},
                    {field:'host',title:'Host',width:'15%',align:'center'},
                    {field:'auth_type',title:'可访问的认证状态',width:'20%',align:'center'},
                    {field:'control_function',title:'控制器名/方法名',width:'15%',align:'center'},
                    {field:'status',title:'是否启用 ',width:'15%',align:'center'},
                    {field:'none',title:'操作',width:'21%',halign:'center',align:'center',
                        formatter:function(value,row,index){
                            var authhtml = '<a href="'+accessurl+'&group_id='+row.id+'&title='+row.title+'" >访问授权</a>&nbsp;&nbsp;&nbsp;&nbsp;';
                            // var banhtml = '<a href="javascript:void(0)" >禁止</a>&nbsp;&nbsp;&nbsp;&nbsp;';
                            var edithtml = '<a href="'+editurl+'&id='+row.id+'&type='+row.type+'" >编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;';
                            var delhtml = '<a href="javascript:void(0)" onclick="chang_status(\'deletegroup\','+row.id+')">删除</a>';
                            return authhtml  + edithtml + delhtml;
                        }
                    }
                ]]
            });
//		 // 回车查询
            $('#keyword').textbox('textbox').bind('keydown',function (e) {
                if (e.keyCode == 13) {
                    $('#search').trigger('click');
                }
            });
            // 增加查询参数，重新加载表格
            $('#search').bind('click',function (){
                var queryParams = dom.datagrid('options').queryParams;
                //查询参数直接添加在queryParams中
                queryParams.keyword = $("#keyword").val();
                dom.datagrid('options').queryParams = queryParams;
                dom.datagrid('reload');
            })
        });
        //状态
        function chang_status(method,id){
            $.messager.confirm('确认','您确认想要执行操作吗？',function(r){
                if (r){
                    $.getJSON(delroles,
                        {"method":method,"id":id},
                        function(data){
                            if(1 == data.status){
                                dom.datagrid("reload");  //重新加载
                            }else{
                                $.messager.alert('提示',data.info);
                            }
                        })
                }
            });
        }
    </script>
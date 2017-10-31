<?php include $this->admin_tpl("header"); ?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH;?>EasyUI/themes/haidaoblue/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH;?>EasyUI/themes/icon.css">
<script type="text/javascript" src="<?php echo JS_PATH;?>EasyUI/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>EasyUI/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>EasyUI/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>EasyUI/hd_default_config.js"></script>
<div class="content">
	<div class="site">
            <a href="#">用户设置</a> > 权限管理
	</div>
	<span class="line_white"></span>
	<div class="install mt10">
		<div class="login mt10" style="border: none;">
			<table id="auth_manage" style="width:100%"></table>
			<div class="clear"></div>
		</div>
		<?php include $this->admin_tpl("copyright"); ?>
		<div class="clear"></div>
	</div>
<script type="text/javascript">
	var dom = $('#auth_manage');
	var pageSize = <?php echo PAGE_SIZE?>;
	var dataurl = '<?php echo U('lists')?>';
	var addurl = '<?php echo U('creategropup')?>';
	var editurl = '<?php echo U('creategropup')?>';
	var delurl = '<?php echo U('delete')?>';
	var accessurl = '<?php echo U('access')?>';
	var delroles = '<?php echo U('delroles')?>';
	$(function(){
		dom.datagrid({
			url:dataurl,
			striped:true,
			width:'100%',
			checkOnSelect:true,
			singleSelect:true,
			fitColumns:true,
			pagination:true,
			toolbar:[
				{
					id:'addrow',
					text:'添加',
					iconCls:'icon-add',
					handler: function () {
                       window.location.href = addurl;
                     }
				},'-'
			],
			pageSize:pageSize,
			pageList: [pageSize,pageSize*2,pageSize*4],//可以设置每页记录条数的列表
			columns:[[
				{field:'sort',title:'排序',width:'15%',align:'center'},
				{field:'code',title:'权限组代号',width:'20%',align:'center'},
				{field:'title',title:'用户组',width:'20%',align:'center'},
				{field:'status',title:'是否启用',width:'15%',align:'center'},
				{field:'none',title:'操作',width:'30%',halign:'center',align:'center',
					formatter:function(value,row,index){
						var authhtml = '<a href="'+accessurl+'&group_id='+row.id+'&title='+row.title+'" >访问授权</a>&nbsp;&nbsp;&nbsp;&nbsp;';
						// var banhtml = '<a href="javascript:void(0)" >禁止</a>&nbsp;&nbsp;&nbsp;&nbsp;';
						var edithtml = '<a href="'+editurl+'&id='+row.id+'" >编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;';
						var delhtml = '<a href="javascript:void(0)" onclick="chang_status(\'deletegroup\','+row.id+')">删除</a>';
						return authhtml  + edithtml + delhtml;
					}
				}
			]]
		});
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
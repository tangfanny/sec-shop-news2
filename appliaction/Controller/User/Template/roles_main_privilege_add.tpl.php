<?php include $this->admin_tpl('header'); ?>
<div class="content">
    <div class="site">
        <a href="#">站点设置</a> > 权限管理
    </div>
    <span class="line_white"></span>
    <div class="install mt10">
        <dl>
            <dt><a href="<?php echo U('RolesPrivilege/lists'); ?>">用户组列表</a><a href="javascript:void(0)" class="hover">
                <?php echo $meta_title; ?></a></dt>

            <dd>
                <div class="install mt10">
                    <div class="install mt10">
                        <dl>
                            <form action="<?php echo U('RolesPrivilege/update'); ?>" class="addform" method="post">
                                <dd>
                                    <ul class="web">
                                        <li>
                                            <strong>权限名称：</strong>
                                            <input type="text" class="text_input" name="title" placeholder='' datatype="*" value="<?php echo $info['title']; ?>" /><span class="Validform_checktip ">请填写权限名称
                                            </span>
                                        </li>
                                        <li>
                                            <strong>Host：</strong>
                                            <input type="text" class="text_input" name="host" placeholder='' datatype="*" value="<?php echo $info['host']; ?>" /><span class="Validform_checktip ">请填写Host
                                            </span>
                                        </li>
                                        <li>
                                            <strong>可访问的认证状态：</strong>
                                            <input type="text" class="text_input" name="auth_type" placeholder='' datatype="*" value="<?php echo $info['auth_type']; ?>" /><span class="Validform_checktip ">请填写可访问的认证状态
                                            </span>
                                        </li>
                                        <li>
                                            <strong>控制器名/方法名：</strong>
                                             <input type="text" class="text_input" name="control_function" placeholder='' datatype="*" value="<?php echo $info['control_function']; ?>" /><span class="Validform_checktip ">请填写控制器名/方法名
                                            </span>
                                        </li>
                                        <li>
                                            <strong>是否启用：</strong>
                                            <input type="radio" name="status" value="1" <?php if ($info['status'] == 1): ?> checked="checked"<?php endif ?> />是
                                            <input type="radio" name="status" value="0" <?php if ($info['status'] == 0): ?> checked="checked"<?php endif ?> />否
                                        </li>
                                        <li>
                                            <input type="hidden" class="text_input" name="type" placeholder='' datatype="*" value="<?php echo $type; ?>" />
                                        </li>
                                    </ul>
                                    <div class="submit">
                                          <input type="button" class="button_search "  id="btn_sub" value="提交" />
										  <a href="<?php echo U('lists')?>">返回</a>
                                    </div>
                                </dd>
                                <input name="id" type='hidden' value='<?php echo $info["id"]; ?>'>
                            </form>
                        </dl>
                    </div>
				</div>
            </dd>
        </dl>
    </div>
    <script>
        $(function() {
            var demo = $(".addform").Validform({
                btnSubmit: "#btn_sub",
                btnReset: ".btn_reset",
                tiptype: function(){},
                label: ".label",
                showAllError: false,
                ajaxPost: true,
                callback: function(data) {
                    $("#Validform_msg").hide();
                    if (data.status == "n") {
                        art.dialog({width: 320, time: 5, title: '温馨提示(5秒后关闭)', content: data.info, ok: true});
                    }
                    if (data.status == "y") {
                        window.location.href = data.url;
                    }
                }
            });
        });
    </script>
	<?php include $this->admin_tpl('copyright'); ?>
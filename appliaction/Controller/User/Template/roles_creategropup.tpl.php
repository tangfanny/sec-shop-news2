<?php include $this->admin_tpl('header'); ?>
<div class="content">
    <div class="site">
        <a href="#">站点设置</a> > 权限管理
    </div>
    <span class="line_white"></span>
    <div class="install mt10">
        <dl>
            <dt><a href="<?php echo U('RolesGroup/lists'); ?>">用户组列表</a><a href="javascript:void(0)" class="hover">
                <?php echo $meta_title; ?></a></dt>
                
            <dd>
                <div class="install mt10">
                    <div class="install mt10">
                        <dl>
                            <form action="<?php echo U('RolesGroup/creategropup'); ?>" class="addform" method="post">
                                <dd>
                                    <ul class="web">
                                        <li>
                                            <strong>排序：</strong>
                                            <input type="text" class="text_input" name="sort" placeholder='' datatype="*" value="<?php echo $info['sort']; ?>" /><span class="Validform_checktip ">请填写序号
                                            </span>
                                        </li>
                                        <li>
                                            <strong>权限组代号：</strong>
                                            <input type="text" class="text_input" name="code" placeholder='' datatype="*" value="<?php echo $info['code']; ?>" /><span class="Validform_checktip ">请填写权限组代号
                                            </span>
                                        </li>
                                        <li>
                                            <strong>用户组：</strong>
                                            <input type="text" class="text_input" name="title" placeholder='' datatype="*" value="<?php echo $info['title']; ?>" /><span class="Validform_checktip ">请填写用户组名
                                            </span>
                                        </li>
                                        <li>
                                            <strong>描述：</strong>
                                            <textarea name="my_explain" rows="4" cols="20" placeholder=''  style="margin-right: 50px;"><?php echo $info['my_explain']; ?></textarea>
                                            <span class="Validform_checktip" style="margin-left:4px;">请填写用户组描述</span>
                                        </li>
                                        <li>
                                            <strong>是否启用：</strong>
                                            <input type="radio" name="status" value="1" <?php if ($info['status'] == 1): ?> checked="checked"<?php endif ?> />是 
                                            <input type="radio" name="status" value="0" <?php if ($info['status'] == 0): ?> checked="checked"<?php endif ?> />否
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
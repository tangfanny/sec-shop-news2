<?php
class RolesPrivilegeController extends AdminBaseController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->db = model('UrRoleGroup');
        $this->purviewmaindb = model('UrPurviewMain');
        $this->purviewchildrendb = model('UrPurviewChildren');
        $this->urrolepurviewdb = model('UrRolePurview');
        $this->urrolechildrenpurview = model('UrRoleChildrenPurview');

    }
    public function lists(){
        $type = isset($_GET['type']) ? $_GET['type'] : 1; //获取类型
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';//检索字段主要可以搜索产品名和品牌名
        if(IS_POST){
            $sqlmap = array();
            //排序
            $_order=isset($_POST['order']) ? ($_POST['order']) : NULL;
            $_sort=isset($_POST['sort']) ? ($_POST['sort']) : NULL;
            if($_order && $_sort){
                $order[$_sort] = $_order;
            }else{
                $order['id'] = 'ASC';
            }
            if (isset($keyword) && $keyword) {
                $keyword = $_GET['keyword'];
                $sqlmap['title|control_function'] = array("LIKE", "%" . $keyword . "%");
            }
            $pagenum=isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rowsnum=isset($_POST['rows']) && (int)($_POST['rows']) != 0 ? intval($_POST['rows']) : PAGE_SIZE;
            switch ($type) {
                /* 查询天 */
                case '1':
                    $data['total'] = $this->purviewmaindb->where($sqlmap)->count();	//计算总数
                    $data['rows'] = $this->purviewmaindb->where($sqlmap)->limit(($pagenum-1)*$rowsnum.','.$rowsnum)->order($order)->select();
                    break;
                case '2': //周
                    $data['total'] = $this->purviewchildrendb->where($sqlmap)->count();	//计算总数
                    $data['rows'] = $this->purviewchildrendb->where($sqlmap)->limit(($pagenum-1)*$rowsnum.','.$rowsnum)->order($order)->select();
                    break;
            }
            foreach ($data['rows'] as $key => $value) {
                if ($data['rows'][$key]['status'] == 1) {
                    $data['rows'][$key]['status'] = "是";
                }elseif($data['rows'][$key]['status'] == 0){
                    $data['rows'][$key]['status'] = "否";
                }
                if($type==1){
                    $data['rows'][$key]['type'] = "1";
                }elseif($type==2){
                    $data['rows'][$key]['type'] = "2";
                }
            }
            if (!$data['rows']) $data['rows']=array();
            echo json_encode($data);
        }else{
            if($type==1){
                include $this->admin_tpl('roles_privilege_main_lists');
            }elseif($type==2){
                include $this->admin_tpl('roles_privilege_child_lists');
            }
        }
    }

    //子权限添加修改角色权限
    public function childUpdate(){
        $type = $_GET['type'];
        $id = $_GET['id'];
        if (IS_POST && $type==2) {
                $pid = explode(',',$_POST['pid']);
                $_POST['level'] = $pid[1];
            //默认数据
            if($this->purviewchildrendb->create()){
                if (empty($_GET['id'])){
                    $_POST['create_time']   =  time();
                    $_POST['create_user']   =  $_SESSION['ADMIN_ID'];
                    $this->purviewchildrendb->add($_POST);
//                    echo $this->purviewchildrendb->_sql();die;
                }else{
                    $_POST['update_time']   =  time();
                    $_POST['update_user']   =  $_SESSION['ADMIN_ID'];
                    $where['id'] = array('eq',$id);
//                    var_dump($_POST);die;
                    $this->purviewchildrendb->where($where)->save($_POST);
//                    echo $this->purviewchildrendb->_sql();die;
                }
                $arr["status"] = "y";
                $arr["info"] = "ok";
                $arr["url"] = U('RolesPrivilege/lists');
                die(json_encode($arr));
            }  else {
                $arr["status"] = "n";
                $arr["info"] = $this->purviewchildrendb->getError();
                die(json_encode($arr));
            }
        }else{
            $validform = '';
            if (empty($_GET['id'])){
                $meta_title = '新增角色子权限';
                $info = $this->purviewchildrendb->getTree();
                $info = $this->purivewStr($info);
                include $this->admin_tpl('roles_child_privilege_add');
            }else{
                $meta_title = '编辑角色子权限';
                $info = $this->purviewchildrendb->getTree();
                $data = $this->purviewchildrendb->where('id=' . $_GET['id'])->find();
                $pid = $data['pid'];
                $info = $this->purivewStr($info,$pid);
                include $this->admin_tpl('roles_child_privilege_edit');
            }

        }
    }
    /**
     *	 主权限添加修改角色权限
     *
     */
    public function update(){
        $type = $_GET['type'];
        if (IS_POST && $type==1) {
                //默认数据
                if($this->purviewmaindb->create()){
                    if (empty($_GET['id'])){
                        $_POST['create_time']   =  time();
                        $_POST['create_user']   =  $_SESSION['ADMIN_ID'];
                        $this->purviewmaindb->add($_POST);
                    }else{
                        $_POST['update_time']   =  time();
                        $_POST['update_user']   =  $_SESSION['ADMIN_ID'];
                        $this->purviewmaindb->save($_POST);
                    }
                    $arr["status"] = "y";
                    $arr["info"] = "ok";
                    $arr["url"] = U('RolesPrivilege/lists');
                    die(json_encode($arr));
                }  else {
                    $arr["status"] = "n";
                    $arr["info"] = $this->purviewmaindb->getError();
                    die(json_encode($arr));
                }
            }else{
                $validform = '';
                if (empty($_GET['id'])){
                    $meta_title = '新增角色权限';
                }else{
                    $meta_title = '编辑角色权限';
                    $info = $this->purviewmaindb->where('id=' . $_GET['id'])->find();
                }
                    include $this->admin_tpl('roles_main_privilege_add');
            }

    }

    public function purivewStr($arr,$pid) {
        $return_str = '';
        if(!is_array($arr) || count($arr)<=0) {
            return $return_str;
        }
        foreach($arr as $key => $value) {
            if(!isset($value['level'])) {
                $value['level'] = 0;
            } else {
                $value['level'] += 1;
            }
//            var_dump($value['level']);die;

            if($value['id'] == $pid) {
                $select = 'selected="selected"';
            }else{
                $select = '';
            }
            $return_str .="<option {$select} value='".$value['id'].",".$value['level']."'>".str_repeat('&nbsp;',5*($value['level']+1)).$value['title']."</option>";
            if(is_array($value['children']) && count($value['children']) > 0) {
                $return_str .= $this->purivewStr($value['children'],$pid);
            }
        }
        return $return_str;
    }

    public function access(){
        include $this->admin_tpl('roles_main_privilege_add');
    }
}

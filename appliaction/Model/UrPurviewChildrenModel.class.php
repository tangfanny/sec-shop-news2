<?php
class UrPurviewChildrenModel extends MysqlModel {
    public function getData(){
        $data = $this->alias('a')
            ->field('a.id,a.pid,a.title,a.host,a.level,a.control_function,a.auth_type,a.my_explain,a.status,a.create_time,a.create_user,a.update_time,a.update_user,m.id as upmid,m.title as upmtitle,m.host as upmhost')
            ->join('t_ur_purview_main as m on a.pid = m.id')
            // ->where($where)
            ->select();
        return $data;
    }

    public function getIds(){
        return $this->field('id,pid,title')->select();
    }
    //权限组 主权限 对应 子权限 信息
    public function getChildrenPurview($pid,$level=0){
        $info = $this->field('id,pid,level,title,host,control_function,auth_type')
            ->where(array('status'=>array('eq','1'),'pid'=>$pid,'level'=>$level))
            ->select();
//        echo $this->_sql();die;
        return $info;
    }


    //递归获取 子权限  rgid 权限组id pid 父权限id
    protected function purviewFunction($pid,$level=0){
        $purview = array();
        $ChildrenPurviews = $this->getChildrenPurview($pid,$level);
        if(!is_array($ChildrenPurviews) || count($ChildrenPurviews) <= 0) {
            return $purview;
        }
        $purview = $ChildrenPurviews;
        foreach($ChildrenPurviews as $key => $value) {
            $level = $value['level']+1;
            $ChildrenPurview = $this->purviewFunction($value['id'],$level);
//            echo $this->_sql();die;
            if(is_array($ChildrenPurview) && count($ChildrenPurview) > 0) {
                $purview[$key]['children'] = $ChildrenPurview;
            }
        }
        return $purview;
    }

    //权限组 所有权限 rgid 角色权限组id
    public function getTree(){
        $arr = array();
        $purview = $arr;
        $pm = model('UrPurviewMain');
        $MainPurview = $pm->getMainPurview();
        if(!is_array($MainPurview) || count($MainPurview) <= 0) {
            return $purview;
        }
        $purview = $MainPurview;
        foreach($MainPurview as $key => $value) {
            $ChildrenPurview = $this->purviewFunction($value['id'],0);
            if(is_array($ChildrenPurview) && count($ChildrenPurview) > 0) {
                $purview[$key]['children'] = $ChildrenPurview;
            }
        }
        return $purview;
    }


}


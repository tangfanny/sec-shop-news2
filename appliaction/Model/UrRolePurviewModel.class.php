<?php
class UrRolePurviewModel extends MysqlModel {

    //角色 主权限 详情
    public function getGroupMainPurview($rgid){
        $purview = $this->alias('a')
            ->field('a.id as mid,a.rgid,a.pmid,b.title,b.host,b.control_function,b.auth_type')
            ->join('t_ur_purview_main as b on a.pmid=b.id')
            ->where(array('b.status'=>array('eq','1'),'a.rgid'=>$rgid))
            ->select();
//        echo $this->_sql();die('hah');
        return $purview;
    }

}


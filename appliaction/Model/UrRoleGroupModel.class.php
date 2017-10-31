<?php
class UrRoleGroupModel extends MysqlModel {
    public function getGroupLists($group_id)
    {
        $data = $this->alias('a')
            ->field('a.id,a.code,a.title,a.sort,a.create_time,a.create_user,a.update_time,a.update_user,upm.id,upm.rgid,upm.pmid')
            ->join('t_ur_role_purview as upm on a.id = upm.rgid')
            ->where(array('a.status'=>array('egt','0'),'a.id'=>$group_id) )
            ->select();
        return $data;
    }

}

<?php
class UrPurviewMainModel extends MysqlModel {

    public function getMainPurview(){
        $purview = $this->field('id,title,host,control_function,auth_type')
            ->where(array('status'=>array('eq','1')))
            ->select();
        return $purview;
    }


}


<?php
/**
 * The AJAX call member information
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */

class member_cardControl extends MircroShopControl{
    public function mcard_infoOp(){
        $uid    = intval($_GET['uid']);
        if($uid <= 0) {
            echo 'false';exit;
        }
        $model_micro_member_info = Model('micro_member_info');
        $micro_member_info = $model_micro_member_info->getOneById($uid);
        if(empty($micro_member_info)){
            echo 'false';exit;
        }
        echo json_encode($micro_member_info);exit;
    }
}

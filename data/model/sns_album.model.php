<?php
/**
 * 买家相册模型
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class sns_albumModel extends Model {

    public function __construct(){
        parent::__construct('sns_albumpic');
    }

    public function getSnsAlbumClassDefault($member_id) {
        if(empty($member_id)) {
            return null;
        }

        $condition = array();
        $condition['member_id'] = $member_id;
        $condition['is_default'] = 1;
        $info = $this->table('sns_albumclass')->where($condition)->find();

        if(!empty($info)) {
            return $info['ac_id'];
        } else {
            return null;
        }
    }
}

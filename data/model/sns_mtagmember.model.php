<?php
/**
 * 标签会员
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');
class sns_mtagmemberModel extends Model {

    public function __construct(){
        parent::__construct('sns_mtagmember');
    }

    /**
     * 标签会员列表
     * @param array $condition
     * @param int $page
     * @param string $order
     */
    public function getSnsMTagMemberList($condition, $page, $order) {
        return $this->where($condition)->order($order)->page($page)->select();
    }
    
    /**
     * 更新标签会员
     * @param unknown $where
     * @param unknown $update
     */
    public function editSnsMTagMember($where, $update) {
        return $this->where($where)->update($update);
    }
    
    /**
     * 删除标签会员
     * @param unknown $where
     */
    public function delSnsMTagMember($where) {
        return $this->where($where)->delete();
    }
}

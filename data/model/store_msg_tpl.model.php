<?php
/**
 * 店铺消息模板模型
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class store_msg_tplModel extends Model{
    public function __construct() {
        parent::__construct('store_msg_tpl');
    }

    /**
     * 店铺消息模板列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getStoreMsgTplList($condition, $field = '*', $page = 0, $order = 'smt_code asc') {
        return $this->field($field)->where($condition)->order($order)->page($page)->select();
    }

    /**
     * 店铺消息模板详细信息
     * @param array $condition
     * @param string $field
     */
    public function getStoreMsgTplInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 编辑店铺消息模板
     * @param unknown $condition
     * @param unknown $update
     */
    public function editStoreMsgTpl($condition, $update) {
        return $this->where($condition)->update($update);
    }
}

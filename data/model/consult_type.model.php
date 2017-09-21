<?php
/**
 * 咨询管理
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class consult_typeModel extends Model{
    public function __construct() {
        parent::__construct('consult_type');
    }

    /**
     * 咨询类型列表
     *
     * @param array $condition
     * @param string $field
     * @param string $order
     * @return array
     */
    public function getConsultTypeList($condition, $field = '*', $order = 'ct_sort asc,ct_id desc') {
        return $this->where($condition)->field($field)->order($order)->select();
    }

    /**
     * 单条咨询类型
     *
     * @param unknown $condition
     * @param string $field
     */
    public function getConsultTypeInfo($condition, $field = '*') {
        return $this->where($condition)->field($field)->find();
    }

    /**
     * 添加咨询类型
     * @param array $insert
     * @return int
     */
    public function addConsultType($insert) {
        return $this->insert($insert);
    }

    /**
     * 编辑咨询类型
     * @param array $condition
     * @param array $update
     * @return boolean
     */
    public function editConsultType($condition, $update) {
        return $this->where($condition)->update($update);
    }

    /**
     * 删除咨询类型
     *
     * @param array $condition
     * @return boolean
     */
    public function delConsultType($condition) {
        return $this->where($condition)->delete();
    }
}

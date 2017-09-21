<?php
/**
 * 推荐组合管理
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class p_combo_quotaModel extends Model {
    public function __construct() {
        parent::__construct('p_combo_quota');
    }

    /**
     * 预售套餐列表
     *
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return array
     */
    public function getComboQuotaList($condition, $field = '*', $page = null, $order = 'cq_id desc') {
        return $this->field($field)->where($condition)->order($order)->page($page)->select();
    }

    /**
     * 预售套餐详细信息
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getComboQuotaInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 保存预售套餐
     *
     * @param array $insert
     * @param boolean $replace
     * @return boolean
     */
    public function addComboQuota($insert, $replace = false) {
        return $this->insert($insert, $replace);
    }

    /**
     * 编辑预售套餐
     * @param array $update
     * @param array $condition
     * @return array
     */
    public function editComboQuota($update, $condition) {
        return $this->where($condition)->update($update);
    }
}

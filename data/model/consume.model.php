<?php
/**
 * 消费记录模型管理
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class consumeModel extends Model {
    public function __construct(){
        parent::__construct('consume');
    }

    /**
     * 消费记录列表
     * @param array $condition
     * @param string $field
     * @param int $page
     * @return array
     */
    public function getConsumeList($condition, $field = '*', $page = 0, $limit = 0) {
        return $this->field($field)->where($condition)->limit($limit)->order('consume_id desc')->page($page)->select();
    }

    /**
     * 添加消费记录
     * @param unknown $insert
     * @return boolean
     */
    public function addConsume($insert) {
        return $this->insert($insert);
    }

    /**
     * 删除消费记录
     * @param array $condition
     * @return boolean
     */
    public function delConsume($condition) {
        return $this->where($condition)->delete();
    }
}

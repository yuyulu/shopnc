<?php
/**
 * 加价购套餐
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class p_cou_quotaModel extends Model
{
    public function __construct()
    {
        parent::__construct('p_cou_quota');
    }

    /**
     * 获取加价购活动套餐列表
     */
    public function getCouQuotaList($where, $page = null, $order = '')
    {
        return $this->where($where)->page($page)->order($order)->select();
    }

    /**
     * 增加加价购活动套餐列表
     */
    public function addCouQuota(array $data)
    {
        return $this->insert($data);
    }

    /**
     * 修改加价购活动套餐
     */
    public function editCouQuota(array $data, $where)
    {
        return $this->where($where)->update($data);
    }

    /**
     * 删除加价购活动套餐
     */
    public function delCouQuota(array $where)
    {
        return $this->where($where)->delete();
    }

    /**
     * 通过店铺ID获取当前加价购活动套餐
     */
    public function getCurrentCouQuota($storeId)
    {
        $ts = time();

        return $this->where(array(
            'store_id' => (int) $storeId,
            'tstart' => array('elt', $ts),
            'tend' => array('egt', $ts),
        ))->find();
    }
}

<?php
/**
 * 加价购活动换购商品
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */

defined('In33hao') or exit('Access Invalid!');

class p_cou_level_skuModel extends Model
{
    public function __construct()
    {
        parent::__construct('p_cou_level_sku');
    }

    /**
     * 通过ID获取多个加价购活动的规则中的换购商品
     */
    public function getCouLevelSkusByCouIds(array $couIds)
    {
        $data = (array) $this->where(array(
            'cou_id' => array('in', $couIds),
        ))->limit(false)->order('xlevel')->select();

        $result = array();
        foreach ($data as $d) {
            $result[$d['cou_id']][$d['xlevel']][$d['sku_id']] = $d;
        }

        return $result;
    }

    /**
     * 通过ID获取加价购活动规则中换购商品
     */
    public function getCouLevelSkusByCouId($couId)
    {
        $data = (array) $this->where(array(
            'cou_id' => (int) $couId,
        ))->limit(false)->order('xlevel')->select();

        return Tpl::groupIndexed($data, 'xlevel', 'sku_id');
    }

    /**
     * 增加加价购活动规则中换购商品
     */
    public function addCouLevelSku(array $data)
    {
        return $this->insert($data);
    }

    /**
     * 通过ID删除加价购活动规则中换购商品
     */
    public function delCouLevelSkuByCouId($couId)
    {
        return $this->where(array(
            'cou_id' => array('in', (array) $couId),
        ))->delete();
    }
}

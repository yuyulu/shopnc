<?php
/**
 * 加价购活动商品
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */


defined('In33hao') or exit('Access Invalid!');

class p_cou_skuModel extends Model
{
    public function __construct()
    {
        parent::__construct('p_cou_sku');
    }

    /**
     * 通过商品SKU获取当前时间之后 最早开始的活动时间
     */
    public function getEarliestTimeFromNowOnBySku($sku)
    {
        $d = $this->field('min(tstart) mt')->where(array(
            'sku_id' => (int) $sku,
            'tstart' => array('egt', time()),
        ))->find();

        return (int) $d['mt'];
    }

    /**
     * 通过商品SKU获取当前的加价购活动ID
     */
    public function getCurrentCouBySku(array $skus, array $jjgIdFilter = null)
    {
        $ts = time();
        $where = array(
            'sku_id' => array('in', $skus),
            'tstart' => array('lt', $ts),
            'tend' => array('gt', $ts),
        );

        if ($jjgIdFilter) {
            $where['cou_id'] = array('in', $jjgIdFilter);
        }

        $data = (array) $this->field('sku_id, cou_id')->where($where)->select();
        return Tpl::indexedValues($data, 'sku_id', 'cou_id');
    }

    /**
     * 通过活动ID获取参与加价购活动的商品
     */
    public function getSkuBySingleCouId($couId)
    {
        $data = $this->getSkuByCou((int) $couId);
        if (isset($data[$couId])) {
            return (array) $data[$couId];
        }

        return array();
    }

    /**
     * 通过多个活动ID获取参与加价购活动的商品
     */
    public function getSkuByCou($cou)
    {
        $data = $this->where(array(
            'cou_id' => array('in', (array) $cou),
        ))->select();

        if (!$data) {
            return array();
        }

        return Tpl::groupedValues($data, 'cou_id', 'sku_id');
    }

    /**
     * 设置参与加价购活动的商品
     * 自动去除同一时段已经参加了其它加价购活动的商品
     */
    public function setCouSkus($couId, array $skus, $tstart, $tend)
    {
        $validSkus = array();
        foreach ($skus as $s) {
            $s = (int) $s;
            if ($s > 0) {
                $validSkus[$s] = null;
            }
        }

        if ($validSkus) {
            $invalidSkus = $this->field('distinct sku_id')->where(array(
                'cou_id' => array('neq', $couId),
                'tstart' => array('lt', $tend),
                'tend' => array('gt', $tstart),
                'sku_id' => array('in', array_keys($validSkus)),
            ))->select();

            foreach ((array) $invalidSkus as $i) {
                unset($validSkus[$i['sku_id']]);
            }
        }

        // 删除新增商品的加价购促销缓存
        Model('p_cou')->dropCachedSkuCouDetailBySku(array_keys($validSkus));

        try {
            $this->beginTransaction();
            $this->delCouSkuById($couId);

            foreach (array_keys($validSkus) as $s) {
                $this->addCouSku($couId, $s, $tstart, $tend, false);
            }

            $this->commit();
        } catch (\Exception $ex) {
            $this->rollback();
            throw $ex;
        }
    }

    /**
     * 增加参与加价购活动的商品
     */
    public function addCouSku($couId, $skuId, $tstart, $tend, $replace = false)
    {
        return $this->insert(array(
            'cou_id' => (int) $couId,
            'sku_id' => (int) $skuId,
            'tstart' => (int) $tstart,
            'tend' => (int) $tend,
        ), $replace);
    }

    /**
     * 删除参与加价购活动的商品
     */
    public function delCouSku($where)
    {
        return $this->where($where)->delete();
    }

    /**
     * 通过ID删除参与加价购活动的商品
     */
    public function delCouSkuById($couId, $skuId = 0)
    {
        $where = array();

        if ($couId) {
            $where['cou_id'] = array('in', (array) $couId);
        }
        if ($skuId) {
            $where['sku_id'] = array('in', (array) $skuId);
        }

        if (!$where) {
            return false;
        }

        return $this->delCouSku($where);
    }
}

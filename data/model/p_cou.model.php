<?php
/**
 * 加价购
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class p_couModel extends Model
{
    const CACHE_PREFIX_OF_SKU_COU = 'sku_jjg';

    const COU_STATE_NORMAL = 1;
    const COU_STATE_CLOSE = 2;
    const COU_STATE_CANCEL = 3;

    private $couStates = array(
        0 => '全部',
        self::COU_STATE_NORMAL => '正常',
        self::COU_STATE_CLOSE => '已结束',
        self::COU_STATE_CANCEL => '管理员关闭',
    );

    public function __construct()
    {
        parent::__construct('p_cou');
    }

    public function dropCachedSkuCouDetailBySku($skus)
    {
        foreach ((array) $skus as $sku) {
            dcache($sku, self::CACHE_PREFIX_OF_SKU_COU);
        }
    }

    public function dropCachedSkuCouDetailByWhere($where)
    {
        // throw new Exception(var_export($where, true));

        if (is_array($where) && isset($where['id'])) {
            $ids = $where['id'];
            if (is_array($ids) && isset($ids[0]) && strtolower((string) $ids[0]) === 'in') {
                $ids = (array) $ids[1];
            } else {
                $ids = (array) $ids;
            }
        } else {
            $ids = (array) $this->field('id')->where($where)->limit(false)->select();
			
            $ids = Tpl::uniqueValues($ids, 'id');
        }

        $this->dropCachedSkuCouDetail($ids);
    }

    public function dropCachedSkuCouDetail($couIds)
    {
        if (empty($couIds)) {
            return;
        }

        $couSkus = Model('p_cou_sku')->getSkuByCou((array) $couIds);

        foreach ((array) $couSkus as $skus) {
            foreach ((array) $skus as $sku) {
                dcache($sku, self::CACHE_PREFIX_OF_SKU_COU);
            }
        }
    }

    /**
     * 通过SKU获取加价购活动的详细信息
     * 此方法使用了缓存
     * 此方法一般供前台商品模型使用
     */
    public function getCachedRelationalCouDetailBySingleSku($sku)
    {
        $sku = (int) $sku;
        if ($sku < 1) {
            return;
        }

        $data = rcache($sku, self::CACHE_PREFIX_OF_SKU_COU);
        $data = @unserialize($data['wrapper']);

        if ($data) {
            // cache exists but invalid
            if (isset($data['notExistsUtil'])) {
                if ($data['notExistsUtil'] === 0 || $data['notExistsUtil'] >= time()) {
                    return;
                }
            } else {
                if ($data['info']['tend'] >= time()) {
                    return $data;
                }
            }

            // from now on there may have some promo
            dcache($sku, self::CACHE_PREFIX_OF_SKU_COU);
        }

        $data = $this->getRelationalCouDetailBySku(array(
            $sku,
        ));

        if ($data) {
            $jjgId = $data['couMap'][$sku];
            $jjgDetail = $data['cou'][$jjgId];
            $jjgDetail['items'] = $data['items'];

            $data = $jjgDetail;
            wcache($sku, array(
                'wrapper' => serialize($data),
            ), self::CACHE_PREFIX_OF_SKU_COU);

            return $data;
        } else {
            $earliestTs = (int) Model('p_cou_sku')->getEarliestTimeFromNowOnBySku($sku);
            wcache($sku, array(
                'wrapper' => serialize(array(
                    'notExistsUtil' => $earliestTs,
                )),
            ), self::CACHE_PREFIX_OF_SKU_COU);

            return;
        }
    }

    /**
     * 通过SKU获取多个加价购活动的详细信息
     * 此方法一般供前台使用
     */
    public function getRelationalCouDetailBySku(array $skus, array $jjgIdFilter = null)
    {
        $data = array();
        $couData = array();

        $couMap = Model('p_cou_sku')->getCurrentCouBySku($skus, $jjgIdFilter);
        $couDetails = $this->getActiveCouInfoByIds(array_unique($couMap));

        foreach ($couDetails as $k => $v) {
            $couData[$k]['info'] = $v;
        }

        // 当前有效
        foreach ($couMap as $k => $v) {
            if (isset($couData[$v])) {
                $data['couMap'][$k] = $v;
            }
        }

        $couIds = array_keys($couData);

        if ($couIds) {
            // 规则
            $couLevels = Model('p_cou_level')->getCouLevelsByCouIds($couIds);
            foreach ($couLevels as $k => $v) {
                $couData[$k]['levels'] = $v;
            }

            $skuIds = array();

            // 规则换购
            $couLevelSkus = Model('p_cou_level_sku')->getCouLevelSkusByCouIds($couIds);

            // 各级最低“再加xx元可以换购”
            foreach ($couData as $k => $v) {
                // 如果未设置等级则此活动无效
                if (empty($v['levels'])) {
                    unset($couData[$k]);
                    continue;
                }

                $lastLevelSkus = array();
                foreach ((array) $v['levels'] as $kk => $vv) {
                    // 继承上级换购商品
                    $lastLevelSkus += (array) $couLevelSkus[$k][$kk];
                    $couData[$k]['levelSkus'][$kk] = $lastLevelSkus;

                    // 取最小值
                    $plus = 0;
                    foreach ($lastLevelSkus as $kkk => $vvv) {
                        if ($plus === 0 || $vvv['price'] < $plus) {
                            $plus = $vvv['price'];
                        }
                    }
                    $couData[$k]['levels'][$kk]['plus'] = $plus;
                }

                // 收集商品SKU
                $skuIds += array_flip(array_keys($lastLevelSkus));

                // 第一级“满xx加xx”
                $couData[$k]['firstLevel']['mincost'] = $couData[$k]['levels'][1]['mincost'];
                $couData[$k]['firstLevel']['plus'] = $couData[$k]['levels'][1]['plus'];
            }

            $data['cou'] = $couData;

            // 换购中商品SKU
            $skuIds = array_keys($skuIds);
            $skuItems = array();

            if ($skuIds) {
                $items = (array) Model('goods')->getGeneralGoodsList(array(
                    'goods_id' => array('in', $skuIds),
                ));
                //处理商品消费者保障服务信息
                $items = Model('goods')->getGoodsContract($items);
                foreach ($items as $i) {
                    //规格
                    $_tmp_name = unserialize($i['spec_name']);
                    $_tmp_value = unserialize($i['goods_spec']);
                    $i['goods_spec'] = '';
                    if (is_array($_tmp_name) && is_array($_tmp_value)) {
                        $_tmp_name = array_values($_tmp_name);$_tmp_value = array_values($_tmp_value);
                        foreach ($_tmp_name as $sk => $sv) {
                            $i['goods_spec'] .= $sv.'：'.$_tmp_value[$sk].'，';
                        }
                    }
                    $skuItems[$i['goods_id']] = array(
                        'id' => $i['goods_id'],
                        'name' => $i['goods_name'],
                        'price' => $i['goods_price'],
                        'storage' => $i['goods_storage'],
                        'url' => urlShop('goods', 'index', array('goods_id' => $i['goods_id'], )),
                        'imgUrl' => cthumb($i['goods_image'], 60, $i['store_id']),
                        // for orders
                        'goods_image' => $i['goods_image'],
                        'gc_id' => $i['gc_id'],
                        'goods_storage' => $i['goods_storage'],
                        'goods_storage_alarm' => $i['goods_storage_alarm'],
                        'goods_commonid' => $i['goods_commonid'],
                        'store_id' => $i['store_id'],
                        'goods_spec' => rtrim($i['goods_spec'],'，'),
                        'contractlist' =>$i['contractlist']
                    );
                }
            }
            $data['items'] = $skuItems;
        }

        return $data;
    }

    /**
     * 通过ID获取加价购全部信息
     */
    public function getRelationalCouDetailById($couId, $storeId)
    {
        $couInfo = $this->getCouInfoByID($couId, $storeId);
        if (empty($couInfo)) {
            return;
        }

        $result = array();
        $result['info'] = $couInfo;

        $result['skus'] = $skus = Model('p_cou_sku')->getSkuBySingleCouId($couId);
        $result['levels'] = $levels = Model('p_cou_level')->getCouLevels($couId);
        $result['levelSkus'] = $levelSkus = Model('p_cou_level_sku')->getCouLevelSkusByCouId($couId);

        $flippedSkuIds = array_flip($skus);
        foreach ($levelSkus as $v) {
            $flippedSkuIds += array_flip(array_keys($v));
        }

        $flippedSkuIds = array_keys($flippedSkuIds);
        if ($flippedSkuIds) {
            $items = (array) Model('goods')->getGeneralGoodsList(array(
                'goods_id' => array('in', $flippedSkuIds),
                'store_id' => $storeId,
            ));

            $result['items'] = Tpl::indexed($items, 'goods_id');
        }

        return $result;
    }

    public function getCouStates()
    {
        return $this->couStates;
    }

    /**
     * 获取加价购活动列表
     */
    public function getCouList($where, $page = null, $order = '')
    {
        $data = (array) $this->where($where)->page($page)->order($order)->select();

        return $data;
    }

    /**
     * 通过ID获取多个加价购活动基本信息
     */
    public function getActiveCouInfoByIds(array $couIds)
    {
        $ts = time();

        return $this->where(array(
            'id' => array('in', $couIds),
            'state' => self::COU_STATE_NORMAL,
            'tstart' => array('lt', $ts),
            'tend' => array('gt', $ts),
        ))->key('id')->select();
    }

    /**
     * 通过ID获取正在进行中的加价购活动基本信息
     */
    public function getActiveCouInfoById($couId, $storeId = 0)
    {
        $ts = time();
        $where = array(
            'id' => (int) $couId,
            'state' => self::COU_STATE_NORMAL,
            'tstart' => array('lt', $ts),
            'tend' => array('gt', $ts),
        );

        if ($storeId > 0) {
            $where['store_id'] = $storeId;
        }

        return $this->where($where)->find();
    }

    /**
     * 通过ID获取加价购活动基本信息
     */
    public function getCouInfoByID($couId, $storeId = 0)
    {
        $where = array(
            'id' => (int) $couId,
        );

        if ($storeId > 0) {
            $where['store_id'] = (int) $storeId;
        }

        $record = (array) $this->where($where)->find();

        return $record;
    }

    /**
     * 添加加价购活动
     */
    public function addCou($data)
    {
        return $this->insert($data);
    }

    /**
     * 修改加价购活动
     */
    public function editCou($data, $where)
    {
        // 删除缓存
        $this->dropCachedSkuCouDetailByWhere($where);
        return $this->where($where)->update($data);
    }

    /**
     * 通过ID删除加价购活动
     */
    public function delCouById($id)
    {
        $id = (int) $id;

        $this->beginTransaction();
        try {
            // 删除缓存
            $this->dropCachedSkuCouDetail($id);

            $this->where(array(
                'id' => $id,
            ))->delete();

            Model('p_cou_level')->delCouLevelById($id);
            Model('p_cou_level_sku')->delCouLevelSkuByCouId($id);
            Model('p_cou_sku')->delCouSkuById($id);

            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }

        return true;
    }

    /**
     * 通过ID取消加价购活动
     */
    public function cancelCouById($id)
    {
        return $this->cancelCou(array(
            'id' => $id,
        ));
    }

    /**
     * 取消加价购活动
     */
    public function cancelCou($condition)
    {
        // 删除缓存
        $this->dropCachedSkuCouDetailByWhere($condition);

        return $this->where($condition)->update(array(
            'state' => self::COU_STATE_CANCEL,
        ));
    }

    /**
     * 通过ID开启加价购活动
     */
    public function reopenCouById($id)
    {
        return $this->reopenCou(array(
            'id' => $id,
        ));
    }

    /**
     * 开启加价购活动
     */
    public function reopenCou($condition)
    {
        // 删除缓存
        $this->dropCachedSkuCouDetailByWhere($condition);

        return $this->where($condition)->update(array(
            'state' => self::COU_STATE_NORMAL,
        ));
    }

    /**
     * 加价购活动设置为过期
     */
    public function editExpireCou($condition)
    {
        // 删除缓存
        $this->dropCachedSkuCouDetailByWhere($condition);

        return $this->where($condition)->update(array(
            'state' => self::COU_STATE_CLOSE,
        ));
    }

}

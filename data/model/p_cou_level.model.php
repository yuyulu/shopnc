<?php
/**
 * 加价购规则
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class p_cou_levelModel extends Model
{
    const MONEY_MIN = 0.01;

    public static function compareLevel(array $a, array $b)
    {
        if ($a['mincost'] < self::MONEY_MIN || $b['mincost'] < self::MONEY_MIN) {
            throw new Exception('规则中购满金额不能为0或负数');
        }

        $c = $a['mincost'] - $b['mincost'];

        if ($c > self::MONEY_MIN) {
            return 1;
        }

        if (-$c > self::MONEY_MIN) {
            return -1;
        }

        // or throws?
        throw new Exception('规则中购满金额有重复');
        return 0;
    }

    public function __construct()
    {
        parent::__construct('p_cou_level');
    }

    /**
     * 获取多个加价购活动规则列表
     */
    public function getCouLevelsByCouIds(array $couIds)
    {
        $data = (array) $this->where(array(
            'cou_id' => array('in', $couIds),
        ))->order('xlevel')->limit(false)->select();

        return Tpl::groupIndexed($data, 'cou_id', 'xlevel');
    }

    /**
     * 获取加价购活动规则列表
     */
    public function getCouLevels($couId)
    {
        $data = (array) $this->where(array(
            'cou_id' => (int) $couId,
        ))->order('xlevel')->limit(false)->select();

        return Tpl::indexed($data, 'xlevel');
    }

    /**
     * 设置加价购活动规则
     * 包括每条规则等级中可以限购的商品
     */
    public function setCouLevels($couId, array $levels)
    {
        $couId = (int) $couId;
        $couLevelSkuModel = Model('p_cou_level_sku');

        $this->beginTransaction();

        try {
            $this->where(array(
                'cou_id' => $couId,
            ))->delete();

            $couLevelSkuModel->delCouLevelSkuByCouId($couId);

            // remote __level indexed
            unset($levels['__level']);

            $levelsCount = count($levels);
            if ($levelsCount < 1) {
                throw new Exception('请至少设置1条换购规则');
            }
            if ($levelsCount > 100) {
                throw new Exception('最多只能设置100条换购规则');
            }

            usort($levels, __CLASS__ . '::compareLevel');

            foreach ($levels as $firstLevel) {
                break;
            }

            if ($firstLevel['mincost'] < self::MONEY_MIN) {
                throw new Exception('购满金额不能为0或负数');
            }

            if (empty($firstLevel['skus'])) {
                throw new Exception('购满金额最低的换购规则必须要有换购商品');
            }

            $iLevel = 0;

            foreach ($levels as $lev) {
                $iLevel++;

                $this->insert(array(
                    'cou_id' => $couId,
                    'xlevel' => $iLevel,
                    'mincost' => $lev['mincost'],
                    'maxcou' => max(0, (int) $lev['maxcou']),
                ));

                $skus = $lev['skus'];
                if (!$skus || !is_array($skus)) {
                    // or throws?
                    continue;
                }

                foreach ($skus as $skuId => $price) {
                    $couLevelSkuModel->addCouLevelSku(array(
                        'cou_id' => $couId,
                        'xlevel' => $iLevel,
                        'sku_id' => (int) $skuId,
                        'price' => (float) $price,
                    ));
                }
            }

            $this->commit();
        } catch (\Exception $ex) {
            $this->rollback();
            throw $ex;
        }
    }

    /**
     * 通过ID删除加价购活动规则
     */
    public function delCouLevelById($couId)
    {
        $this->where(array(
            'cou_id' => array('in', (array) $couId),
        ))->delete();
    }
}

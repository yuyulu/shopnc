<?php
/**
 * 商品推荐组合模型
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class p_combo_goodsModel extends Model {
    public function __construct(){
        parent::__construct('p_combo_goods');
    }

    /**
     * 插入数据
     *
     * @param unknown $insert
     * @return boolean
     */
    public function addComboGoodsAll($insert) {
        $result = $this->insertAll($insert);
        if ($result) {
            foreach ((array)$insert as $v) {
                if ($v['goods_id']) $this->_dComboGoodsCache($v['goods_id']);
            }
        }
        return $result;
    }

    /**
     * 查询组合商品列表
     * @param unknown $condition
     */
    public function getComboGoodsList($condition, $field = '*', $page = null, $order = 'cg_id desc') {
        return $this->field($field)->where($condition)->order($order)->page($page)->select();
    }

    /**
     * 删除推荐组合商品
     */
    public function delComboGoods($condition) {
        $list = $this->getComboGoodsList($condition, 'goods_id');
        if (empty($list)) {
            return true;
        }
        $result = $this->where($condition)->delete();
        if ($result) {
            foreach ($list as $v) {
                $this->_dComboGoodsCache($v['goods_id']);
            }
        }
        return $result;
    }
    
    /**
     * 根据商品id删除推荐组合
     * @param unknown $goods_id
     */
    public function delComboGoodsByGoodsId($goods_id) {
        $this->where(array('goods_id' => $goods_id))->delete();
        return $this->_dComboGoodsCache($goods_id);
    }

    public function getComboGoodsCacheByGoodsId($goods_id) {
        $array = $this->_rComboGoodsCache($goods_id);
        if (empty($array)) {
            $array = array();
            $arr = array();
            $gcombo_list = array();
            $combo_list = $this->getComboGoodsList(array('goods_id' => $goods_id));
            if (!empty($combo_list)) {
                $comboid_array= array();
                foreach ($combo_list as $val) {
                    $comboid_array[] = $val['combo_goodsid'];
                }
                $gcombo_list = Model('goods')->getGeneralGoodsList(array('goods_id' => array('in', $comboid_array)));
                $gcombo_list = array_under_reset($gcombo_list, 'goods_id');
                foreach ($combo_list as $val) {
                    if (empty($gcombo_list[$val['combo_goodsid']])) {
                        continue;
                    }
                    $array[$val['cg_class']][] = $gcombo_list[$val['combo_goodsid']];
                }
                $i = 1;
                foreach ($array as $key => $val) {
                    $arr[$i]['name'] = $key;
                    $arr[$i]['goods'] = $val;
                    $i++;
                }
            }
            $array = array('gcombo_list' => serialize($arr));
            $this->_wComboGoodsCache($goods_id, $array);
        }
        return $array;
    }

    /**
     * 读取商品推荐搭配缓存
     * @param int $goods_id
     * @return array
     */
    private function _rComboGoodsCache($goods_id) {
        return rcache($goods_id, 'goods_combo');
    }

    /**
     * 写入商品推荐搭配缓存
     * @param int $goods_id
     * @param array $array
     * @return boolean
     */
    private function _wComboGoodsCache($goods_id, $array) {
        return wcache($goods_id, $array, 'goods_combo', 60);
    }

    /**
     * 删除商品推荐搭配缓存
     * @param int $goods_id
     * @return boolean
     */
    private function _dComboGoodsCache($goods_id) {
        return dcache($goods_id, 'goods_combo');
    }
}

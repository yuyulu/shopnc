<?php
/**
 * 商品赠品模型
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class goods_giftModel extends Model {
    public function __construct(){
        parent::__construct('goods_gift');
    }
    
    /**
     * 允许赠送赠品的商品列表
     * @param unknown $condition
     * @param string $field
     */
    public function getAllowGiftGoodsList($condition, $field = '*') {
        $condition['is_virtual']    = 0;
        return Model('goods')->getGoodsList($condition, $field);
    }

    /**
     * 插入数据
     *
     * @param unknown $insert
     * @return boolean
     */
    public function addGoodsGiftAll($insert) {
        $result = $this->insertAll($insert);
        if ($result) {
            foreach ($insert as $val) {
                $this->_dGoodsGiftCache($val['goods_id']);
            }
        }
        return $result;
    }

    /**
     * 查询赠品列表
     * @param unknown $condition
     */
    public function getGoodsGiftList($condition) {
        return $this->where($condition)->select();
    }

    public function getGoodsGiftListByGoodsId($goods_id) {
        $condition['goods_id'] = $goods_id;
        $list = $this->_rGoodsGiftCache($goods_id);
        if (empty($list)) {
            $gift_list = $this->getGoodsGiftList($condition);
            $list['gift'] = serialize($gift_list);
            $this->_wGoodsGiftCache($goods_id, $list);
        }
        $gift_list = unserialize($list['gift']);
        return $gift_list;
    }

    /**
     * 删除赠品
     */
    public function delGoodsGift($condition) {
        $gift_list = $this->getGoodsGiftList($condition);
        if (empty($gift_list)) {
            return true;
        }
        $result = $this->where($condition)->delete();
        if ($result) {
            foreach ($gift_list as $val) {
                $this->_dGoodsGiftCache($val['goods_id']);
            }
        }
        return $result;
    }

    /**
     * 读取商品公共缓存
     * @param int $goods_id
     * @return array
     */
    private function _rGoodsGiftCache($goods_id) {
        return rcache($goods_id, 'goods_gift');
    }

    /**
     * 写入商品公共缓存
     * @param int $goods_id
     * @param array $list
     * @return boolean
     */
    private function _wGoodsGiftCache($goods_id, $list) {
        return wcache($goods_id, $list, 'goods_gift');
    }

    /**
     * 删除商品公共缓存
     * @param int $goods_id
     * @return boolean
     */
    private function _dGoodsGiftCache($goods_id) {
        return dcache($goods_id, 'goods_gift');
    }
}

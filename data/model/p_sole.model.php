<?php
/**
 * 手机专享管理
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */

defined('In33hao') or exit('Access Invalid!');

class p_soleModel extends Model {
    const STATE1 = 1;       // 开启
    const STATE0 = 0;       // 关闭
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * 手机专享套餐列表
     *
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return array
     */
    public function getSoleQuotaList($condition, $field = '*', $page = 0, $order = 'sole_quota_id desc') {
        return $this->table('p_sole_quota')->field($field)->where($condition)->order($order)->page($page)->select();
    }

    /**
     * 手机专享套餐详细信息
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getSoleQuotaInfo($condition, $field = '*') {
        return $this->table('p_sole_quota')->field($field)->where($condition)->find();
    }

    /**
     * 通过的套餐详细信息
     *
     * @param int $store_id
     * @param string $field
     * @return array
     */
    public function getSoleQuotaInfoCurrent($store_id) {
        $condition['store_id'] = $store_id;
        $condition['sole_quota_endtime'] = array('gt', TIMESTAMP);
        $condition['sole_state'] = self::STATE1;
        return $this->getSoleQuotaInfo($condition);
    }

    /**
     * 保存手机专享套餐
     *
     * @param array $insert
     * @param boolean $replace
     * @return boolean
     */
    public function addSoleQuota($insert, $replace = false) {
        return $this->table('p_sole_quota')->pk(array('sole_quota_id'))->insert($insert, $replace);
    }

    /**
     * 编辑手机专享套餐
     * @param array $update
     * @param array $condition
     * @return array
     */
    public function editSoleQuota($update, $condition) {
        return $this->table('p_sole_quota')->where($condition)->update($update);
    }

    /**
     * 编辑手机专享套餐
     * @param array $update
     * @param array $condition
     * @return array
     */
    public function editSoleQuotaOpen($update, $condition) {
        $update['sole_state'] = self::STATE1;
        return $this->table('p_sole_quota')->where($condition)->update($update);
    }

    /**
     * 商品列表
     *
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param int $limit
     * @param string $order
     * @return array
     */
    public function getSoleGoodsList($condition, $field = '*', $page = 0, $limit = 0, $order = 'sole_goods_id asc') {
        return $this->table('p_sole_goods')->field($field)->where($condition)->limit($limit)->order($order)->page($page)->select();
    }

    /**
     * 获取手机专享商品详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getSoleGoodsInfo($condition, $field = '*') {
        return $this->table('p_sole_goods')->field($field)->where($condition)->find();
    }

    /**
     * 取得商品详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $goods_id
     * @return array
     */
    public function getSoleGoodsInfoOpenByGoodsID($goods_id) {
        $goods_info = $this->_rGoodsSoleCache($goods_id);
        if (empty($goods_info)) {
            $goods_info = $this->getSoleGoodsInfo(array('goods_id'=>$goods_id, 'sole_state' => self::STATE1));
            $this->_wGoodsSoleCache($goods_id, $goods_info);
        }
        return $goods_info;
    }

    /**
     * 保存手机专享商品信息
     * @param array $insert
     * @return boolean
     */
    public function addSoleGoods($insert) {
        return $this->table('p_sole_goods')->insert($insert);
    }
    
    /**
     * 更新手机专享商品信息
     */
    public function editSoleGoods($update, $condition) {
        $solegoods_list = $this->getSoleGoodsList($condition);
        if (empty($solegoods_list)) {
            return true;
        }
        $goodsid_array = array();
        foreach ($solegoods_list as $val) {
            $goodsid_array[] = $val['goods_id'];
        }
        $result = $this->table('p_sole_goods')->where(array('goods_id' => array('in', $goodsid_array)))->update($update);
        if ($result) {
            foreach ($goodsid_array as $val) {
                $this->_dGoodsSoleCache($val);
            }
        }
        return $result;
    }

    /**
     * 更新套餐为关闭状态
     * @param array $condition
     * @return boolean
     */
    public function editSoleClose($condition) {
        $quota_list = $this->getSoleQuotaList($condition);
        if (empty($quota_list)) {
            return true;
        }
        $storeid_array = array();
        foreach ($quota_list as $val) {
            $storeid_array[] = $val['store_id'];
        }
        $where = array('store_id' => array('in', $storeid_array));
        $update = array('sole_state' => self::STATE0);
        $this->editSoleQuota($update, $where);
        $this->editSoleGoods($update, $where);
        return true;
    }

    /**
     * 删除手机专享商品
     *
     * @param unknown $condition
     * @return boolean
     */
    public function delSoleGoods($condition) {
        return $this->table('p_sole_goods')->where($condition)->delete();
    }

    /**
     * 读取商品限时折扣缓存
     * @param int $goods_id
     * @return array/bool
     */
    private function _rGoodsSoleCache($goods_id) {
        return rcache($goods_id, 'goods_sole');
    }
    
    /**
     * 写入商品限时折扣缓存
     * @param int $goods_id
     * @param array $info
     * @return boolean
     */
    private function _wGoodsSoleCache($goods_id, $info) {
        return wcache($goods_id, $info, 'goods_sole');
    }
    
    /**
     * 删除商品限时折扣缓存
     * @param int $goods_id
     * @return boolean
     */
    private function _dGoodsSoleCache($goods_id) {
        return dcache($goods_id, 'goods_sole');
    }
}

<?php
/**
 * 买家 交易快照
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class snapshotControl extends BaseHomeControl {

    public function __construct() {
        parent::__construct();
        Tpl::setLayout('home_layout');
    }

    public function indexOp() {
        $rec_id = intval($_GET['rec_id']);
        if ($rec_id <= 0) {
            showMessage('参数错误', '', 'html', 'error');
        }
        $model_order = Model('order');
        $order_goods_info = $model_order->getOrderGoodsInfo(array('rec_id' => $rec_id));
        if (empty($order_goods_info)) {
            showMessage('参数错误，或者不是本人购买的商品', '', 'html', 'error');
        }
        $order_goods_info['goods_type_cn'] = orderGoodsType($order_goods_info['goods_type']);
        $spec_array = array();
        if ($order_goods_info['goods_spec'] != '') {
            $spec = explode('，', $order_goods_info['goods_spec']);
            foreach ($spec as $key=>$val) {
                $param = explode('：', $val);
                $spec_array[$param[0]] = $param[1];
            }
        }
        $order_goods_info['goods_spec'] = $spec_array;

        //查询消费者保障服务
        if (C('contract_allow') == 1 && !empty($order_goods_info['goods_contractid'])) {
            $contract_item = Model('contract')->getContractItemByCache();

            $goods_contractid_arr = explode(',',$order_goods_info['goods_contractid']);
            foreach ((array)$goods_contractid_arr as $gcti_v) {
                $order_goods_info['contractlist'][] = $contract_item[$gcti_v];
            }
        }

        $sp_hot_info = Model('order_snapshot')->getSnapshotInfoByRecid($rec_id,$order_goods_info['goods_id']);
        $sp_hot_info['goods_attr'] = unserialize($sp_hot_info['goods_attr']);
        Tpl::output('goods', array_merge($order_goods_info,$sp_hot_info));

        $order_info = $model_order->getOrderInfo(array('order_id' => $order_goods_info['order_id']));
        Tpl::output('order_info', $order_info);

        $store_info = Model('store')->getStoreInfo(array('store_id' => $order_goods_info['store_id']));
        if (!empty($store_info) && $store_info['is_own_shop'] == 0) {
            Tpl::output('store_info', $store_info);
        }
        
        Tpl::showpage('snapshot');
    }
}

<?php
/**
 * 物流自提服务站首页
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class orderControl extends BaseChainCenterControl{
    public function __construct(){
        parent::__construct();
    }
    
    public function indexOp() {
        $model_order = Model('order');
        $condition = array();
        $condition['chain_id'] = $_SESSION['chain_id'];
        $condition['chain_code'] = array('gt',0);
        if ($_GET['search_state_type'] == 'yes') {
            $condition['order_state'] = ORDER_STATE_SUCCESS;
        } else {
            $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        }
        if ($_GET['keyword'] != '') {
            if ($_GET['search_key_type'] == 'chain_code') {
            	$condition['chain_code'] = preg_match('/^\d{4}$/',$_GET['keyword']) ? $_GET['keyword'] : -1;
            } elseif ($_GET['search_key_type'] == 'order_sn') {
                $condition['order_sn'] = preg_match('/^\d{10,20}$/',$_GET['keyword']) ? $_GET['keyword'] : -1;
            } else {
                $condition['buyer_phone'] = preg_match('/^\d{11}$/',$_GET['keyword']) ? $_GET['keyword'] : -1;
            }
        }

        $order_list = $model_order->getOrderList($condition, 20, '*', 'order_id desc','', array('order_goods','order_common'));
        //页面中显示那些操作
        foreach ($order_list as $key => $order_info) {
            foreach ($order_info['extend_order_goods'] as & $value) {
                $value['image_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
                $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
            }
            usort($order_info['extend_order_goods'],function($a,$b){
                if ($a['goods_type'] == $b['goods_type']) return 0;
            	return $a['goods_type'] > $b['goods_type'] ? 1 : -1;
            });
            $order_list[$key] = $order_info;
        }
        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        Tpl::showpage('order.list');
    }

    /**
     * 提货验证
     */
    public function pickup_parcelOp() {
        if (chksubmit()) {
            $order_id = intval($_POST['order_id']);
            $pickup_code = intval($_POST['pickup_code']);
            if ($order_id <= 0 || $pickup_code <= 0) {
                showDialog(L('wrong_argument'));
            }
            $model_order = Model('order');
            $order_info = $model_order->getOrderInfo(array('order_id' => $order_id, 'chain_code' => $pickup_code));
            if (empty($order_info)) {
                showDialog('提货码错误');
            }
            $logic_order = Logic('order');
            $if_allow = $model_order->getOrderOperateState('chain_receive',$order_info);
            if (!$if_allow) {
                showDialog('无权操作');
            }
            $result = $logic_order->changeOrderStateReceive($order_info,'chain','自提门店','商品已被买家自提，门店更改订单为完成状态');

            if ($result['state']) {
                showDialog('提货成功', 'reload', 'succ', 'DialogManager.close("pickup_parcel")');
            } else {
                showDialog($result['msg']);
            }
        } else {
            $order_model = Model('order');
            $condition['order_id'] = intval($_GET['order_id']);
            $condition['chain_id'] = $_SESSION['chain_id'];
            $order_info = $order_model->getOrderInfo($condition,array('order_common','order_goods'));
            foreach ($order_info['extend_order_goods'] as $k => $value) {
                $value['image_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
                $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
                $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
                $order_info['extend_order_goods'][$k] = $value;
            }
            usort($order_info['extend_order_goods'],function($a,$b){
                if ($a['goods_type'] == $b['goods_type']) return 0;
                return $a['goods_type'] > $b['goods_type'] ? 1 : -1;
            });
            Tpl::output('order_info',$order_info);
        }
        Tpl::showpage('order.pickup_parcel', 'null_layout');
    }
}

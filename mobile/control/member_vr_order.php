<?php
/**
 * 我的订单
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_vr_orderControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 订单列表
     */
    public function order_listOp() {

        $ownShopIds = Model('store')->getOwnShopIds();

        $model_vr_order = Model('vr_order');
        
        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
        if (preg_match('/^\d{10,20}$/',$_POST['order_key'])) {
            $condition['order_sn'] = $_POST['order_key'];
        } elseif ($_POST['order_key'] != '') {
            $condition['goods_name'] = array('like','%'.$_POST['order_key'].'%');
        }
        if ($_POST['state_type'] != '') {
            $condition['order_state'] = str_replace(
                    array('state_new','state_pay'),
                    array(ORDER_STATE_NEW,ORDER_STATE_PAY), $_POST['state_type']);
        }
        $order_list = $model_vr_order->getOrderList($condition, $this->page, '*', 'order_id desc');

        foreach ($order_list as $key => $order) {
            //显示取消订单
            $order_list[$key]['if_cancel'] = $model_vr_order->getOrderOperateState('buyer_cancel',$order);
        
            //显示支付
            $order_list[$key]['if_pay'] = $model_vr_order->getOrderOperateState('payment',$order);

            //显示评价
            $order_list[$key]['if_evaluation'] = $model_vr_order->getOrderOperateState('evaluation',$order);

            $order_list[$key]['goods_image_url'] = cthumb($order['goods_image'], 240, $order['store_id']);

            $order_list[$key]['ownshop'] = in_array($order['store_id'], $ownShopIds);
        }

        $page_count = $model_vr_order->gettotalpage();

        output_data(array('order_list' => $order_list), mobile_page($page_count));
    }

    public function order_infoOp() {
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
            output_error('订单不存在');
        }
        $model_vr_order = Model('vr_order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_vr_order->getOrderInfo($condition);
        if (empty($order_info) || $order_info['delete_state'] == ORDER_DEL_STATE_DROP) {
            output_error('订单不存在');
        }
        $order_list = array();
        $order_list[$order_id] = $order_info;

        //显示取消订单
        $order_info['if_cancel'] = $model_vr_order->getOrderOperateState('buyer_cancel',$order_info);

        //显示评价
        $order_info['if_evaluation'] = $model_vr_order->getOrderOperateState('evaluation',$order_info);

        //显示退款
        $order_info['if_refund'] = $model_vr_order->getOrderOperateState('refund',$order_info);

        $order_info['goods_image_url'] = cthumb($order_info['goods_image'], 240, $order_info['store_id']);

        $ownShopIds = Model('store')->getOwnShopIds();
        $order_info['ownshop'] = in_array($order_info['store_id'], $ownShopIds);

        $order_info['vr_indate'] = $order_info['vr_indate'] ? date('Y-m-d',$order_info['vr_indate']) : '';
        $order_info['add_time'] = date('Y-m-d',$order_info['add_time']);
        $order_info['payment_time'] = $order_info['payment_time'] ? date('Y-m-d',$order_info['payment_time']) : '';
        $order_info['finnshed_time'] = $order_info['finnshed_time'] ? date('Y-m-d',$order_info['finnshed_time']) : '';

        $order_info['if_resend'] = $order_info['order_state'] == ORDER_STATE_PAY ? true : false;
        //取兑换码列表
        $vr_code_list = $model_vr_order->getOrderCodeList(array('order_id' => $order_info['order_id']));
        $order_info['code_list'] = $vr_code_list ? $vr_code_list : array();

        output_data(array('order_info' => $order_info));   
    }

    /**
     * 取消订单
     */
    public function order_cancelOp() {
        $model_vr_order = Model('vr_order');
        $condition = array();
        $condition['order_id'] = intval($_POST['order_id']);
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info	= $model_vr_order->getOrderInfo($condition);

        $if_allow = $model_vr_order->getOrderOperateState('buyer_cancel',$order_info);
        if (!$if_allow) {
            output_data('无权操作');
        }

        $logic_vr_order = Logic('vr_order');
        $result = $logic_vr_order->changeOrderStateCancel($order_info,'buyer', '其它原因');

        if(!$result['state']) {
            output_data($result['msg']);
        } else {
            output_data('1');
        }
    }

    /**
     * 发送兑换码到手机
     */
    public function resendOp() {
        if (!preg_match('/^[\d]{11}$/',$_POST['buyer_phone'])) {
            output_error('请正确填写手机号');
        }
        $order_id   = intval($_POST['order_id']);
        if ($order_id <= 0) {
            output_error('参数错误');
        }

        $model_vr_order = Model('vr_order');

        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_vr_order->getOrderInfo($condition);
        if (empty($order_info) && $order_info['order_state'] != ORDER_STATE_PAY) {
            output_error('订单信息发生错误');
        }
        if ($order_info['vr_send_times'] >= 5) {
            output_error('您发送的次数过多，无法发送');
        }

        //发送兑换码到手机
        $param = array('order_id'=>$order_id,'buyer_id'=>$this->member_info['member_id'],'buyer_phone'=>$_POST['buyer_phone'],'goods_name'=>$order_info['goods_name']);
        QueueClient::push('sendVrCode', $param);

        $model_vr_order->editOrder(array('vr_send_times'=>array('exp','vr_send_times+1')),array('order_id'=>$order_id));

        output_data('1');
    }
}

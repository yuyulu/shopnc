<?php
/**
 * 我的订单
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_orderControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 订单列表
     */
    public function order_listOp() {
        $model_order = Model('order');

        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['order_type'] = array('in',array(1,3));

        if ($_POST['state_type'] != '') {
            $condition['order_state'] = str_replace(
                array('state_new','state_send','state_noeval'),
                array(ORDER_STATE_NEW,ORDER_STATE_SEND,ORDER_STATE_SUCCESS), $_POST['state_type']);
        }
        if ($_POST['state_type'] == 'state_new') {
            $condition['chain_code'] = 0;
        }
        if ($_POST['state_type'] == 'state_noeval') {
            $condition['evaluation_state'] = 0;
            $condition['order_state'] = ORDER_STATE_SUCCESS;
        }
        if ($_POST['state_type'] == 'state_notakes') {
            $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
            $condition['chain_code'] = array('gt',0);
        }

        if (preg_match('/^\d{10,20}$/',$_POST['order_key'])) {
            $condition['order_sn'] = $_POST['order_key'];
        } elseif ($_POST['order_key'] != '') {
            $condition['order_id'] = array('in',$this->_getOrderIdByKeyword($_POST['order_key']));
        }

        $order_list_array = $model_order->getNormalOrderList($condition, $this->page, '*', 'order_id desc','', array('order_goods'));
        $model_refund_return = Model('refund_return');
        $order_list_array = $model_refund_return->getGoodsRefundList($order_list_array,1);//订单商品的退款退货显示

        $ownShopIds = Model('store')->getOwnShopIds();

        $order_group_list = array();
        $order_pay_sn_array = array();
        foreach ($order_list_array as $value) {
            $value['zengpin_list'] = array();
            //显示取消订单
            $value['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$value);
            //显示退款取消订单
            $value['if_refund_cancel'] = $model_order->getOrderOperateState('refund_cancel',$value);
            //显示收货
            $value['if_receive'] = $model_order->getOrderOperateState('receive',$value);
            //显示锁定中
            $value['if_lock'] = $model_order->getOrderOperateState('lock',$value);
            //显示物流跟踪
            $value['if_deliver'] = $model_order->getOrderOperateState('deliver',$value);
            //显示评价
            $value['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$value);
            //显示追加评价
            $value['if_evaluation_again'] = $model_order->getOrderOperateState('evaluation_again',$value);
            //显示删除订单(放入回收站)
            $value['if_delete'] = $model_order->getOrderOperateState('delete',$value);

            $value['ownshop'] = in_array($value['store_id'], $ownShopIds);

            //商品图
            foreach ($value['extend_order_goods'] as $k => $goods_info) {
                $value['extend_order_goods'][$k]['goods_image_url'] = cthumb($goods_info['goods_image'], 240, $value['store_id']);
                $value['extend_order_goods'][$k]['refund'] = $value['extend_order_goods'][$k]['refund'] ? true : false;
                unset($value['extend_order_goods'][$k]['rec_id']);
                unset($value['extend_order_goods'][$k]['order_id']);
                unset($value['extend_order_goods'][$k]['goods_pay_price']);
                unset($value['extend_order_goods'][$k]['store_id']);
                unset($value['extend_order_goods'][$k]['buyer_id']);
                unset($value['extend_order_goods'][$k]['promotions_id']);
                unset($value['extend_order_goods'][$k]['commis_rate']);
                unset($value['extend_order_goods'][$k]['gc_id']);
                unset($value['extend_order_goods'][$k]['goods_contractid']);
                unset($value['extend_order_goods'][$k]['goods_image']);
                if ($value['extend_order_goods'][$k]['goods_type'] == 5) {
                    $value['zengpin_list'][] = $value['extend_order_goods'][$k];
                    unset($value['extend_order_goods'][$k]);
                }
            }
            if ($value['extend_order_goods']) {
                $value['extend_order_goods'] = array_values($value['extend_order_goods']);
            }

            $order_group_list[$value['pay_sn']]['order_list'][] = $value;

            //如果有在线支付且未付款的订单则显示合并付款链接
            if ($value['order_state'] == ORDER_STATE_NEW) {
                $order_group_list[$value['pay_sn']]['pay_amount'] += $value['order_amount'] - $value['rcb_amount'] - $value['pd_amount'];
            }
            $order_group_list[$value['pay_sn']]['add_time'] = $value['add_time'];

            //记录一下pay_sn，后面需要查询支付单表
            $order_pay_sn_array[] = $value['pay_sn'];
        }
        $new_order_group_list = array();
        foreach ($order_group_list as $key => $value) {
            $value['pay_sn'] = strval($key);
            $new_order_group_list[] = $value;
        }

        $page_count = $model_order->gettotalpage();

        output_data(array('order_group_list' => $new_order_group_list), mobile_page($page_count));
    }

    private function _getOrderIdByKeyword($keyword) {
        $goods_list = Model('order')->getOrderGoodsList(array('goods_name'=>array('like','%'.$keyword.'%')),'order_id',100,null,'', null,'order_id');
        return array_keys($goods_list);
    }

    /**
     * 取消订单
     */
    public function order_cancelOp() {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $order_id = intval($_POST['order_id']);

        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['order_type'] = array('in',array(1,3));
        $order_info = $model_order->getOrderInfo($condition);
        $if_allow = $model_order->getOrderOperateState('buyer_cancel',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }
        if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
            $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
            output_error('该订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消');
        }
        $result = $logic_order->changeOrderStateCancel($order_info,'buyer', $this->member_info['member_name'], '其它原因');
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data('1');
        }
    }

    /**
     * 取消订单
     */
    public function order_deleteOp() {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $order_id = intval($_POST['order_id']);
    
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['order_type'] = array('in',array(1,3));
        $order_info = $model_order->getOrderInfo($condition);
        $if_allow = $model_order->getOrderOperateState('delete',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }

        $result = $logic_order->changeOrderStateRecycle($order_info,'buyer','delete');
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data('1');
        }
    }

    /**
     * 订单确认收货
     */
    public function order_receiveOp() {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $order_id = intval($_POST['order_id']);

        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['order_type'] = 1;
        $order_info = $model_order->getOrderInfo($condition);
        $if_allow = $model_order->getOrderOperateState('receive',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }

        $result = $logic_order->changeOrderStateReceive($order_info,'buyer', $this->member_info['member_name'],'签收了货物');
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data('1');
        }
    }

    /**
     * 物流跟踪
     */
    public function search_deliverOp(){
        $order_id   = intval($_POST['order_id']);
        if ($order_id <= 0) {
            output_error('订单不存在');
        }

        $model_order    = Model('order');
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info) || !in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS))) {
            output_error('订单不存在');
        }

        $express = rkcache('express',true);
        $e_code = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
        $e_name = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
        $deliver_info = $this->_get_express($e_code, $order_info['shipping_code']);
        output_data(array('express_name' => $e_name, 'shipping_code' => $order_info['shipping_code'], 'deliver_info' => $deliver_info));
    }

    /**
     * 取得当前的物流最新信息
     */
    public function get_current_deliverOp(){
        $order_id   = intval($_POST['order_id']);
        if ($order_id <= 0) {
            output_error('订单不存在');
        }
    
        $model_order    = Model('order');
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info) || !in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS))) {
            output_error('订单不存在');
        }
    
        $express = rkcache('express',true);
        $e_code = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
        $e_name = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
        $content = Model('express')->get_express($e_code, $order_info['shipping_code']);
        if (empty($content)) {
            output_error('物流信息查询失败');
        } else {
            foreach ($content as $k=>$v) {
                if ($v['time'] == '') continue;
                output_data(array('deliver_info'=>$content[0]));
            }
            output_error('物流信息查询失败');
        }
    }

    /**
     * 从第三方取快递信息
     *
     */
    public function _get_express($e_code, $shipping_code){

        $content = Model('express')->get_express($e_code, $shipping_code);
        if (empty($content)) {
            output_error('物流信息查询失败');
        }
        $output = array();
        foreach ($content as $k=>$v) {
            if ($v['time'] == '') continue;
            $output[]= $v['time'].'&nbsp;&nbsp;'.$v['context'];
        }

        return $output;
    }

    public function order_infoOp() {
        $logic_order = logic('order');
        $result = $logic_order->getMemberOrderInfo($_GET['order_id'],$this->member_info['member_id']);
        if (!$result['state']) {
            output_error($result['msg']);
        }
        $data = array();
        $data['order_id'] = $result['data']['order_info']['order_id'];
        $data['order_sn'] = $result['data']['order_info']['order_sn'];
        $data['store_id'] = $result['data']['order_info']['store_id'];
        $data['store_name'] = $result['data']['order_info']['store_name'];
        $data['add_time'] = date('Y-m-d H:i:s',$result['data']['order_info']['add_time']);
        $data['payment_time'] = $result['data']['order_info']['payment_time'] ? date('Y-m-d H:i:s',$result['data']['order_info']['payment_time']) : '';
        $data['shipping_time'] = $result['data']['order_info']['extend_order_common']['shipping_time'] ? date('Y-m-d H:i:s',$result['data']['order_info']['extend_order_common']['shipping_time']) : '';
        $data['finnshed_time'] = $result['data']['order_info']['finnshed_time'] ? date('Y-m-d H:i:s',$result['data']['order_info']['finnshed_time']): '';
        $data['order_amount'] = ncPriceFormat($result['data']['order_info']['order_amount']);
        $data['shipping_fee'] = ncPriceFormat($result['data']['order_info']['shipping_fee']);
        $data['real_pay_amount'] = ncPriceFormat($result['data']['order_info']['order_amount']);
//         $data['evaluation_state'] = $result['data']['order_info']['evaluation_state'];
//         $data['evaluation_again_state'] = $result['data']['order_info']['evaluation_again_state'];
//         $data['refund_state'] = $result['data']['order_info']['refund_state'];
        $data['state_desc'] = $result['data']['order_info']['state_desc'];
        $data['payment_name'] = $result['data']['order_info']['payment_name'];
        $data['order_message'] = $result['data']['order_info']['extend_order_common']['order_message'];
        $data['reciver_phone'] = $result['data']['order_info']['buyer_phone'];
        $data['reciver_name'] = $result['data']['order_info']['extend_order_common']['reciver_name'];
        $data['reciver_addr'] = $result['data']['order_info']['extend_order_common']['reciver_info']['address'];
        $data['store_member_id'] = $result['data']['order_info']['extend_store']['member_id'];
        $data['store_phone'] = $result['data']['order_info']['extend_store']['store_phone'];
        $data['order_tips'] = $result['data']['order_info']['order_state'] == ORDER_STATE_NEW ? '请于'.ORDER_AUTO_CANCEL_TIME.'小时内完成付款，逾期未付订单自动关闭' : '';
        $_tmp = $result['data']['order_info']['extend_order_common']['invoice_info'];
        $_invonce = '';
        if (is_array($_tmp) && count($_tmp) > 0) {
            foreach ($_tmp as $_k => $_v) {
                $_invonce .= $_k.'：'.$_v.' ';
            }
        }
        $_tmp = $result['data']['order_info']['extend_order_common']['promotion_info'];
        $data['promotion'] = array();
        if(!empty($_tmp)){
            $pinfo = unserialize($_tmp);
            if (is_array($pinfo) && $pinfo){
                foreach ($pinfo as $pk => $pv){
                    if (!is_array($pv) || !is_string($pv[1]) || is_array($pv[1])) {
                        $pinfo = array();
                        break;
                    }
                    $pinfo[$pk][1] = strip_tags($pv[1]);
                }
                $data['promotion'] = $pinfo;
            }
        }
        
        $data['invoice'] = rtrim($_invonce);
        $data['if_deliver'] = $result['data']['order_info']['if_deliver'];
        $data['if_buyer_cancel'] = $result['data']['order_info']['if_buyer_cancel'];
        $data['if_refund_cancel'] = $result['data']['order_info']['if_refund_cancel'];
        $data['if_receive'] = $result['data']['order_info']['if_receive'];
        $data['if_evaluation'] = $result['data']['order_info']['if_evaluation'];
        $data['if_lock'] = $result['data']['order_info']['if_lock'];
        $data['goods_list'] = array();
        foreach ($result['data']['order_info']['goods_list'] as $_k => $_v) {
            $data['goods_list'][$_k]['rec_id'] = $_v['rec_id'];
            $data['goods_list'][$_k]['goods_id'] = $_v['goods_id'];
            $data['goods_list'][$_k]['goods_name'] = $_v['goods_name'];
            $data['goods_list'][$_k]['goods_price'] = ncPriceFormat($_v['goods_price']);
            $data['goods_list'][$_k]['goods_num'] = $_v['goods_num'];
            $data['goods_list'][$_k]['goods_spec'] = $_v['goods_spec'];
            $data['goods_list'][$_k]['image_url'] = $_v['image_240_url'];
            $data['goods_list'][$_k]['refund'] = $_v['refund'];
        }
        $data['zengpin_list'] = array();
        foreach ($result['data']['order_info']['zengpin_list'] as $_k => $_v) {
            $data['zengpin_list'][$_k]['goods_name'] = $_v['goods_name'];
            $data['zengpin_list'][$_k]['goods_num'] = $_v['goods_num'];
        }

        $ownShopIds = Model('store')->getOwnShopIds();
        $data['ownshop'] = in_array($data['store_id'], $ownShopIds);

        output_data(array('order_info'=>$data));
    }

}

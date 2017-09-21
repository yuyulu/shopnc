<?php
/**
 * 会员退货
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class member_returnControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }
    
    /**
     * 发货
     *
     */
    public function ship_formOp(){
        $express_list = array();
        $list = rkcache('express',true);
        foreach($list as $k => $v) {
            $val = array();
            $val['express_id'] = $v['id'];
            $val['express_name'] = $v['e_name'];
            $express_list[] = $val;
        }
        $model_trade = Model('trade');
        $return_delay = $model_trade->getMaxDay('return_delay');//发货默认5天后才能选择没收到
        $return_confirm = $model_trade->getMaxDay('return_confirm');//商家不处理收货时按同意并弃货处理
        
        $return_id = intval($_GET['return_id']);
        $output_data = array('return_id' => $return_id,'return_delay' => $return_delay,'return_confirm' => $return_confirm,'express_list' => $express_list);
        output_data($output_data);
    }
    
    /**
     * 发货保存
     *
     */
    public function ship_postOp(){
        $model_refund = Model('refund_return');
        $member_id = $this->member_info['member_id'];
        $condition = array();
        $condition['buyer_id'] = $member_id;
        $condition['seller_state'] = 2;
        $condition['goods_state'] = 1;
        $condition['refund_id'] = intval($_POST['return_id']);
        $return = $model_refund->getRefundReturnInfo($condition);
        if(!empty($return)) {
            $refund_array = array();
            $refund_array['ship_time'] = time();
            $refund_array['delay_time'] = time();
            $refund_array['express_id'] = $_POST['express_id'];
            $refund_array['invoice_no'] = $_POST['invoice_no'];
            $refund_array['goods_state'] = '2';
            $state = $model_refund->editRefundReturn($condition, $refund_array);
            if ($state) {
                output_data(1);
            } else {
                output_error('退款退货申请,发货保存失败');
            }
        } else {
            output_error('参数错误');
        }
    }
    /**
     * 延迟收货时间
     *
     */
    public function delay_formOp(){
        $model_trade = Model('trade');
        $return_delay = $model_trade->getMaxDay('return_delay');//发货默认5天后才能选择没收到
        $return_confirm = $model_trade->getMaxDay('return_confirm');//商家不处理收货时按同意并弃货处理
        
        $return_id = intval($_GET['return_id']);
        $output_data = array('return_id' => $return_id,'return_delay' => $return_delay,'return_confirm' => $return_confirm);
        output_data($output_data);
    }
    /**
     * 延迟收货时间保存
     *
     */
    public function delay_postOp(){
        $model_refund = Model('refund_return');
        $member_id = $this->member_info['member_id'];
        $condition = array();
        $condition['buyer_id'] = $member_id;
        $condition['seller_state'] = 2;
        $condition['goods_state'] = 3;
        $condition['refund_id'] = intval($_POST['return_id']);
        $return = $model_refund->getRefundReturnInfo($condition);
        if(!empty($return)) {
            $refund_array = array();
            $refund_array['delay_time'] = time();
            $refund_array['goods_state'] = '2';
            $state = $model_refund->editRefundReturn($condition, $refund_array);
            if ($state) {
                output_data(1);
            } else {
                output_error('退款退货申请,延迟收货保存失败');
            }
        } else {
            output_error('参数错误');
        }
    }

    /**
     * 退货记录列表
     */
    public function get_return_listOp() {
        $model_order = Model('order');
        $model_refund = Model('refund_return');
        $member_id = $this->member_info['member_id'];
        $refund_list = array();
        $condition = array();
        $condition['buyer_id'] = $member_id;
        $keyword_type = array('order_sn','refund_sn','goods_name');
        if (trim($_GET['k']) != '' && in_array($_GET['type'],$keyword_type)){
            $type = $_GET['type'];
            $condition[$type] = array('like','%'.$_GET['k'].'%');
        }
        if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != ''){
            $add_time_from = strtotime(trim($_GET['add_time_from']));
            $add_time_to = strtotime(trim($_GET['add_time_to']));
            if ($add_time_from !== false || $add_time_to !== false){
                $condition['add_time'] = array('time',array($add_time_from,$add_time_to));
            }
        }
        $list = $model_refund->getReturnList($condition,$this->page);
        $page_count = $model_refund->gettotalpage();
        if(!empty($list) && is_array($list)) {
            $seller_state = $model_refund->getRefundStateArray('seller');
            $admin_state = $model_refund->getRefundStateArray('admin');
            $rec_id_list = array();
            foreach($list as $k => $v) {
                $rec_id_list[] = $v['order_goods_id'];
            }
            $spec_list = array();
            $goods_list = array();
            $goods_list = $model_order->getOrderGoodsList(array('rec_id'=> array('in',$rec_id_list)));
            foreach($goods_list as $k => $v) {
                $order_goods_id = $v['order_goods_id'];
                $spec_list[$order_goods_id] = $v['goods_spec'];
            }
            foreach($list as $k => $v) {
                $val = array();
                $val['refund_id'] = $v['refund_id'];
                $val['goods_id'] = $v['goods_id'];
                $val['goods_name'] = $v['goods_name'];
                $val['goods_spec'] = $spec_list[$v['order_goods_id']]['goods_spec'];
                $val['goods_num'] = $v['goods_num'];
                $val['goods_state_v'] = $v['goods_state'];//物流状态:1为待发货,2为待收货,3为未收到,4为已收货
                $val['ship_state'] = '0';
                $val['delay_state'] = '0';
                if($v['seller_state'] == 2 && $v['return_type'] == 2 && $v['goods_state'] == 1) {
                    $val['ship_state'] = '1';
                }
                if($v['seller_state'] == 2 && $v['return_type'] == 2 && $v['goods_state'] == 3) {
                    $val['delay_state'] = '1';
                }
                $val['order_id'] = $v['order_id'];
                $val['refund_amount'] = ncPriceFormat($v['refund_amount']);
                $val['refund_sn'] = $v['refund_sn'];
                $val['return_type'] = $v['return_type'];
                $val['order_sn'] = $v['order_sn'];
                $val['add_time'] = date("Y-m-d H:i:s",$v['add_time']);
                $val['goods_img_360'] = thumb($v,360);
                $val['seller_state_v'] = $v['seller_state'];
                $val['seller_state'] = $seller_state[$v['seller_state']];
                $val['admin_state_v'] = $v['refund_state'];
                $val['admin_state'] = $v['seller_state']==2 ? $admin_state[$v['refund_state']]:'无';
                $val['store_id'] = $v['store_id'];
                $val['store_name'] = $v['store_name'];
                $refund_list[] = $val;
            }
        }
        output_data(array('return_list' => $refund_list), mobile_page($page_count));
    }

    /**
     * 查看退货信息
     *
     */
    public function get_return_infoOp(){
        $model_refund = Model('refund_return');
        $member_id = $this->member_info['member_id'];
        $condition = array();
        $condition['buyer_id'] = $member_id;
        $condition['refund_id'] = intval($_GET['return_id']);
        $refund_info = $model_refund->getRefundReturnInfo($condition);
        if(!empty($refund_info) && is_array($refund_info)) {
            $seller_state = $model_refund->getRefundStateArray('seller');
            $admin_state = $model_refund->getRefundStateArray('admin');
            $refund = array();
            $refund['refund_id'] = $refund_info['refund_id'];
            $refund['goods_id'] = $refund_info['goods_id'];
            $refund['goods_name'] = $refund_info['goods_name'];
            $refund['goods_num'] = $refund_info['goods_num'];
            $refund['goods_state_v'] = $refund_info['goods_state'];
            $refund['ship_state'] = '0';
            $refund['delay_state'] = '0';
            if($refund_info['seller_state'] == 2 && $refund_info['return_type'] == 2 && $refund_info['goods_state'] == 1) {
                $refund['ship_state'] = '1';
            }
            if($refund_info['seller_state'] == 2 && $refund_info['return_type'] == 2 && $refund_info['goods_state'] == 3) {
                $refund['delay_state'] = '1';
            }
            $express_list  = rkcache('express',true);
            if ($refund_info['express_id'] > 0 && !empty($refund_info['invoice_no'])) {
                $refund['express_name'] = $express_list[$refund_info['express_id']]['e_name'];
                $refund['invoice_no'] = $refund_info['invoice_no'];
            }
            $refund['order_id'] = $refund_info['order_id'];
            $refund['refund_amount'] = ncPriceFormat($refund_info['refund_amount']);
            $refund['refund_sn'] = $refund_info['refund_sn'];
            $refund['return_type'] = $refund_info['return_type'];
            $refund['order_sn'] = $refund_info['order_sn'];
            $refund['add_time'] = date("Y-m-d H:i:s",$refund_info['add_time']);
            $refund['goods_img_360'] = thumb($refund_info,360);
            $refund['seller_state'] = $seller_state[$refund_info['seller_state']];
            $refund['admin_state'] = $refund_info['seller_state']==2 ? $admin_state[$refund_info['refund_state']]:'无';
            $refund['store_id'] = $refund_info['store_id'];
            $refund['store_name'] = $refund_info['store_name'];
            $refund['reason_info'] = $refund_info['reason_info'];
            $refund['buyer_message'] = $refund_info['buyer_message'];
            $refund['seller_message'] = $refund_info['seller_message'];
            $refund['admin_message'] = $refund_info['admin_message'];
            
            $info['buyer'] = array();
            if(!empty($refund_info['pic_info'])) {
                $info = unserialize($refund_info['pic_info']);
            }
            $pic_list = array();
            if(is_array($info['buyer'])) {
                foreach($info['buyer'] as $k => $v) {
                    if(!empty($v)){
                        $pic_list[] = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$v;
                    }
                }
            }
            
            $detail_info = $model_refund->getDetailInfo(array('refund_id'=> $refund_info['refund_id']));
            $detail_array = array();
            if(!empty($detail_info) && $detail_info['refund_state'] == 2) {
                $detail_array['refund_code'] = orderPaymentName($detail_info['refund_code']);
                $detail_array['pay_amount'] = ncPriceFormat($detail_info['pay_amount']);
                $detail_array['pd_amount'] = ncPriceFormat($detail_info['pd_amount']);
                $detail_array['rcb_amount'] = ncPriceFormat($detail_info['rcb_amount']);
            }
            output_data(array('return_info' => $refund,'pic_list' => $pic_list,'detail_array' => $detail_array));
        } else {
            output_error('参数错误');
        }
    }
}

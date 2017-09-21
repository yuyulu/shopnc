<?php
/**
 * 购买
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_vr_buyControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 虚拟商品购买第一步，设置购买数量
	 * POST
	 * 传入：cart_id:商品ID，quantity:购买数量
	 */
	public function buy_step1Op() {
	    $_POST['goods_id'] = $_POST['cart_id'];

	    $logic_buy_virtual = Logic('buy_virtual');
	    $result = $logic_buy_virtual->getBuyStep2Data($_POST['goods_id'], $_POST['quantity'], $this->member_info['member_id']);
	    if(!$result['state']) {
	        output_error($result['msg']);
	    } else {
	        $result = $result['data'];
	    }
	    unset($result['member_info']);
	    output_data($result);
	}

    /**
     * 虚拟商品购买第二步，设置接收手机号
	 * POST
	 * 传入：goods_id:商品ID，quantity:购买数量
	 */
    public function buy_step2Op() {

        $logic_buy_virtual = Logic('buy_virtual');
        $result = $logic_buy_virtual->getBuyStep2Data($_POST['goods_id'], $_POST['quantity'], $this->member_info['member_id']);
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
	        $result = $result['data'];
            $member_info = array();
            $member_info['member_mobile'] = $result['member_info']['member_mobile'];
            $member_info['available_predeposit'] = $result['member_info']['available_predeposit'];
            $member_info['available_rc_balance'] = $result['member_info']['available_rc_balance'];
            unset($result['member_info']);
            $result['member_info'] = $member_info;
            output_data($result);
        }
    }

    /**
     * 虚拟订单第三步，产生订单
	 * POST
	 * 传入：goods_id:商品ID，quantity:购买数量，buyer_phone：接收手机，buyer_msg:下单留言,pd_pay:是否使用预存款支付0否1是，password：支付密码
	 */
    public function buy_step3Op() {
        $logic_buy_virtual = Logic('buy_virtual');
        $input = array();
        $input['goods_id'] = $_POST['goods_id'];
        $input['quantity'] = $_POST['quantity'];
        $input['buyer_phone'] = $_POST['buyer_phone'];
        $input['buyer_msg'] = $_POST['buyer_msg'];
        //支付密码
        $input['password'] = $_POST['password'];

        //是否使用充值卡支付0是/1否
        $input['rcb_pay'] = intval($_POST['rcb_pay']);

        //是否使用预存款支付0是/1否
        $input['pd_pay'] = intval($_POST['pd_pay']);

        $input['order_from'] = 2;
        $result = $logic_buy_virtual->buyStep3($input,$this->member_info['member_id']);
        if (!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data($result['data']);
        }
    }

    /**
     * 虚拟订单支付(新接口)，返回应付金额和支付方式
     */
    public function payOp() {
        $order_sn   = $_POST['pay_sn'];
        if(!preg_match('/^\d{18}$/',$order_sn)){
            output_error('该订单不存在');
        }

        $model_vr_order = Model('vr_order');
        //取订单信息
        $condition = array();
        $condition['order_sn'] = $order_sn;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_vr_order->getOrderInfo($condition,'*',true);
        if (empty($order_info) || !in_array($order_info['order_state'],array(ORDER_STATE_NEW,ORDER_STATE_PAY))) {
            output_error('该订单不存在');
        }

        //定义输出数组
        $pay = array();
        //应支付金额
        $pay['pay_amount'] = 0;
        //已支付金额(之前支付中止，余额被锁定)
        $pay['payed_amount'] = 0;
        //账户可用金额
        $pay['member_available_pd'] = 0;
        $pay['member_available_rcb'] = 0;

        $logic_order = Logic('order');

        //计算相关支付金额
        $pay['payed_amount'] = $order_info['rcb_amount'] + $order_info['pd_amount'];
        $pay['pay_amount'] = $order_info['order_amount'] - $order_info['rcb_amount'] - $order_info['pd_amount'];
        if (empty($pay['pay_amount'])) {
            output_error('订单重复支付');
        }

        $payment_list = Model('mb_payment')->getMbPaymentOpenList();
        if(!empty($payment_list)) {
            foreach ($payment_list as $k => $value) {
                if ($value['payment_code'] == 'wxpay') {
                    unset($payment_list[$k]);
                    continue;
                }
                unset($payment_list[$k]['payment_id']);
                unset($payment_list[$k]['payment_config']);
                unset($payment_list[$k]['payment_state']);
                unset($payment_list[$k]['payment_state_text']);
            }
        }
        //显示预存款、支付密码、充值卡
        $pay['member_available_pd'] = $this->member_info['available_predeposit'];
        $pay['member_available_rcb'] = $this->member_info['available_rc_balance'];
        $pay['member_paypwd'] = $this->member_info['member_paypwd'] ? true : false;
//         $pay['order_sn'] = $order_sn;
        $pay['payment_list'] = $payment_list ? array_values($payment_list) : array();
        output_data(array('pay_info'=>$pay));
    }
}



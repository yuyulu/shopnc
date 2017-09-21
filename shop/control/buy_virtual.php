<?php
/**
 * 虚拟商品购买
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class buy_virtualControl extends BaseBuyControl {

    public function __construct() {
        parent::__construct();
        Language::read('home_cart_index');
        if (!$_SESSION['member_id']){
            redirect(urlLogin('login', 'index', array('ref_url' => request_uri())));
        }
        //验证该会员是否禁止购买
        if(!$_SESSION['is_buy']){
            showMessage(Language::get('cart_buy_noallow'),'','html','error');
        }
        Tpl::output('hidden_rtoolbar_cart', 1);
    }

    /**
     * 虚拟商品购买第一步
     */
    public function buy_step1Op() {
        $logic_buy_virtual = Logic('buy_virtual');
        $result = $logic_buy_virtual->getBuyStep1Data($_GET['goods_id'], $_GET['quantity'], $_SESSION['member_id']);
        if (!$result['state']) {
            showMessage($result['msg'], '', 'html', 'error');
        }
        //标识购买流程执行步骤
        Tpl::output('buy_step','step1');

        Tpl::output('goods_info',$result['data']['goods_info']);
        Tpl::output('store_info',$result['data']['store_info']);

        Tpl::showpage('buy_virtual_step1');
    }

    /**
     * 虚拟商品购买第二步
     */
    public function buy_step2Op() {
        $logic_buy_virtual = Logic('buy_virtual');
        $result = $logic_buy_virtual->getBuyStep2Data($_POST['goods_id'], $_POST['quantity'], $_SESSION['member_id']);
        if (!$result['state']) {
            showMessage($result['msg'], '', 'html', 'error');
        }

        //处理会员信息
        $member_info = array_merge($this->member_info,$result['data']['member_info']);

        //标识购买流程执行步骤
        Tpl::output('buy_step','step2');
        Tpl::output('goods_info',$result['data']['goods_info']);
        Tpl::output('store_info',$result['data']['store_info']);
        Tpl::output('member_info',$member_info);
        Tpl::showpage('buy_virtual_step2');
    }

    /**
     * 虚拟商品购买第三步
     */
    public function buy_step3Op() {
        $logic_buy_virtual = Logic('buy_virtual');
        $_POST['order_from'] = 1;
        $result = $logic_buy_virtual->buyStep3($_POST,$_SESSION['member_id']);
        if (!$result['state']) {
            showMessage($result['msg'], 'index.php', 'html', 'error');
        }
        //转向到商城支付页面
        redirect('index.php?act=buy_virtual&op=pay&order_id='.$result['data']['order_id']);
    }

    /**
     * 下单时支付页面
     */
    public function payOp() {
        $order_id   = intval($_GET['order_id']);
        if ($order_id <= 0){
            showMessage('该订单不存在','index.php?act=member_vr_order','html','error');
        }

        $model_vr_order = Model('vr_order');
        //取订单信息
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $_SESSION['member_id'];
        $order_info = $model_vr_order->getOrderInfo($condition,'*',true);
        if (empty($order_info) || !in_array($order_info['order_state'],array(ORDER_STATE_NEW,ORDER_STATE_PAY))) {
            showMessage('未找到需要支付的订单','index.php?act=member_order','html','error');
        }

        //定义输出数组
        $pay = array();
        //订单总支付金额
        $pay['pay_amount_online'] = 0;
        //充值卡支付金额(之前支付中止，余额被锁定)
        $pay['payd_rcb_amount'] = 0;
        //预存款支付金额(之前支付中止，余额被锁定)
        $pay['payd_pd_amount'] = 0;
        //还需在线支付金额(之前支付中止，余额被锁定)
        $pay['payd_diff_amount'] = 0;
        //账户可用金额
        $pay['member_pd'] = 0;
        $pay['member_rcb'] = 0;

        $pay['pay_amount_online'] = floatval($order_info['order_amount']);
        $pay['payd_rcb_amount'] = floatval($order_info['rcb_amount']);
        $pay['payd_pd_amount'] = floatval($order_info['pd_amount']);
        $pay['payd_diff_amount'] = $order_info['order_amount'] - $order_info['rcb_amount'] - $order_info['pd_amount'];

        Tpl::output('order_info',$order_info);

        //如果所需支付金额为0，转到支付成功页
        if ($pay['payd_diff_amount'] == 0) {
            redirect('index.php?act=buy_virtual&op=pay_ok&order_sn='.$order_info['order_sn'].'&order_id='.$order_info['order_id'].'&order_amount='.ncPriceFormat($order_info['order_amount']));
        }

        //是否显示站内余额操作(如果以前没有使用站内余额支付过且非货到付款)
        $pay['if_show_pdrcb_select'] = ($pay['payd_rcb_amount'] == 0 && $pay['payd_pd_amount'] == 0);
        
        //显示支付接口列表
        $model_payment = Model('payment');
        $condition = array();
        $payment_list = $model_payment->getPaymentOpenList($condition);
        if (!empty($payment_list)) {
            unset($payment_list['predeposit']);
            unset($payment_list['offline']);
        }
        if (empty($payment_list)) {
            showMessage('暂未找到合适的支付方式','index.php?act=member_vr_order','html','error');
        }
        Tpl::output('payment_list',$payment_list);

        if ($pay['if_show_pdrcb_select']) {
            //显示预存款、支付密码、充值卡
            $available_predeposit = $available_rc_balance = 0;
            $buyer_info = Model('member')->getMemberInfoByID($_SESSION['member_id']);
            if (floatval($buyer_info['available_predeposit']) > 0) {
                $pay['member_pd'] = $buyer_info['available_predeposit'];
            }
            if (floatval($buyer_info['available_rc_balance']) > 0) {
                $pay['member_rcb'] = $buyer_info['available_rc_balance'];
            }
            $pay['member_paypwd'] = $buyer_info['member_paypwd'] ? true : false;
        }
        //标识购买流程执行步骤
        Tpl::output('buy_step','step3');
        
        Tpl::output('pay',$pay);

        Tpl::showpage('buy_virtual_step3');
    }

    /**
     * 支付成功页面
     */
    public function pay_okOp() {
        $order_sn   = $_GET['order_sn'];
        if (!preg_match('/^\d{18}$/',$order_sn)){
            showMessage('该订单不存在','index.php?act=member_vr_order','html','error');
        }
        Tpl::showpage('buy_virtual_step4');
    }
}

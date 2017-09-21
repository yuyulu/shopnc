<?php
/**
 * 支付行为
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');
class paymentLogic {

    /**
     * 取得实物订单所需支付金额等信息
     * @param int $pay_sn
     * @param int $member_id
     * @return array
     */
    public function getRealOrderInfo($pay_sn, $member_id = null) {

        //验证订单信息
        $model_order = Model('order');
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        if (!empty($member_id)) {
            $condition['buyer_id'] = $member_id;
        }
        $order_pay_info = $model_order->getOrderPayInfo($condition,true);
        if(empty($order_pay_info)){
            return callback(false,'该支付单不存在');
        }

        $order_pay_info['subject'] = '实物订单_'.$order_pay_info['pay_sn'];
        $order_pay_info['order_type'] = 'real_order';

        $condition = array();
        $condition['pay_sn'] = $pay_sn;

        //同步异步通知时,预定支付尾款时需要用到已经支付状态
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order_list = $model_order->getOrderList($condition,'','*','','',array(),true);

        //取订单其它扩展信息
        $result = $this->getOrderExtendList($order_list);
        if (!$result['state']) {
            return $result;
        }

        $order_pay_info['order_list'] = $order_list;
        $order_pay_info['if_buyer_repay'] = $result['data']['if_buyer_repay'];

        return callback(true,'',$order_pay_info);
    }

    /**
     * 取得订单其它扩展信息
     * @param unknown $order_list
     * @param string $role 操作角色 目前只用admin时需要传入
     */
    public function getOrderExtendList(& $order_list,$role = '') {

        //预定订单
        if ($order_list[0]['order_type'] == 2) {
            //原值需要记录一下[后面会被清空]，最后订单表需要记录再次支付的总额，因为退款会用到 2015/07/09
            $order_list[0]['original_pd_amount'] = $order_list[0]['pd_amount'];
            $order_list[0]['original_rcb_amount'] = $order_list[0]['rcb_amount'];

            $order_info = $order_list[0];
            $result = Logic('order_book')->getOrderBookInfo($order_info);
            if (!$result['data']['if_buyer_pay'] && $role != 'admin') {
                return callback(false,'未找到需要支付的订单');
            }
            $order_list[0] = $result['data'];
            $order_list[0]['order_amount'] = $order_list[0]['pay_amount'];
            
            //如果是支付尾款，则把订单状态更改为未支付状态，方便执行统一支付程序
            if ($result['data']['if_buyer_repay']) {
                $order_list[0]['order_state'] = ORDER_STATE_NEW;
            }

            //当以下情况时不需要清除数据pd_amount,rcb_amount：
            //如果第2次支付尾款，并且已经锁定了站内款
            //当以下情形时清除站内余额数据pd_amount,rcb_amount：
            //如果第1次支付，两个均为空，如果第1.5次支付，不会POST扣款标识不会重复扣站内款，不需要该值，所以可以清空
            //如果第2次支付尾款，如果第一次选择站内支付，也需要清空原来的支付定金的金额
            if (!$order_list[0]['if_buyer_pay_lock']) {
                $order_list[0]['pd_amount'] = $order_list[0]['rcb_amount'] = 0;
            }
        }
        return callback(true);
    }

    /**
     * 取得虚拟订单所需支付金额等信息
     * @param int $order_sn
     * @param int $member_id
     * @return array
     */
    public function getVrOrderInfo($order_sn, $member_id = null) {

        //验证订单信息
        $model_order = Model('vr_order');
        $condition = array();
        $condition['order_sn'] = $order_sn;
        if (!empty($member_id)) {
            $condition['buyer_id'] = $member_id;
        }
        //同步异步通知时需要用到已经支付状态
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order_info = $model_order->getOrderInfo($condition);
        if(empty($order_info)){
            return callback(false,'该订单不存在');
        }

        $order_info['subject'] = '虚拟订单_'.$order_sn;
        $order_info['order_type'] = 'vr_order';
        $order_info['pay_sn'] = $order_sn;

        return callback(true,'',$order_info);
    }

    /**
     * 取得充值单所需支付金额等信息
     * @param int $pdr_sn
     * @param int $member_id
     * @return array
     */
    public function getPdOrderInfo($pdr_sn, $member_id = null) {

        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_sn'] = $pdr_sn;
        if (!empty($member_id)) {
            $condition['pdr_member_id'] = $member_id;
        }
        $order_info = $model_pd->getPdRechargeInfo($condition);
        if(empty($order_info)){
            return callback(false,'该订单不存在');
        }

        $order_info['subject'] = '预存款充值_'.$order_info['pdr_sn'];
        $order_info['order_type'] = 'pd_order';
        $order_info['pay_sn'] = $order_info['pdr_sn'];
        $order_info['api_pay_amount'] = $order_info['pdr_amount'];
        return callback(true,'',$order_info);
    }

    /**
     * 取得所使用支付方式信息
     * @param unknown $payment_code
     */
    public function getPaymentInfo($payment_code) {
        if (in_array($payment_code,array('offline','predeposit')) || empty($payment_code)) {
            return callback(false,'系统不支持选定的支付方式');
        }
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
        if(empty($payment_info)) {
            return callback(false,'系统不支持选定的支付方式');
        }

        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
        if(!file_exists($inc_file)){
            return callback(false,'系统不支持选定的支付方式');
        }
        require_once($inc_file);
        $payment_info['payment_config'] = unserialize($payment_info['payment_config']);

        return callback(true,'',$payment_info);
    }

    /**
     * 支付成功后修改实物订单状态
     */
    public function updateRealOrder($out_trade_no, $payment_code, $order_list, $trade_no) {
        $post['payment_code'] = $payment_code;
        $post['trade_no'] = $trade_no;
        return Logic('order')->changeOrderReceivePay($order_list, 'system', '系统', $post);
    }

    /**
     * 支付成功后修改虚拟订单状态
     */
    public function updateVrOrder($out_trade_no, $payment_code, $order_info, $trade_no) {
        $post['payment_code'] = $payment_code;
        $post['trade_no'] = $trade_no;
        return Logic('vr_order')->changeOrderStatePay($order_info, 'system', $post);
    }

    /**
     * 支付成功后修改充值订单状态
     * @param unknown $out_trade_no
     * @param unknown $trade_no
     * @param unknown $payment_info
     * @throws Exception
     * @return multitype:unknown
     */
    public function updatePdOrder($out_trade_no,$trade_no,$payment_info,$recharge_info) {
		$model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_sn'] = $recharge_info['pdr_sn'];
        $condition['pdr_payment_state'] = 0;
        $pd_order_info = $model_pd->getPdRechargeInfo($condition);
        if (empty($pd_order_info)) {
            return callback(true);
        }
        try {
            $model_pd->beginTransaction();
            $pd_order_info = $model_pd->getPdRechargeInfo(array('pdr_id'=>$pd_order_info['pdr_id']),'*',true);
            if ($pd_order_info['pdr_payment_state'] == 1) {
                return callback(true);
            }
            $update = array();
            $update['pdr_payment_state'] = 1;
            $update['pdr_payment_time'] = TIMESTAMP;
            $update['pdr_payment_code'] = $payment_info['payment_code'];
            $update['pdr_payment_name'] = $payment_info['payment_name'];
            $update['pdr_trade_sn'] = $trade_no;

            //更改充值状态
            $state = $model_pd->editPdRecharge($update,$condition);
            if (!$state) {
                throw new Exception('更新充值状态失败');
            }
            //变更会员预存款
            $data = array();
            $data['member_id'] = $recharge_info['pdr_member_id'];
            $data['member_name'] = $recharge_info['pdr_member_name'];
            $data['amount'] = $recharge_info['pdr_amount'];
            $data['pdr_sn'] = $recharge_info['pdr_sn'];
            $model_pd->changePd('recharge',$data);
            $model_pd->commit();
            return callback(true);

        } catch (Exception $e) {
            $model_pd->rollback();
            return callback(false,$e->getMessage());
        }
    }
}

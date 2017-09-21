<?php
/**
 * 虚拟兑码退款模型
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */

defined('In33hao') or exit('Access Invalid!');

class vr_refundModel extends Model{

    public function __construct() {
        parent::__construct();
    }

    /**
     * 增加退款
     *
     * @param
     * @return int
     */
    public function addRefund($refund_array, $order = array()) {
        if (!empty($order) && is_array($order)) {
            $refund_array['order_id'] = $order['order_id'];
            $refund_array['order_sn'] = $order['order_sn'];
            $refund_array['store_id'] = $order['store_id'];
            $refund_array['store_name'] = $order['store_name'];
            $refund_array['buyer_id'] = $order['buyer_id'];
            $refund_array['buyer_name'] = $order['buyer_name'];
            $refund_array['goods_id'] = $order['goods_id'];
            $refund_array['goods_name'] = $order['goods_name'];
            $refund_array['goods_image'] = $order['goods_image'];
            $refund_array['commis_rate'] = $order['commis_rate'];
        }
        $refund_array['refund_sn'] = $this->getRefundsn($refund_array['store_id']);

        try {
            $this->beginTransaction();
            $refund_id = $this->table('vr_refund')->insert($refund_array);
            $code_array = explode(',', $refund_array['code_sn']);
            $model_vr_order = Model('vr_order');
            $model_vr_order->editOrderCode(array('refund_lock'=> 1),array('vr_code'=> array('in',$code_array)));//退款锁定
            $this->commit();
            return $refund_id;
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }
    }

    /**
     * 平台退款处理
     *
     * @param
     * @return bool
     */
    public function editOrderRefund($refund) {
        $refund_id = $refund['refund_id'];
        $refund_lock = '0';//退款锁定状态:0为正常,1为锁定,2为同意
        $model_vr_order = Model('vr_order');
        try {
            $this->beginTransaction();
            $refund_array = array();
            $refund_array['admin_time'] = $refund['admin_time'];
            $refund_array['admin_state'] = $refund['admin_state'];
            $refund_array['admin_message'] = $refund['admin_message'];
            $state = $this->editRefund(array('refund_id'=> $refund_id),$refund_array);////更新退款
            if ($state && $refund['admin_state'] == '2') {//审核状态:1为待审核,2为同意,3为不同意
                $refund_lock = '2';
                $order_id = $refund['order_id'];//订单编号
                $order = $model_vr_order->getOrderInfo(array('order_id'=> $order_id));

                $order_amount = $order['order_amount'];//订单金额
                $rcb_amount = $order['rcb_amount'];//充值卡支付金额
                $predeposit_amount = $order_amount-$order['refund_amount']-$rcb_amount;//可退预存款金额
                $detail_array = array();

                $model_predeposit = Model('predeposit');
                if (($rcb_amount > 0) && ($refund['refund_amount'] > $predeposit_amount)) {//退充值卡
                    $log_array = array();
                    $log_array['member_id'] = $order['buyer_id'];
                    $log_array['member_name'] = $order['buyer_name'];
                    $log_array['order_sn'] = $order['order_sn'];
                    $log_array['amount'] = $refund['refund_amount'];
                    if ($predeposit_amount > 0) {
                        $log_array['amount'] = $refund['refund_amount']-$predeposit_amount;
                    }
                    $detail_array['rcb_amount'] = $log_array['amount'];
                    $state = $model_predeposit->changeRcb('refund', $log_array);//增加买家可用充值卡金额
                }
                if ($predeposit_amount > 0) {//退预存款
                    $log_array = array();
                    $log_array['member_id'] = $order['buyer_id'];
                    $log_array['member_name'] = $order['buyer_name'];
                    $log_array['order_sn'] = $order['order_sn'];
                    $log_array['amount'] = $refund['refund_amount'];//退预存款金额
                    if ($refund['refund_amount'] > $predeposit_amount) {
                        $log_array['amount'] = $predeposit_amount;
                    }
                    $pay_amount = floatval($refund['pay_amount']);//已完成在线退款金额
                    if ($pay_amount > 0) {
                        $log_array['amount'] -= $pay_amount;
                    }
                    if ($log_array['amount'] > 0) {
                        $detail_array['pd_amount'] = $log_array['amount'];
                        $state = $model_predeposit->changePd('refund', $log_array);//增加买家可用预存款金额
                    }
                }
                if ($state) {
                    $detail_array['refund_state'] = '2';
                    $this->editDetail(array('refund_id'=> $refund_id), $detail_array);//更新退款详细
                    $order_array = array();
                    $order_amount = $order['order_amount'];//订单金额
                    $refund_amount = $order['refund_amount']+$refund['refund_amount'];//退款金额
                    $order_array['refund_state'] = ($order_amount-$refund_amount) > 0 ? 1:2;
                    $order_array['refund_amount'] = ncPriceFormat($refund_amount);
                    $state = $model_vr_order->editOrder($order_array,array('order_id'=> $order_id));//更新订单退款
                }
            }
            if ($state) {
                $code_array = explode(',', $refund['code_sn']);
                $state = $model_vr_order->editOrderCode(array('refund_lock'=> $refund_lock),array('vr_code'=> array('in',$code_array)));//更新退款的兑换码
                if ($state && $refund['admin_state'] == '2') {
                    Logic('vr_order')->changeOrderStateSuccess($order_id);//更新订单状态
                }
            }
            $this->commit();
            return $state;
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }
    }

    /**
     * 增加退款详细
     *
     * @param
     * @return int
     */
    public function addDetail($refund,$order) {
        $detail_array = array();
        $detail_array['refund_id'] = $refund['refund_id'];
        $detail_array['order_id'] = $refund['order_id'];
        $detail_array['batch_no'] = date('YmdHis').$refund['refund_id'];//批次号。支付宝要求格式为：当天退款日期+流水号。
        $detail_array['refund_amount'] = ncPriceFormat($refund['refund_amount']);
        $detail_array['refund_code'] = 'predeposit';
        $detail_array['refund_state'] = '1';
        $detail_array['add_time'] = time();
        if (!empty($order['trade_no']) && in_array($order['payment_code'],array('wxpay','wx_jsapi','wx_saoma'))) {//微信支付
            $api_file = BASE_PATH.DS.'api'.DS.'refund'.DS.'wxpay'.DS.'WxPay.Config.php';
            if ($order['payment_code'] == 'wxpay') {
                $api_file = BASE_PATH.DS.'api'.DS.'refund'.DS.'wxpay'.DS.'WxPayApp.Config.php';
            }
            include $api_file;
            $apiclient_cert = WxPayConfig::SSLCERT_PATH;
            $apiclient_key = WxPayConfig::SSLKEY_PATH;
            if (!empty($apiclient_cert) && !empty($apiclient_key)) {//验证商户证书路径设置
                $detail_array['refund_code'] = $order['payment_code'];
            }
        }
        if (!empty($order['trade_no']) && $order['payment_code'] == 'alipay') {//支付宝
            $detail_array['refund_code'] = 'alipay';
        }
        $result = $this->table('vr_refund_detail')->insert($detail_array);
        return $result;
    }

    /**
     * 修改退款
     *
     * @param
     * @return bool
     */
    public function editRefund($condition, $data) {
        if (empty($condition)) {
            return false;
        }
        if (is_array($data)) {
            $result = $this->table('vr_refund')->where($condition)->update($data);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 修改退款详细记录
     *
     * @param
     * @return bool
     */
    public function editDetail($condition, $data) {
        if (empty($condition)) {
            return false;
        }
        if (is_array($data)) {
            $result = $this->table('vr_refund_detail')->where($condition)->update($data);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 退款编号
     *
     * @param
     * @return array
     */
    public function getRefundsn($store_id) {
        $result = mt_rand(100,999).substr(500+$store_id,-3).date('ymdHis');
        return $result;
    }

    /**
     * 退款详细记录
     *
     * @param
     * @return array
     */
    public function getDetailInfo($condition = array(), $fields = '*') {
        return $this->table('vr_refund_detail')->where($condition)->field($fields)->find();
    }

    /**
     * 订单在线退款计算
     *
     * @param
     * @return array
     */
    public function getPayDetailInfo($detail_array) {
        $condition = array();
        $condition['order_id'] = $detail_array['order_id'];
        $model_order = Model('vr_order');
        $order = $model_order->getOrderInfo($condition);//订单详细
        $order['pay_amount'] = ncPriceFormat($order['order_amount']-$order['rcb_amount']-$order['pd_amount']);//在线支付金额=订单总价格-充值卡支付金额-预存款支付金额
        $out_amount = $order['pay_amount']-$order['refund_amount'];//可在线退款金额
        
        $refund_amount = $detail_array['refund_amount'];//本次退款总金额
        if ($refund_amount > $out_amount) {
            $refund_amount = $out_amount;
        }
        $order['pay_refund_amount'] = ncPriceFormat($refund_amount);
        $condition = array();
        $condition['payment_code'] = $order['payment_code'];
        if(in_array($order['payment_code'],array('wxpay','wx_jsapi'))) {//手机客户端微信支付
            if($order['payment_code'] == 'wx_jsapi') {
                $condition['payment_code'] = 'wxpay_jsapi';
            }
            $model_payment = Model('mb_payment');
            $payment_info = $model_payment->getMbPaymentInfo($condition);//接口参数
            $payment_info = $payment_info['payment_config'];
            if($order['payment_code'] == 'wxpay') {
                $payment_config['appid'] = $payment_info['wxpay_appid'];
                $payment_config['mchid'] = $payment_info['wxpay_partnerid'];
                $payment_config['key'] = $payment_info['wxpay_partnerkey'];
            }
            if($order['payment_code'] == 'wx_jsapi') {
                $payment_config['appid'] = $payment_info['appId'];
                $payment_config['mchid'] = $payment_info['partnerId'];
                $payment_config['key'] = $payment_info['apiKey'];
            }
        } else {
            if($order['payment_code'] == 'wx_saoma') {
                $condition['payment_code'] = 'wxpay';
            }
            $model_payment = Model('payment');
            $payment_info = $model_payment->getPaymentInfo($condition);//接口参数
            $payment_config = unserialize($payment_info['payment_config']);
        }
        $order['payment_config'] = $payment_config;
        return $order;
    }

    /**
     * 单条退款记录
     *
     * @param
     * @return array
     */
    public function getRefundInfo($condition = array()) {
        return $this->table('vr_refund')->where($condition)->field($fields)->find();
    }

    /**
     * 退款记录
     *
     * @param
     * @return array
     */
    public function getRefundList($condition = array(), $page = '', $limit = '', $fields = '*',$order = 'refund_id desc') {
        $result = $this->table('vr_refund')->field($fields)->where($condition)->page($page)->limit($limit)->order($order)->select();
        return $result;
    }

    /**
     * 取得退款记录的数量
     * @param array $condition
     */
    public function getRefundCount($condition) {
        $result = $this->table('vr_refund')->where($condition)->count();
        return $result;
    }

    /**
     * 详细页右侧订单信息
     *
     * @param
     * @return array
     */
    public function getRightOrderList($order_condition){
        $order_id = $order_condition['order_id'];
        $model_vr_order = Model('vr_order');
        $order_info = $model_vr_order->getOrderInfo($order_condition);
        Tpl::output('order',$order_info);
        $order_list = array();
        $order_list[$order_id] = $order_info;
        $order_list = $model_vr_order->getCodeRefundList($order_list);//没有使用的兑换码列表
        $order_info = $order_list[$order_id];
        $model_store = Model('store');
        $store = $model_store->getStoreInfo(array('store_id' => $order_info['store_id']));
        Tpl::output('store',$store);

        //显示退款
        $order_info['if_refund'] = $model_vr_order->getOrderOperateState('refund',$order_info);

        if ($order_info['if_refund']) {
            $code_list = $order_info['code_list'];
            Tpl::output('code_list',$code_list);
        }
        return $order_info;
    }

    /*
     *  获得退款的店铺列表
     *  @param array $complain_list
     *  @return array
     */
    public function getRefundStoreList($list) {
        $store_ids = array();
        if (!empty($list) && is_array($list)) {
            foreach ($list as $key => $value) {
                $store_ids[] = $value['store_id'];//店铺编号
            }
        }
        $field = 'store_id,store_name,member_id,member_name,seller_name,store_company_name,store_qq,store_ww,store_phone,store_domain';
        return Model('store')->getStoreMemberIDList($store_ids, $field);
    }

    /**
     * 向模板页面输出退款状态
     *
     * @param
     * @return array
     */
    public function getRefundStateArray($type = 'all') {
        $admin_array = array(
            '1' => '待审核',
            '2' => '同意',
            '3' => '不同意'
            );//退款状态:1为待审核,2为同意,3为不同意
        Tpl::output('admin_array', $admin_array);

        $state_data = array(
            'admin' => $admin_array
            );
        if ($type == 'all') return $state_data;//返回所有
        return $state_data[$type];
    }
}

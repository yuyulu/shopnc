<?php
/**
 * 支付
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_paymentControl extends mobileMemberControl {

    private $payment_code;
    private $payment_config;

    public function __construct() {
        parent::__construct();

        if($_GET['op'] != 'payment_list' && !$_POST['payment_code']) {
            $payment_code = 'alipay';

            if(in_array($_GET['op'], array('wx_app_pay', 'wx_app_pay3', 'wx_app_vr_pay', 'wx_app_vr_pay3'), true)) {
                $payment_code = 'wxpay';
            }
            else if (in_array($_GET['op'],array('alipay_native_pay','alipay_native_vr_pay'),true)) {
                $payment_code = 'alipay_native';
            }
            else if (isset($_GET['payment_code'])) {
                $payment_code = $_GET['payment_code'];
            }

            $model_mb_payment = Model('mb_payment');
            $condition = array();
            $condition['payment_code'] = $payment_code;
            $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
            if(!$mb_payment_info) {
                output_error('支付方式未开启');
            }

            $this->payment_code = $payment_code;
            $this->payment_config = $mb_payment_info['payment_config'];

        }
    }

    /**
     * 实物订单支付 新方法
     */
    public function pay_newOp() {
        @header("Content-type: text/html; charset=".CHARSET);
        $pay_sn = $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            exit('支付单号错误');
        }
        if (in_array($_GET['payment_code'],array('alipay','wxpay_jsapi'))) {
            $model_mb_payment = Model('mb_payment');
            $condition = array();
            $condition['payment_code'] = $_GET['payment_code'];
            $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
            if(!$mb_payment_info) {
                exit('支付方式未开启');
            }

            $this->payment_code = $_GET['payment_code'];
            $this->payment_config = $mb_payment_info['payment_config'];            
        } else {
            exit('支付方式提交错误');
        }

        $pay_info = $this->_get_real_order_info($pay_sn,$_GET);
        if(isset($pay_info['error'])) {
            exit($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);

    }

    /**
     * 虚拟订单支付 新方法
     */
    public function vr_pay_newOp() {
        @header("Content-type: text/html; charset=".CHARSET);
        $order_sn = $_GET['pay_sn'];
        if(!preg_match('/^\d{18}$/',$order_sn)){
            exit('订单号错误');
        }
        if (in_array($_GET['payment_code'],array('alipay','wxpay_jsapi'))) {
            $model_mb_payment = Model('mb_payment');
            $condition = array();
            $condition['payment_code'] = $_GET['payment_code'];
            $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
            if(!$mb_payment_info) {
                exit('支付方式未开启');
            }

            $this->payment_code = $_GET['payment_code'];
            $this->payment_config = $mb_payment_info['payment_config'];
        } else {
            exit('支付方式提交错误');
        }

        $pay_info = $this->_get_vr_order_info($order_sn,$_GET);
        if(isset($pay_info['error'])) {
            exit($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);
    
    }

    /**
     * 站内余额支付(充值卡、预存款支付) 实物订单
     *
     */
    private function _pd_pay($order_list, $post) {
        if (empty($post['password'])) {
            return $order_list;
        }
        $model_member = Model('member');
        $buyer_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_list;
        }
    
        if ($buyer_info['available_rc_balance'] == 0) {
            $post['rcb_pay'] = null;
        }
        if ($buyer_info['available_predeposit'] == 0) {
            $post['pd_pay'] = null;
        }
        if (floatval($order_list[0]['rcb_amount']) > 0 || floatval($order_list[0]['pd_amount']) > 0) {
            return $order_list;
        }
    
        try {
            $model_member->beginTransaction();
            $logic_buy_1 = Logic('buy_1');
            //使用充值卡支付
            if (!empty($post['rcb_pay'])) {
                $order_list = $logic_buy_1->rcbPay($order_list, $post, $buyer_info);
            }
    
            //使用预存款支付
            if (!empty($post['pd_pay'])) {
                $order_list = $logic_buy_1->pdPay($order_list, $post, $buyer_info);
            }
    
            //特殊订单站内支付处理
            $logic_buy_1->extendInPay($order_list);
    
            $model_member->commit();
        } catch (Exception $e) {
            $model_member->rollback();
            exit($e->getMessage());
        }
    
        return $order_list;
    }

    /**
     * 站内余额支付(充值卡、预存款支付) 虚拟订单
     *
     */
    private function _pd_vr_pay($order_info, $post) {
        if (empty($post['password'])) {
            return $order_info;
        }
        $model_member = Model('member');
        $buyer_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_info;
        }
        if ($buyer_info['available_rc_balance'] == 0) {
            $post['rcb_pay'] = null;
        }
        if ($buyer_info['available_predeposit'] == 0) {
            $post['pd_pay'] = null;
        }
        if (floatval($order_info['rcb_amount']) > 0 || floatval($order_info['pd_amount']) > 0) {
            return $order_info;
        }

        try {
            $model_member->beginTransaction();
            $logic_buy = Logic('buy_virtual');
            //使用充值卡支付
            if (!empty($post['rcb_pay'])) {
                $order_info = $logic_buy->rcbPay($order_info, $post, $buyer_info);
            }
    
            //使用预存款支付
            if (!empty($post['pd_pay'])) {
                $order_info = $logic_buy->pdPay($order_info, $post, $buyer_info);
            }
    
            $model_member->commit();
        } catch (Exception $e) {
            $model_member->rollback();
            exit($e->getMessage());
        }
    
        return $order_info;
    }

    /**
     * 实物订单支付
     */
    public function payOp() {
        $pay_sn = $_GET['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);
    }

    /**
     * 虚拟订单支付
     */
    public function vr_payOp() {
        $pay_sn = $_GET['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);
    }

    /**
     * 第三方在线支付接口
     *
     */
    private function _api_pay($order_pay_info) {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
        if(!is_file($inc_file)){
            exit('支付接口不存在');
        }
        require($inc_file);
        $param = $this->payment_config;

        // wxpay_jsapi
        if ($this->payment_code == 'wxpay_jsapi') {
            $param['orderSn'] = $order_pay_info['pay_sn'];
            $param['orderFee'] = (int) (100 * $order_pay_info['api_pay_amount']);
            $param['orderInfo'] = C('site_name') . '商品订单' . $order_pay_info['pay_sn'];
            $param['orderAttach'] = ($order_pay_info['order_type'] == 'real_order' ? 'r' : 'v');
            $api = new wxpay_jsapi();
            $api->setConfigs($param);
            try {
                echo $api->paymentHtml($this);
            } catch (Exception $ex) {
                if (C('debug')) {
                    header('Content-type: text/plain; charset=utf-8');
                    echo $ex, PHP_EOL;
                } else {
                    Tpl::output('msg', $ex->getMessage());
                    Tpl::showpage('payment_result');
                }
            }
            exit;
        }

        $param['order_sn'] = $order_pay_info['pay_sn'];
        $param['order_amount'] = $order_pay_info['api_pay_amount'];
        $param['order_type'] = ($order_pay_info['order_type'] == 'real_order' ? 'r' : 'v');
        $payment_api = new $this->payment_code();
        $return = $payment_api->submit($param);
        echo $return;
        exit;
    }

    /**
     * 获取订单支付信息
     */
    private function _get_real_order_info($pay_sn,$rcb_pd_pay = array()) {
        $logic_payment = Logic('payment');

        //取订单信息
        $result = $logic_payment->getRealOrderInfo($pay_sn, $this->member_info['member_id']);
        if(!$result['state']) {
            return array('error' => $result['msg']);
        }

        //站内余额支付
        if ($rcb_pd_pay) {
            $result['data']['order_list'] = $this->_pd_pay($result['data']['order_list'],$rcb_pd_pay);
        }

        //计算本次需要在线支付的订单总金额
        $pay_amount = 0;
        $pay_order_id_list = array();
        if (!empty($result['data']['order_list'])) {
            foreach ($result['data']['order_list'] as $order_info) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                    $pay_order_id_list[] = $order_info['order_id'];
                }
            }
        }

        if ($pay_amount == 0) {
            redirect(WAP_SITE_URL.'/tmpl/member/order_list.html');
        }

        $result['data']['api_pay_amount'] = ncPriceFormat($pay_amount);

        $update = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>array('in',$pay_order_id_list)));
        if(!$update) {
            return array('error' => '更新订单信息发生错误，请重新支付');
        }

        //如果是开始支付尾款，则把支付单表重置了未支付状态，因为支付接口通知时需要判断这个状态
        if ($result['data']['if_buyer_repay']) {
            $update = Model('order')->editOrderPay(array('api_pay_state'=>0),array('pay_id'=>$result['data']['pay_id']));
            if (!$update) {
                return array('error' => '订单支付失败');
            }
            $result['data']['api_pay_state'] = 0;
        }

        return $result;
    }

    /**
     * 获取虚拟订单支付信息
     */
    private function _get_vr_order_info($pay_sn,$rcb_pd_pay = array()) {
        $logic_payment = Logic('payment');

        //取得订单信息
        $result = $logic_payment->getVrOrderInfo($pay_sn, $this->member_info['member_id']);
        if(!$result['state']) {
            output_error($result['msg']);
        }

        //站内余额支付
        if ($rcb_pd_pay) {
            $result['data'] = $this->_pd_vr_pay($result['data'],$rcb_pd_pay);
        }
        //计算本次需要在线支付的订单总金额
        $pay_amount = 0;
        if ($result['data']['order_state'] == ORDER_STATE_NEW) {
            $pay_amount += $result['data']['order_amount'] - $result['data']['pd_amount'] - $result['data']['rcb_amount'];
        }

        if ($pay_amount == 0) {
            redirect(WAP_SITE_URL.'/tmpl/member/vr_order_list.html');
        }

        $result['data']['api_pay_amount'] = ncPriceFormat($pay_amount);
        
        $update = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>$result['data']['order_id']));
        if(!$update) {
            return array('error' => '更新订单信息发生错误，请重新支付');
        }       

        //计算本次需要在线支付的订单总金额
        $pay_amount = $result['data']['order_amount'] - $result['data']['pd_amount'] - $result['data']['rcb_amount'];
        $result['data']['api_pay_amount'] = ncPriceFormat($pay_amount);

        return $result;
    }

    /**
     * 可用支付参数列表
     */
    public function payment_listOp() {
        $model_mb_payment = Model('mb_payment');

        $payment_list = $model_mb_payment->getMbPaymentOpenList();

        $payment_array = array();
        if(!empty($payment_list)) {
            foreach ($payment_list as $value) {
                $payment_array[] = $value['payment_code'];
            }
        }

        output_data(array('payment_list' => $payment_array));
    }

    /**
     * 微信APP订单支付
     */
    public function wx_app_payOp() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
    }

    /**
     * 微信APP虚拟订单支付
     */
    public function wx_app_vr_payOp() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'];

        $data = $this->_get_wx_pay_info($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
   }

    /**
     * 获取支付参数
     */
    private function _get_wx_pay_info($pay_param) {
        $access_token = $this->_get_wx_access_token();
        if(empty($access_token)) {
            return array('error' => '支付失败code:1001');
        }

        $package = $this->_get_wx_package($pay_param);

        $noncestr = md5($package + TIMESTAMP);
        $timestamp = TIMESTAMP;
        $traceid = $this->member_info['member_id'];

        // 获取预支付app_signature
        $param = array();
        $param['appid'] = $this->payment_config['wxpay_appid'];
        $param['noncestr'] = $noncestr;
        $param['package'] = $package;
        $param['timestamp'] = $timestamp;
        $param['traceid'] = $traceid;
        $app_signature = $this->_get_wx_signature($param);

        // 获取预支付编号
        $param['sign_method'] = 'sha1';
        $param['app_signature'] = $app_signature;
        $post_data = json_encode($param);
        $prepay_result = http_postdata('https://api.weixin.qq.com/pay/genprepay?access_token=' . $access_token, $post_data);
        $prepay_result = json_decode($prepay_result, true);
        if($prepay_result['errcode']) {
            return array('error' => '支付失败code:1002');
        }
        $prepayid = $prepay_result['prepayid'];

        // 生成正式支付参数
        $data = array();
        $data['appid'] = $this->payment_config['wxpay_appid'];
        $data['noncestr'] = $noncestr;
        $data['package'] = 'Sign=WXPay';
        $data['partnerid'] = $this->payment_config['wxpay_partnerid'];
        $data['prepayid'] = $prepayid;
        $data['timestamp'] = $timestamp;
        $sign = $this->_get_wx_signature($data);
        $data['sign'] = $sign;
        return $data;
    }

    /**
     * 获取微信access_token
     */
    private function _get_wx_access_token() {
        // 尝试读取缓存的access_token
        $access_token = rkcache('wx_access_token');
        if($access_token) {
            $access_token = unserialize($access_token);
            // 如果access_token未过期直接返回缓存的access_token
            if($access_token['time'] > TIMESTAMP) {
                return $access_token['token'];
            }
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
        $url = sprintf($url, $this->payment_config['wxpay_appid'], $this->payment_config['wxpay_appsecret']);
        $re = http_get($url);
        $result = json_decode($re, true);
        if($result['errcode']) {
            return '';
        }

        // 缓存获取的access_token
        $access_token = array();
        $access_token['token'] = $result['access_token'];
        $access_token['time'] = TIMESTAMP + $result['expires_in'];
        wkcache('wx_access_token', serialize($access_token));

        return $result['access_token'];
    }

    /**
     * 获取package
     */
    private function _get_wx_package($param) {
        $array = array();
        $array['bank_type'] = 'WX';
        $array['body'] = $param['subject'];
        $array['fee_type'] = 1;
        $array['input_charset'] = 'UTF-8';
        $array['notify_url'] = MOBILE_SITE_URL . '/api/payment/wxpay/notify_url.php';
        $array['out_trade_no'] = $param['pay_sn'];
        $array['partner'] = $this->payment_config['wxpay_partnerid'];
        $array['total_fee'] = $param['amount'];
        $array['spbill_create_ip'] = get_server_ip();

        ksort($array);

        $string = '';
        $string_encode = '';
        foreach ($array as $key => $val) {
            $string .= $key . '=' . $val . '&';
            $string_encode .= $key . '=' . urlencode($val). '&';
        }

        $stringSignTemp = $string . 'key=' . $this->payment_config['wxpay_partnerkey'];
        $signValue = md5($stringSignTemp);
        $signValue = strtoupper($signValue);

        $wx_package = $string_encode . 'sign=' . $signValue;
        return $wx_package;
    }

    /**
     * 获取微信支付签名
     */
    private function _get_wx_signature($param) {
        $param['appkey'] = $this->payment_config['wxpay_appkey'];

        $string = '';

        ksort($param);
        foreach ($param as $key => $value) {
            $string .= $key . '=' . $value . '&';
        }
        $string = rtrim($string, '&');

        $sign = sha1($string);

        return $sign;
    }

    /**
     * 微信APP订单支付
     */
    public function wx_app_pay3Op() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info3($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
    }

    /**
     * 微信APP虚拟订单支付
     */
    public function wx_app_vr_pay3Op() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info3($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
   }

    /**
     * 获取支付参数
     */
    private function _get_wx_pay_info3($pay_param) {
        $noncestr = md5(rand());

        $param = array();
        $param['appid'] = $this->payment_config['wxpay_appid'];
        $param['mch_id'] = $this->payment_config['wxpay_partnerid'];
        $param['nonce_str'] = $noncestr;
        $param['body'] = $pay_param['subject'];
        $param['out_trade_no'] = $pay_param['pay_sn'];
        $param['total_fee'] = $pay_param['amount'];
        $param['spbill_create_ip'] = get_server_ip();
        $param['notify_url'] = MOBILE_SITE_URL . '/api/payment/wxpay3/notify_url.php';
        $param['trade_type'] = 'APP';

        $sign = $this->_get_wx_pay_sign3($param);
        $param['sign'] = $sign;

        $post_data = '<xml>';
        foreach ($param as $key => $value) {
            $post_data .= '<' . $key .'>' . $value . '</' . $key . '>';
        }
        $post_data .= '</xml>';

        $prepay_result = http_postdata('https://api.mch.weixin.qq.com/pay/unifiedorder', $post_data);
        $prepay_result = simplexml_load_string($prepay_result);
        if($prepay_result->return_code != 'SUCCESS') {
            return array('error' => '支付失败code:1002');
        }

        // 生成正式支付参数
        $data = array();
        $data['appid'] = $this->payment_config['wxpay_appid'];
        $data['noncestr'] = $noncestr;
        //微信修改接口参数，否则IOS报解析失败
        //$data['package'] = 'prepay_id=' . $prepay_result->prepay_id;
        $data['package'] = 'Sign=WXPay';
        $data['partnerid'] = $this->payment_config['wxpay_partnerid'];
        $data['prepayid'] = (string)$prepay_result->prepay_id;
        $data['timestamp'] = TIMESTAMP;
        $sign = $this->_get_wx_pay_sign3($data);
        $data['sign'] = $sign;
        return $data;
    }

    private function _get_wx_pay_sign3($param) {
        ksort($param);
        foreach ($param as $key => $val) {
            $string .= $key . '=' . $val . '&';
        }
        $string .= 'key=' . $this->payment_config['wxpay_partnerkey'];
        return strtoupper(md5($string));
    }

    /**
     * 取得支付宝移动支付 订单信息 实物订单
     */
    public function alipay_native_payOp() {
        $pay_sn = $_POST['pay_sn'];
        if (!preg_match('/^\d+$/',$pay_sn)){
            output_error('支付单号错误');
        }
        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }
        
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
        if(!is_file($inc_file)){
            exit('支付接口不存在');
        }
        require($inc_file);
        $pay_info['data']['order_type'] = 'r';
        $payment_api = new $this->payment_code();
        $payment_api->init($this->payment_config,$pay_info['data']);
        $prestr = 'partner="'.$payment_api->param['partner']
        .'"&seller_id="'.$payment_api->param['seller_id']
        .'"&out_trade_no="'.$payment_api->param['out_trade_no']
        .'"&subject="'.$payment_api->param['subject'].'"&body="r"&total_fee="'
        .$payment_api->param['total_fee'].'"&notify_url="'
        .$payment_api->param['notify_url'].'"&service="mobile.securitypay.pay"&payment_type="1"&_input_charset="utf-8"&it_b_pay="1"';
        $mysign = $payment_api->mySign($prestr);
        output_data(array('signStr'=>$prestr.'&sign_type="RSA"&sign="'.urlencode($mysign).'"'));
    }

    /**
     * 取得支付宝移动支付 订单信息 虚拟订单
     */
    public function alipay_native_vr_payOp() {
        $pay_sn = $_POST['pay_sn'];
        if (!preg_match('/^\d+$/',$pay_sn)){
            output_error('支付单号错误');
        }
        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
        if(!is_file($inc_file)){
            exit('支付接口不存在');
        }
        require($inc_file);
        $pay_info['data']['order_type'] = 'v';
        $payment_api = new $this->payment_code();
        $payment_api->init($this->payment_config,$pay_info['data']);
        $prestr = 'partner="'.$payment_api->param['partner']
        .'"&seller_id="'.$payment_api->param['seller_id']
        .'"&out_trade_no="'.$payment_api->param['out_trade_no']
        .'"&subject="'.$payment_api->param['subject'].'"&body="v"&total_fee="'
        .$payment_api->param['total_fee'].'"&notify_url="'
        .$payment_api->param['notify_url'].'"&service="mobile.securitypay.pay"&payment_type="1"&_input_charset="utf-8"&it_b_pay="1"';
        $mysign = $payment_api->mySign($prestr);
        output_data(array('signStr'=>$prestr.'&sign_type="RSA"&sign="'.urlencode($mysign).'"'));

    }

}
